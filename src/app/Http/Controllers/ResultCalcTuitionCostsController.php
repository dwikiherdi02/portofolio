<?php

namespace App\Http\Controllers;

use App\Models\DataPerhitungan;
use App\Models\HasilPerhitungan;
use App\Models\Kriteria;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ResultCalcTuitionCostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('resultcalccosts.index');
    }

    public function dataTables(Request $request, DataTables $dataTables): JsonResponse
    {
        $model = TahunAjaran::query();
        return $dataTables->eloquent($model)
                ->filter(function ($query) use ($request) {
                    if (!empty($request->get('search'))) {
                        $search = strtolower($request->get('search'));
                        $query->where(function($query) use ($search) {
                            $query->where(DB::raw('lower(tahun)'), 'like', '%'. $search .'%');
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
                ->addColumn('status', function(TahunAjaran $tahunAjaran) {
                    if (
                        $tahunAjaran->dataPerhitungan->count() > 0 &&
                        $tahunAjaran->hasilPerhitungan->count() > 0
                    ) {
                        return 'Data sudah di entri';
                    } else {
                        return 'Data belum di entri';
                    }
                })
                ->addColumn('aksi', function(TahunAjaran $tahunAjaran) {
                    return view('resultcalccosts.datatables.action', compact('tahunAjaran'))->render();
                })
                ->rawColumns(['aksi'])
                ->toJson();
    }


    /**
     * Display the specified resource.
     */
    public function show(TahunAjaran $tahunAjaran): View
    {
        $kriteria = Kriteria::select('nama', DB::raw('LOWER(kode) as kode'))->orderBy('id', 'asc')->get();
        $kriteriaJson = array_column($kriteria->toArray(), 'kode');
        return view('resultcalccosts.show', compact('tahunAjaran', 'kriteria', 'kriteriaJson'));
    }

    public function dataTablesDetail(TahunAjaran $tahunAjaran, Request $request, DataTables $dataTables): JsonResponse
    {
        $kriteria = Kriteria::select('id', DB::raw('LOWER(kode) as kode'))->orderBy('id', 'asc')->get();
        $model = Siswa::where('id_tahun_ajaran', $tahunAjaran->id);
        $table = $dataTables->eloquent($model)->filter(function ($query) use ($request) {
                    if (!empty($request->get('search'))) {
                        $search = strtolower($request->get('search'));
                        $query->where(function($query) use ($search) {
                            $query->where(DB::raw('lower(nis)'), 'like', '%'. $search .'%')
                                ->orWhere(DB::raw('lower(nama)'), 'like', '%'. $search .'%');
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
                ->addColumn('nis', '{{ $nis }}')
                ->addColumn('nama', '{{ $nama }}');

        foreach ($kriteria as $item) {
            $table = $table->addColumn($item->kode, function(Siswa $siswa) use ($tahunAjaran, $item) {
                $data = DataPerhitungan::where([
                    'id_tahun_ajaran' => $tahunAjaran->id,
                    'id_siswa' => $siswa->id,
                    'id_kriteria' => $item->id,
                ])->first();
                return $data->nilaiKriteria->keterangan;
            });
        }

        return $table->toJson();
    }

    public function dataTablesCalcCriteriaStudent(TahunAjaran $tahunAjaran, Request $request, DataTables $dataTables): JsonResponse
    {
        $kriteria = Kriteria::select('id', DB::raw('LOWER(kode) as kode'))->orderBy('id', 'asc')->get();
        $model = Siswa::where('id_tahun_ajaran', $tahunAjaran->id);
        $table = $dataTables->eloquent($model)->order(function ($query) use ($request) {
                    $columns = array_column($request->get('columns'), 'data');
                    $orders = $request->get('order');
                    foreach ($orders as $order) {
                        $column = $columns[$order['column']];
                        $sort = $order['dir'];
                        $query->orderBy($column, $sort);
                    }
                })
                ->addColumn('nis', '{{ $nis }}')
                ->addColumn('nama', '{{ $nama }}');

        foreach ($kriteria as $item) {
            $table = $table->addColumn($item->kode, function(Siswa $siswa) use ($tahunAjaran, $item) {
                $data = DataPerhitungan::where([
                    'id_tahun_ajaran' => $tahunAjaran->id,
                    'id_siswa' => $siswa->id,
                    'id_kriteria' => $item->id,
                ])->first();
                return $data->bobot_nilai_kriteria;
            });
        }

        return $table->toJson();
    }

    public function dataTablesCalcCriteriaWeight(TahunAjaran $tahunAjaran, Request $request, DataTables $dataTables): JsonResponse
    {
        $model = Kriteria::query();
        return $dataTables->eloquent($model)->order(function ($query) use ($request) {
                    $query->orderBy('id', 'asc');
                })
                ->addColumn('nama', '{{ $nama }}')
                ->addColumn('bobot', '{{ $bobot }}')
                ->addColumn('min', function(Kriteria $kriteria) use ($tahunAjaran) {
                    $data = DataPerhitungan::select(
                        DB::raw('MIN(bobot_nilai_kriteria) AS bobot_min')
                    )->where([
                        "id_tahun_ajaran" => $tahunAjaran->id,
                        "id_kriteria" => $kriteria->id,
                    ])->first();
                    return $data->bobot_min;
                })
                ->addColumn('max', function(Kriteria $kriteria) use ($tahunAjaran) {
                    $data = DataPerhitungan::select(
                        DB::raw('MAX(bobot_nilai_kriteria) AS bobot_maks')
                    )->where([
                        "id_tahun_ajaran" => $tahunAjaran->id,
                        "id_kriteria" => $kriteria->id,
                    ])->first();
                    return $data->bobot_maks;
                })
                ->toJson();
    }

    public function dataTablesCalcCriteriaValue(TahunAjaran $tahunAjaran, Request $request, DataTables $dataTables): JsonResponse
    {
        $kriteria = Kriteria::select('id', DB::raw('LOWER(kode) as kode'))->orderBy('id', 'asc')->get();
        $model = Siswa::where('id_tahun_ajaran', $tahunAjaran->id);
        $table = $dataTables->eloquent($model)->order(function ($query) use ($request) {
                    $columns = array_column($request->get('columns'), 'data');
                    $orders = $request->get('order');
                    foreach ($orders as $order) {
                        $column = $columns[$order['column']];
                        $sort = $order['dir'];
                        $query->orderBy($column, $sort);
                    }
                })
                ->addColumn('nis', '{{ $nis }}')
                ->addColumn('nama', '{{ $nama }}');

        foreach ($kriteria as $item) {
            $table = $table->addColumn($item->kode, function(Siswa $siswa) use ($tahunAjaran, $item) {
                $data = DataPerhitungan::where([
                    'id_tahun_ajaran' => $tahunAjaran->id,
                    'id_siswa' => $siswa->id,
                    'id_kriteria' => $item->id,
                ])->first();
                return $data->rumus_normalisasi;
            });
        }

        return $table->toJson();
    }

    public function dataTablesCalcCriteriaResult(TahunAjaran $tahunAjaran, Request $request, DataTables $dataTables): JsonResponse
    {
        $kriteria = Kriteria::select('id', DB::raw('LOWER(kode) as kode'))->orderBy('id', 'asc')->get();
        $model = Siswa::where('id_tahun_ajaran', $tahunAjaran->id);
        $table = $dataTables->eloquent($model)->order(function ($query) use ($request) {
                    $columns = array_column($request->get('columns'), 'data');
                    $orders = $request->get('order');
                    foreach ($orders as $order) {
                        $column = $columns[$order['column']];
                        $sort = $order['dir'];
                        $query->orderBy($column, $sort);
                    }
                })
                ->addColumn('nis', '{{ $nis }}')
                ->addColumn('nama', '{{ $nama }}');

        foreach ($kriteria as $item) {
            $table = $table->addColumn($item->kode, function(Siswa $siswa) use ($tahunAjaran, $item) {
                $data = DataPerhitungan::where([
                    'id_tahun_ajaran' => $tahunAjaran->id,
                    'id_siswa' => $siswa->id,
                    'id_kriteria' => $item->id,
                ])->first();
                return $data->nilai_normalisasi;
            });
        }

        return $table->toJson();
    }

    public function dataTablesCalcPreferenceWeight(TahunAjaran $tahunAjaran, Request $request, DataTables $dataTables): JsonResponse {
        $kriteria = Kriteria::select('id', DB::raw('LOWER(kode) as kode'))->orderBy('id', 'asc')->get();
        $model = Siswa::where('id_tahun_ajaran', $tahunAjaran->id);
        $table = $dataTables->eloquent($model)->order(function ($query) use ($request) {
                    $columns = array_column($request->get('columns'), 'data');
                    $orders = $request->get('order');
                    foreach ($orders as $order) {
                        $column = $columns[$order['column']];
                        $sort = $order['dir'];
                        $query->orderBy($column, $sort);
                    }
                })
                ->addColumn('nis', '{{ $nis }}')
                ->addColumn('nama', '{{ $nama }}');

        foreach ($kriteria as $item) {
            $table = $table->addColumn($item->kode, function(Siswa $siswa) use ($tahunAjaran, $item) {
                $data = DataPerhitungan::where([
                    'id_tahun_ajaran' => $tahunAjaran->id,
                    'id_siswa' => $siswa->id,
                    'id_kriteria' => $item->id,
                ])->first();
                return $data->rumus_preferensi;
            });
        }

        return $table->toJson();
    }

    public function dataTablesCalcPreferenceResult(TahunAjaran $tahunAjaran, Request $request, DataTables $dataTables): JsonResponse
    {
        $kriteria = Kriteria::select('id', DB::raw('LOWER(kode) as kode'))->orderBy('id', 'asc')->get();
        $model = Siswa::where('id_tahun_ajaran', $tahunAjaran->id);
        $table = $dataTables->eloquent($model)->order(function ($query) use ($request) {
                    $columns = array_column($request->get('columns'), 'data');
                    $orders = $request->get('order');
                    foreach ($orders as $order) {
                        $column = $columns[$order['column']];
                        $sort = $order['dir'];
                        $query->orderBy($column, $sort);
                    }
                })
                ->addColumn('nis', '{{ $nis }}')
                ->addColumn('nama', '{{ $nama }}');

        foreach ($kriteria as $item) {
            $table = $table->addColumn($item->kode, function(Siswa $siswa) use ($tahunAjaran, $item) {
                $data = DataPerhitungan::where([
                    'id_tahun_ajaran' => $tahunAjaran->id,
                    'id_siswa' => $siswa->id,
                    'id_kriteria' => $item->id,
                ])->first();
                return $data->nilai_preferensi;
            });
        }

        $table = $table->addColumn('total', function(Siswa $siswa) use ($tahunAjaran) {
            $data = HasilPerhitungan::where([
                'id_tahun_ajaran' => $tahunAjaran->id,
                'id_siswa' => $siswa->id
            ])->first();
            return $data->total_preferensi;
        });

        return $table->toJson();
    }

    public function dataTablesResult(TahunAjaran $tahunAjaran, Request $request, DataTables $dataTables): JsonResponse
    {
        $model = HasilPerhitungan::where('id_tahun_ajaran', $tahunAjaran->id);
        return $dataTables->eloquent($model)->filter(function ($query) use ($request) {
                    if (!empty($request->get('search'))) {
                        $search = strtolower($request->get('search'));
                        $query->where(function($query) use ($search) {
                            $query->where(DB::raw('lower(total_preferensi)'), 'like', '%'. $search .'%')
                                ->orWhere(DB::raw('lower(biaya)'), 'like', '%'. $search .'%')
                                ->orWhereHas('siswa', function (Builder $qhas) use ($search) {
                                    $qhas->where(DB::raw('lower(nis)'), 'like', '%'. $search .'%')
                                        ->orWhere(DB::raw('lower(nama)'), 'like', '%'. $search .'%');
                                });
                        });
                    }
                })
                ->order(function ($query) use ($request) {
                    $columns = array_column($request->get('columns'), 'data');
                    $orders = $request->get('order');
                    foreach ($orders as $order) {
                        $column = $columns[$order['column']];
                        if (in_array($column, ['nis', 'nama'])) {
                            $sort = $order['dir'];
                            $query->join(DB::raw("(SELECT id, nama, nis FROM siswa) AS siswa"), 'siswa.id', '=', 'hasil_perhitungan.id_siswa')
                                ->orderBy("siswa.{$column}", $sort);
                        } else {
                            $sort = $order['dir'];
                            $query->orderBy($column, $sort);
                        }
                    }
                })
                ->addColumn('nis', function(HasilPerhitungan $hasil) {
                    return $hasil->siswa->nis;
                })
                ->addColumn('nama', function(HasilPerhitungan $hasil) {
                    return $hasil->siswa->nama;
                })
                ->addColumn('total_preferensi', function(HasilPerhitungan $hasil) {
                    return number_format($hasil->total_preferensi, 2, ".");
                })
                ->addColumn('biaya', function(HasilPerhitungan $hasil) {
                    return number_format($hasil->biaya);
                })
                ->toJson();
    }

    public function exportPdf(TahunAjaran $tahunAjaran): Response {
        $hasil = [];
        HasilPerhitungan::where('id_tahun_ajaran', $tahunAjaran->id)
                            ->join(DB::raw("(SELECT id, nama, nis FROM siswa) AS siswa"), 'siswa.id', '=', 'hasil_perhitungan.id_siswa')
                            ->orderBy("siswa.nama", 'asc')
                            ->chunk(200, function(Collection $queries) use (&$hasil) {
                                foreach ($queries as $query) {
                                    $hasil[] = $query;
                                }
                            });
        $pdf = Pdf::loadView('resultcalccosts.show.export', compact('tahunAjaran', 'hasil'));
        $filename = "Biaya-SPP-Siswa-Tahun-{$tahunAjaran->tahun}.pdf";
        return $pdf->download($filename);
    }
}
