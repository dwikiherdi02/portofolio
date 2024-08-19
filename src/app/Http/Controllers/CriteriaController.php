<?php

namespace App\Http\Controllers;

use App\Http\Requests\CriteriaRequest;
use App\Models\Bobot;
use App\Models\JenisKriteria;
use App\Models\Kriteria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('criteria.index');
    }

    public function dataTables(Request $request, DataTables $dataTables): JsonResponse
    {
        $model = Kriteria::query();
        return $dataTables->eloquent($model)
            ->filter(function ($query) use ($request) {
                if (!empty($request->get('search'))) {
                    $search = strtolower($request->get('search'));
                    $query->where(function ($query) use ($search) {
                        $query->where(DB::raw('lower(nama)'), 'like', '%' . $search . '%')
                            ->orWhere(DB::raw('lower(kode)'), 'like', '%' . $search . '%')
                            ->orWhereHas('jenisKriteria', function (Builder $qhas) use ($search) {
                                $qhas->where(DB::raw('lower(nama)'), 'like', '%' . $search . '%');
                            })
                            ->orWhereHas('bobotByNilai', function (Builder $qhas) use ($search) {
                                $qhas->where(DB::raw('lower(nama)'), 'like', '%' . $search . '%');
                            });
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
            ->addColumn('nama', '{{ $nama }}')
            ->addColumn('kode', '{{ $kode }}')
            ->addColumn('nama_jenis_kriteria', function (Kriteria $kriteria) {
                return $kriteria->jenisKriteria->nama ?? '-';
            })
            ->addColumn('bobot', '{{ $bobot }}')
            ->addColumn('aksi', function (Kriteria $kriteria) {
                return view('criteria.datatables.action', compact('kriteria'))->render();
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function exportPdf(): Response
    {
        $kriteria = [];
        Kriteria::chunk(200, function (Collection $queries) use (&$kriteria) {
            foreach ($queries as $query) {
                $kriteria[] = $query;
            }
        });
        $pdf = Pdf::loadView('criteria.export', compact('kriteria'));
        return $pdf->download('kriteria.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bobot = Bobot::select('nama', 'kode', 'nilai')->orderBy('id', 'asc')->get();
        $jenisKriteria = JenisKriteria::select('id', 'nama', 'keterangan')->orderBy('id', 'asc')->get();
        return view('criteria.create', compact('bobot', 'jenisKriteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CriteriaRequest $request): RedirectResponse|JsonResponse
    {
        if ($request->ajax()) {
            if ($errMsg = $request->validate()) {
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

            return response()->json([
                'code' => 200,
                'msg-key' => 'SUCCESS',
                'message' => 'success.'
            ]);
        }

        DB::beginTransaction();
        try {
            $kriteria = new Kriteria;
            $kriteria->fill($request->all());
            $kriteria->save();
            DB::commit();
            return Redirect::route('kriteria')->with('notif-success', 'Kriteria berhasil di buat.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return Redirect::back()->with('notif-danger', 'Kriteria gagal di buat. silahkan cek formulir kembali atau hubungin admin.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriteria): View
    {
        $bobot = Bobot::select('nama', 'kode', 'nilai')->orderBy('id', 'asc')->get();
        $jenisKriteria = JenisKriteria::select('id', 'nama', 'keterangan')->orderBy('id', 'asc')->get();
        return view('criteria.edit', compact('kriteria', 'bobot', 'jenisKriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CriteriaRequest $request, Kriteria $kriteria): RedirectResponse|JsonResponse
    {
        if ($request->ajax()) {
            if ($errMsg = $request->validate()) {
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

            return response()->json([
                'code' => 200,
                'msg-key' => 'SUCCESS',
                'message' => 'success.'
            ]);
        }

        DB::beginTransaction();
        try {
            $kriteria->fill($request->all());
            $kriteria->save();
            DB::commit();
            return Redirect::route('kriteria')->with('notif-success', 'Kriteria berhasil di ubah.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return Redirect::back()->with('notif-danger', 'Kriteria gagal di ubah. silahkan cek formulir kembali atau hubungin admin.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriteria): JsonResponse
    {
        DB::beginTransaction();
        try {
            $kriteria->delete();
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg-key' => 'SUCCESS',
                'message' => 'success.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return response()->json([
                'code' => 400,
                'msg-key' => 'BAD-REQUEST',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
