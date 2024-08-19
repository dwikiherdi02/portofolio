<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Libraries\PhpSpreadsheet\ReadFilter;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use File;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('student.index');
    }

    public function dataTables(Request $request, DataTables $dataTables): JsonResponse
    {
        $model = TahunAjaran::query();
        return $dataTables->eloquent($model)
            ->filter(function ($query) use ($request) {
                if (!empty($request->get('search'))) {
                    $search = strtolower($request->get('search'));
                    $query->where(function ($query) use ($search) {
                        $query->where(DB::raw('lower(tahun)'), 'like', '%' . $search . '%');
                    });
                }
            })
            ->order(function ($query) use ($request) {
                $columns = array_column($request->get('columns'), 'data');
                $orders = $request->get('order');
                foreach ($orders as $order) {
                    $column = $columns[$order['column']];
                    $sort = $order['dir'];
                    $query->orderBy($column, $sort);
                }
            })
            ->addColumn('tahun', '{{ $tahun }}')
            ->addColumn('status', function (TahunAjaran $tahunAjaran) {
                if ($tahunAjaran->siswa->count() > 0) {
                    return 'Data sudah di entri';
                } else {
                    return 'Data belum di entri';
                }
            })
            ->addColumn('total', function (TahunAjaran $tahunAjaran) {
                return $tahunAjaran->siswa->count();
            })
            ->addColumn('aksi', function (TahunAjaran $tahunAjaran) {
                return view('student.datatables.action', compact('tahunAjaran'))->render();
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function downloadStudentTemplate()
    {
        $now = strtotime(now());
        $filename = 'template-siswa.xlsx';
        $dir = storage_path("app/tmp/$now");

        $styleArray = [
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

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // removing default worksheet

        $studentSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Data Siswa');
        $spreadsheet->addSheet($studentSheet, 0);
        $activeStudentSheet = $spreadsheet->setActiveSheetIndex(0);
        $activeStudentSheet->getStyle('A1:B1')->applyFromArray($styleArray);
        $activeStudentSheet->getRowDimension(1)->setRowHeight(30);
        $activeStudentSheet->setCellValue('A1', 'NISN');
        $activeStudentSheet->setCellValue('B1', 'Nama Siswa');


        $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($activeStudentSheet->getHighestDataColumn());
        for ($i = 1; $i <= $lastColumnIndex; $i++) {
            $activeStudentSheet->getColumnDimensionByColumn($i)->setAutoSize(false)->setWidth(35);
        }

        $writer = new Xlsx($spreadsheet);

        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="' . $filename . '"');
        // $writer->save('php://output');

        // File::makeDirectory($dir, 755, true);
        // $writer->save("$dir/$filename");
        // return response()->download("$dir/$filename")->deleteFileAfterSend(true);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);

    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAjaran $tahunAjaran): View
    {
        return view('student.show', compact('tahunAjaran'));
    }

    public function showDataTables(TahunAjaran $tahunAjaran, Request $request, DataTables $dataTables): JsonResponse
    {
        $model = Siswa::where('id_tahun_ajaran', $tahunAjaran->id);
        return $dataTables->eloquent($model)
            ->filter(function ($query) use ($request) {
                if (!empty($request->get('search'))) {
                    $search = strtolower($request->get('search'));
                    $query->where(function ($query) use ($search) {
                        $query->where(DB::raw('lower(nama)'), 'like', '%' . $search . '%')
                            ->orWhere(DB::raw('lower(nis)'), 'like', '%' . $search . '%');
                    });
                }
            })
            ->order(function ($query) use ($request) {
                $columns = array_column($request->get('columns'), 'data');
                $orders = $request->get('order');
                foreach ($orders as $order) {
                    $column = $columns[$order['column']];
                    $sort = $order['dir'];
                    $query->orderBy($column, $sort);
                }
            })
            ->addColumn('nisn', '{{ $nis }}')
            ->addColumn('nama', '{{ $nama }}')
            // ->addColumn('status', function(Siswa $siswa) {
            //     return view('student.show.datatables.status', compact('siswa'))->render();
            // })
            // ->addColumn('aksi', function(Siswa $siswa) {
            //     return view('student.show.datatables.action', compact('siswa'))->render();
            // })
            // ->rawColumns(['aksi'])
            ->toJson();
    }

    public function exportPdf(TahunAjaran $tahunAjaran): \Illuminate\Http\Response
    {
        $siswa = [];
        Siswa::where('id_tahun_ajaran', $tahunAjaran->id)
            ->orderBy('nama', 'asc')
            ->chunk(200, function (Collection $queries) use (&$siswa) {
                foreach ($queries as $query) {
                    $siswa[] = $query;
                }
            });
        $pdf = Pdf::loadView('student.export', compact('tahunAjaran', 'siswa'));
        $filename = "Siswa-Tahun-{$tahunAjaran->tahun}.pdf";
        return $pdf->download($filename);
    }

    public function showDestroy(TahunAjaran $tahunAjaran): JsonResponse
    {
        DB::beginTransaction();
        try {
            $tahunAjaran->siswa->each->delete();
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
        }

        return response()->json([
            'code' => 200,
            'msg-key' => 'SUCCESS',
            'message' => 'success.'
        ]);
    }

    public function processFetchingExcelData(TahunAjaran $tahunAjaran, StudentRequest $request): JsonResponse
    {
        if ($request->ajax()) {
            if ($errMsg = $request->validateFetchingExcelData()) {
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

            /**  Create an Instance of our Read Filter  **/
            $filterSubset = new ReadFilter(0, range('A', 'B'));

            /**  Create a new Reader of the type defined in $inputFileType  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucFirst($file->extension()));
            /**  Tell the Reader that we want to use the Read Filter  **/
            $reader->setReadFilter($filterSubset);

            /**  Advise the Reader that we only want to load cell data  **/
            $reader->setReadDataOnly(true);

            $reader->setReadEmptyCells(false);

            /**  Load only the rows and columns that match our filter to Spreadsheet  **/
            $spreadsheet = $reader->load($file->getRealPath());
            $studentSheet = $spreadsheet->setActiveSheetIndex(0);
            $studentData = [];
            for ($i = 2; $i <= $studentSheet->getHighestDataRow(); $i++) {
                $nisn = $studentSheet->getCell("A{$i}")->getValue();
                $studentName = $studentSheet->getCell("B{$i}")->getValue();
                $studentData[] = [
                    'nis' => $nisn,
                    'nama' => $studentName,
                ];
            }

            // $worksheetData = $reader->listWorksheetInfo($file->getRealPath());
            // $worksheetData = $reader->listWorksheetInfo
            // dd($worksheetData);


            return response()->json([
                'code' => 200,
                'msg-key' => 'SUCCESS',
                'message' => 'success.',
                'data' => [
                    'is_exist' => Siswa::where('id_tahun_ajaran', $tahunAjaran->id)->count() > 0,
                    'students' => $studentData
                ]
            ]);
        }

        abort(404);
    }

    public function processStoringData(TahunAjaran $tahunAjaran, StudentRequest $request): JsonResponse
    {
        if ($request->ajax() && !$request->validateProcessStoringData()) {
            $data = $request->all();
            $data['id_tahun_ajaran'] = $tahunAjaran->id;

            DB::beginTransaction();
            try {
                $siswa = new Siswa;
                $siswa->fill($data);
                $siswa->save();
                DB::commit();
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollback();
            }

        }
        return response()->json([
            'code' => 200,
            'msg-key' => 'SUCCESS',
            'message' => 'success.'
        ]);
    }
}
