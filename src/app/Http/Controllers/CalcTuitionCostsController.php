<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalcTuitionCostsRequest;
use App\Libraries\PhpSpreadsheet\ReadFilter;
use App\Models\BiayaSPP;
use App\Models\DataPerhitungan;
use App\Models\HasilPerhitungan;
use App\Models\Kriteria;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CalcTuitionCostsController extends Controller
{
    protected const spreadsheetHeaderStyle = [
        'font' => [
            'bold' => true,
            'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE],
        ],
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'CB0C9F',
            ],
        ],
    ];

    protected const spreadsheetBodyStyle = [
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAjaran = TahunAjaran::select('id', 'tahun')
            ->whereHas('biayaSPP')
            ->whereHas('siswa')
            ->orderBy('tahun', 'asc')->get();
        return view('calctuitioncosts.index', compact('tahunAjaran'));
    }


    public function downladFile(Request $request)
    {
        if (!$request->has('syid')) {
            abort(404);
        }

        $tahunAjaran = TahunAjaran::findOrFail($request->get('syid'));

        $siswa = Siswa::where('id_tahun_ajaran', $request->get('syid'));

        if ($siswa->count() < 1) {
            abort(404);
        }

        $filename = "data-siswa-{$tahunAjaran->tahun}.xlsx";

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // removing default worksheet

        $criteriaSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Kriteria');
        $spreadsheet->addSheet($criteriaSheet, 0);

        $studentSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Data Siswa');
        $spreadsheet->addSheet($studentSheet, 1);

        // Generate criteria sheet
        $criteriaRows = $this->criteriaSheet($spreadsheet);

        // Generate students sheet
        $this->studentsSheet($spreadsheet, $siswa->get(), $criteriaRows);

        $writer = new Xlsx($spreadsheet);

        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="' . $filename . '"');
        // $writer->save('php://output');

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    public function storeCheck(CalcTuitionCostsRequest $request): JsonResponse
    {
        if ($request->ajax()) {
            if ($errMsg = $request->validateStore()) {
                return response()->json(
                    [
                        'code' => 422,
                        'msg-key' => 'ERROR-VALIDATION',
                        'message' => 'validation error.',
                        'data' => $errMsg
                    ],
                    422
                );
            }

            $tahunAjaran = TahunAjaran::find($request->get('id_tahun_ajaran'));
            $isDeleteExistingData = false;
            if ($tahunAjaran->dataPerhitungan->count() > 0 || $tahunAjaran->hasilPerhitungan->count() > 0) {
                $isDeleteExistingData = true;
            }

            return response()->json([
                'code' => 200,
                'msg-key' => 'SUCCESS',
                'message' => 'success.',
                'data' => [
                    'is_delete_existing' => $isDeleteExistingData
                ]
            ]);
        }

        abort(404);
    }

    public function storeImport(CalcTuitionCostsRequest $request): JsonResponse
    {
        if ($request->ajax()) {
            if ($errMsg = $request->validateStore()) {
                return response()->json(
                    [
                        'code' => 422,
                        'msg-key' => 'ERROR-VALIDATION',
                        'message' => 'validation error.',
                        'data' => $errMsg
                    ],
                    422
                );
            }

            $file = $request->file('berkas');
            $kriteria = Kriteria::select('id', 'kode')->get();
            $tahunAjaran = TahunAjaran::find($request->get('id_tahun_ajaran'));
            if (empty($tahunAjaran)) {
                return response()->json([
                    'code' => 404,
                    'msg-key' => 'NOTFOUND',
                    'message' => 'data tahun tidak ditemukan.'
                ], 404);
            }

            $startCol = $endCol = 'A';
            for ($i = 0; $i <= $kriteria->count(); $i++) {
                $endCol++;
            }
            $rangeCol = range($startCol, $endCol);

            $criteria = [];
            foreach ($kriteria as $key => $itemK) {
                $criteria[$key] = [
                    'id' => $itemK->id,
                    'kode' => $itemK->kode,
                ];
                foreach ($itemK->nilaiKriteria as $itemNK) {
                    $criteria[$key]['nilai'][$itemNK->keterangan] = [
                        'id' => $itemNK->id,
                        'kode' => $itemNK->kode,
                        'bobot' => $itemNK->bobot
                    ];
                }
            }

            /**  Create an Instance of our Read Filter  **/
            $filterSubset = new ReadFilter(0, $rangeCol);

            /**  Create a new Reader of the type defined in $inputFileType  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucFirst($file->extension()));
            /**  Tell the Reader that we want to use the Read Filter  **/
            $reader->setReadFilter($filterSubset);

            /**  Advise the Reader that we only want to load cell data  **/
            $reader->setReadDataOnly(true);

            $reader->setReadEmptyCells(false);

            /**  Load only the rows and columns that match our filter to Spreadsheet  **/
            $spreadsheet = $reader->load($file->getRealPath());
            $studentSheet = $spreadsheet->setActiveSheetIndex(1);
            $studentData = [];
            $studentCount = 0;
            for ($i = 2; $i <= $studentSheet->getHighestDataRow(); $i++) {
                $criteriaCount = 0;
                $data = [];
                foreach ($rangeCol as $col) {
                    $column = "{$col}{$i}";
                    $valCell = $studentSheet->getCell($column)->getValue();
                    switch ($col) {
                        case 'A':
                            $now = now();
                            // $data[$i]['id'] = (string) Str::orderedUuid();
                            $data[$i]['id_tahun_ajaran'] = $tahunAjaran->id;
                            $data[$i]['created_at'] = $now;
                            $data[$i]['updated_at'] = $now;
                            break;

                        case 'B':
                            $siswa = Siswa::where([
                                'nis' => $valCell,
                                'id_tahun_ajaran' => $tahunAjaran->id,
                            ])->first();
                            if (empty($siswa)) {
                                return response()->json([
                                    'code' => 404,
                                    'msg-key' => 'NOTFOUND',
                                    'message' => "NISN {$valCell} di kolom {$column} tidak ditemukan pada data siswa tahun {$tahunAjaran->tahun}"
                                ], 404);
                            }

                            $data[$i]['id_siswa'] = $siswa->id;
                            break;

                        default:
                            $c = $criteria[$criteriaCount];
                            if (empty($valCell) || empty($nilai = $c['nilai'][$valCell])) {
                                return response()->json([
                                    'code' => 406,
                                    'msg-key' => 'NOTFOUND',
                                    'message' => "Isi kolom {$column} tidak valid"
                                ], 404);
                            }
                            $studentData[$studentCount] = array_merge(
                                $data[$i],
                                [
                                    'id_kriteria' => $c['id'],
                                    'id_nilai_kriteria' => $nilai['id'],
                                    'bobot_nilai_kriteria' => $nilai['bobot'],
                                ]
                            );
                            $criteriaCount++;
                            $studentCount++;
                            break;
                    }
                }
            }

            DB::beginTransaction();
            try {
                DataPerhitungan::insert($studentData);
                DB::commit();
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollback();
                return response()->json([
                    'code' => 500,
                    'msg-key' => 'SERVER-ERROR',
                    'message' => $e->getMessage()
                ], 500);
            }

            return response()->json([
                'code' => 200,
                'msg-key' => 'SUCCESS',
                'message' => 'success.'
            ]);
        }

        abort(404);
    }

    public function storeCalculate(CalcTuitionCostsRequest $request): JsonResponse
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $kriteria = Kriteria::select('id', 'id_jenis_kriteria', 'bobot')->get();
                $tahunAjaran = TahunAjaran::find($request->get('id_tahun_ajaran'));
                if (empty($tahunAjaran)) {
                    return response()->json([
                        'code' => 404,
                        'msg-key' => 'NOTFOUND',
                        'message' => 'Data tahun ajaran tidak ditemukan.'
                    ], 404);
                }
                $biayaSPP = BiayaSPP::where('id_tahun_ajaran', $tahunAjaran->id)->orderBy('id', 'asc')->get();
                if ($biayaSPP->count() < 1) {
                    return response()->json([
                        'code' => 404,
                        'msg-key' => 'NOTFOUND',
                        'message' => 'Data biaya SPP tidak ditemukan.'
                    ], 404);
                }

                $criteria = [];
                foreach ($kriteria as $item) {
                    $qualityMinMax = DataPerhitungan::select(
                        DB::raw('MIN(bobot_nilai_kriteria) AS bobot_min'),
                        DB::raw('MAX(bobot_nilai_kriteria) AS bobot_maks')
                    )->where([
                                "id_tahun_ajaran" => $tahunAjaran->id,
                                "id_kriteria" => $item->id,
                            ])->first();

                    if (empty($qualityMinMax)) {
                        return response()->json([
                            'code' => 404,
                            'msg-key' => 'SUCCESS',
                            'message' => "Bobot minimal dan maksimal kriteria {$item->nama} tidak ditemukan"
                        ], 404);
                    }

                    $criteria[$item->id] = [
                        'type' => $item->jenisKriteria->kode,
                        'quality' => $item->bobot,
                        'min' => $qualityMinMax->bobot_min,
                        'max' => $qualityMinMax->bobot_maks,
                    ];
                }

                $dataPerhitunganSiswa = DataPerhitungan::select('id_tahun_ajaran', 'id_siswa')->where('id_tahun_ajaran', $tahunAjaran->id)->groupBy('id_siswa')->orderBy('id_siswa', 'asc')->get();

                $data = [];
                foreach ($dataPerhitunganSiswa as $keyDPS => $itemDPS) {
                    $now = now();
                    // $data[$keyDPS]['id'] = (string) Str::orderedUuid();
                    $data[$keyDPS]['id_tahun_ajaran'] = $itemDPS->id_tahun_ajaran;
                    $data[$keyDPS]['id_siswa'] = $itemDPS->id_siswa;
                    $data[$keyDPS]['created_at'] = $now;
                    $data[$keyDPS]['updated_at'] = $now;

                    $dataSiswa = DataPerhitungan::where([
                        'id_tahun_ajaran' => $itemDPS->id_tahun_ajaran,
                        'id_siswa' => $itemDPS->id_siswa,
                    ])->orderBy('id_kriteria', 'asc')->get();

                    $totalPreference = 0;
                    foreach ($dataSiswa as $itemS) {
                        $c = $criteria[$itemS->id_kriteria];

                        if (empty($c)) {
                            return response()->json([
                                'code' => 404,
                                'msg-key' => 'SUCCESS',
                                'message' => "Kriteria tidak valid"
                            ], 404);
                        }

                        $rij = 0;
                        $formulaNormalization = "";
                        switch ($c['type']) {
                            case 'b':
                                $formulaNormalization = "$itemS->bobot_nilai_kriteria / $c[max]";
                                $rij = $itemS->bobot_nilai_kriteria / $c['max'];
                                break;

                            case 'c':
                                $formulaNormalization = "$c[min]  / $itemS->bobot_nilai_kriteria";
                                $rij = $c['min'] / $itemS->bobot_nilai_kriteria;
                                break;
                        }

                        $formulaPreference = "$c[quality] * $rij";
                        $calcTtlPreference = $c['quality'] * $rij;
                        $totalPreference = $totalPreference + $calcTtlPreference;

                        DataPerhitungan::where([
                            'id_tahun_ajaran' => $itemS->id_tahun_ajaran,
                            'id_siswa' => $itemS->id_siswa,
                            'id_kriteria' => $itemS->id_kriteria,
                        ])->update([
                                    'bobot_nilai_maks' => $c['max'],
                                    'bobot_nilai_min' => $c['min'],
                                    'rumus_normalisasi' => $formulaNormalization,
                                    'nilai_normalisasi' => $rij,
                                    'bobot_kriteria' => $c['quality'],
                                    'rumus_preferensi' => $formulaPreference,
                                    'nilai_preferensi' => $calcTtlPreference,
                                ]);
                    }

                    $data[$keyDPS]['total_preferensi'] = $totalPreference;

                    foreach ($biayaSPP as $itemSPP) {
                        if ((empty($itemSPP->bobot_minimal) && !empty($itemSPP->bobot_maksimal)) && $totalPreference <= $itemSPP->bobot_maksimal) {
                            $data[$keyDPS]['biaya'] = $itemSPP->nilai;
                            break;
                        }

                        if ((!empty($itemSPP->bobot_minimal) && !empty($itemSPP->bobot_maksimal)) && ($totalPreference > $itemSPP->bobot_minimal && $totalPreference <= $itemSPP->bobot_maksimal)) {
                            $data[$keyDPS]['biaya'] = $itemSPP->nilai;
                            break;
                        }

                        if ((!empty($itemSPP->bobot_minimal) && empty($itemSPP->bobot_maksimal)) && $totalPreference > $itemSPP->bobot_minimal) {
                            $data[$keyDPS]['biaya'] = $itemSPP->nilai;
                            break;
                        }
                    }
                }

                HasilPerhitungan::insert($data);
                DB::commit();
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollback();
                return response()->json([
                    'code' => 500,
                    'msg-key' => 'SERVER-ERROR',
                    'message' => $e->getMessage()
                ], 500);
            }

            Session::flash('notif-success', 'Perhitungan biaya SPP berhasil di proses.');
            $redirectTo = route('hasil_biaya_spp.lihat', $tahunAjaran->id);
            return response()->json([
                'code' => 200,
                'msg-key' => 'SUCCESS',
                'message' => 'success.',
                'data' => [
                    'redirect_to' => $redirectTo
                ]
            ]);
        }
        abort(404);
    }

    public function destroyByNewSchoolYear(CalcTuitionCostsRequest $request): JsonResponse
    {
        if ($request->ajax()) {
            $tahunAjaran = TahunAjaran::find($request->get('id_tahun_ajaran'));
            if (empty($tahunAjaran)) {
                return response()->json([
                    'code' => 404,
                    'msg-key' => 'NOTFOUND',
                    'message' => 'data tahun tidak ditemukan.'
                ], 404);
            }
            DB::beginTransaction();
            try {
                DataPerhitungan::where('id_tahun_ajaran', $tahunAjaran->id)->delete();
                HasilPerhitungan::where('id_tahun_ajaran', $tahunAjaran->id)->delete();
                // $tahunAjaran->dataPerhitungan->each->delete();
                // $tahunAjaran->hasilPerhitungan->each->delete();
                DB::commit();
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollback();
                return response()->json([
                    'code' => 500,
                    'msg-key' => 'SERVER-ERROR',
                    'message' => $e->getMessage()
                ], 500);
            }
            return response()->json([
                'code' => 200,
                'msg-key' => 'SUCCESS',
                'message' => 'success.'
            ]);
        }
        abort(404);
    }

    protected function criteriaSheet(Spreadsheet $spreadsheet): array
    {
        $indexCurrentCell = 1;
        $kriteria = Kriteria::orderBy('id', 'asc')->get();
        $criteriaValueRows = [];

        $activeSheet = $spreadsheet->setActiveSheetIndex(0)->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);

        foreach ($kriteria as $itemKriteria) {
            $activeSheet->getStyle("A{$indexCurrentCell}")->getFont()->setSize(14)->setBold(true);
            $activeSheet->setCellValue("A{$indexCurrentCell}", "{$itemKriteria->nama} ({$itemKriteria->kode})");
            $activeSheet->mergeCells("A{$indexCurrentCell}:B{$indexCurrentCell}");

            $indexCurrentCell++;
            $activeSheet->getStyle("A{$indexCurrentCell}:B{$indexCurrentCell}")->applyFromArray(self::spreadsheetHeaderStyle);
            $activeSheet->getRowDimension($indexCurrentCell)->setRowHeight(30);
            $activeSheet->setCellValue("A{$indexCurrentCell}", 'Kriteria');
            $activeSheet->setCellValue("B{$indexCurrentCell}", 'Kode');

            $nilaiKriteria = $itemKriteria->nilaiKriteria;
            foreach ($nilaiKriteria as $indexNilaiKriteria => $itemNilaiKriteria) {
                $indexCurrentCell++;
                if ($indexNilaiKriteria == 0 || ($nilaiKriteria->count() - 1) == $indexNilaiKriteria) {
                    $criteriaValueRows[$itemKriteria->kode][] = $indexCurrentCell;
                }
                $activeSheet->getStyle("A{$indexCurrentCell}:B{$indexCurrentCell}")->applyFromArray(self::spreadsheetBodyStyle);
                $activeSheet->getStyle("B{$indexCurrentCell}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $activeSheet->setCellValue("A{$indexCurrentCell}", "{$itemNilaiKriteria->keterangan}");
                $activeSheet->setCellValue("B{$indexCurrentCell}", "{$itemNilaiKriteria->kode}");
            }

            $indexCurrentCell += 3;
        }

        $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($activeSheet->getHighestDataColumn());
        for ($i = 1; $i <= $lastColumnIndex; $i++) {
            // $activeSheet->getColumnDimensionByColumn($i)->setAutoSize(true);
            $activeSheet->getColumnDimensionByColumn($i)->setAutoSize(false)->setWidth(35);
        }

        return $criteriaValueRows;
    }

    protected function studentsSheet(Spreadsheet $spreadsheet, object $siswa, array $criteriaRows = []): Worksheet
    {
        $indexCurrentCell = 1;
        $kriteria = Kriteria::orderBy('id', 'asc')->get();
        $countCriteria = $kriteria->count();
        $criteriaColumn = 'C';
        $criteriaColumns = [];
        do {
            $criteriaColumns[] = $criteriaColumn;
            $criteriaColumn++;
        } while (--$countCriteria > 0);
        $endColumn = end($criteriaColumns);

        $activeSheet = $spreadsheet->setActiveSheetIndex(1);

        $activeSheet->getStyle("A{$indexCurrentCell}:{$endColumn}{$indexCurrentCell}")->applyFromArray(self::spreadsheetHeaderStyle);
        $activeSheet->getRowDimension($indexCurrentCell)->setRowHeight(30);
        $activeSheet->setCellValue("A{$indexCurrentCell}", 'Nama');
        $activeSheet->setCellValue("B{$indexCurrentCell}", 'NISN');
        foreach ($kriteria as $index => $item) {
            $criteriaCol = $criteriaColumns[$index];
            $activeSheet->setCellValue("{$criteriaCol}{$indexCurrentCell}", "{$item->nama}");
        }

        foreach ($siswa as $item) {
            $indexCurrentCell++;
            $activeSheet->getStyle("A{$indexCurrentCell}:{$endColumn}{$indexCurrentCell}")->applyFromArray(self::spreadsheetBodyStyle);
            $activeSheet->getStyle("B{$indexCurrentCell}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $activeSheet->setCellValue("A{$indexCurrentCell}", "{$item->nama}");
            $activeSheet->setCellValue("B{$indexCurrentCell}", "{$item->nis}");
            foreach ($kriteria as $index => $item) {
                $criteriaCol = $criteriaColumns[$index];
                $startRow = $criteriaRows[$item->kode][0];
                $endRow = $criteriaRows[$item->kode][1];
                // $activeSheet->setCellValue("{$criteriaCol}{$indexCurrentCell}", "{$item->kode}");
                $dropdownlist = $activeSheet->getCell("{$criteriaCol}{$indexCurrentCell}")->getDataValidation();
                $dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                    ->setFormula1('=Kriteria!$A$' . $startRow . ':$A$' . $endRow)
                    ->setAllowBlank(false)
                    ->setShowDropDown(true)
                    ->setShowInputMessage(true)
                    ->setPromptTitle('Pilih kriteria')
                    ->setPrompt('Wajib memilih opsi kriteria yang ada di list')
                    ->setShowErrorMessage(true)
                    ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)
                    ->setErrorTitle('Pilihan tidak valid')
                    ->setError('Opsi kriteria tidak tersedia');
            }
        }

        $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($activeSheet->getHighestDataColumn());
        for ($i = 1; $i <= $lastColumnIndex; $i++) {
            if ($i < 3) {
                $activeSheet->getColumnDimensionByColumn($i)->setAutoSize(false)->setWidth(20);
            } else {
                $activeSheet->getColumnDimensionByColumn($i)->setAutoSize(true);
            }
        }

        return $activeSheet;
    }
}
