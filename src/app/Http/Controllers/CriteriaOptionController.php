<?php

namespace App\Http\Controllers;

use App\Http\Requests\CriteriaOptionRequest;
use App\Models\Bobot;
use App\Models\Kriteria;
use App\Models\NilaiKriteria;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;

class CriteriaOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Kriteria $kriteria): View
    {
        return view('criteria.options.index', compact('kriteria'));
    }

    public function dataTables(Kriteria $kriteria, Request $request, DataTables $dataTables): JsonResponse
    {
        $model = NilaiKriteria::where('id_kriteria', $kriteria->id);
        return $dataTables->eloquent($model)
                ->filter(function ($query) use ($request) {
                    if (!empty($request->get('search'))) {
                        $search = strtolower($request->get('search'));
                        $query->where(function($query) use ($search) {
                            $query->where(DB::raw('lower(keterangan)'), 'like', '%'. $search .'%')
                                ->orWhere(DB::raw('lower(kode)'),'like', '%'. $search .'%')
                                ->orWhere(DB::raw('lower(bobot)'),'like', '%'. $search .'%')
                                ->orWhereHas('bobotByNilai', function (Builder $qhas) use ($search) {
                                    $qhas->where(DB::raw('lower(nama)'), 'like', '%'. $search .'%');
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
                ->addColumn('keterangan', '{{ $keterangan }}')
                ->addColumn('kode', '{{ $kode }}')
                ->addColumn('bobot', '{{ $bobot }}')
                ->addColumn('aksi', function(NilaiKriteria $nilaiKriteria) use($kriteria) {
                    return view('criteria.options.datatables.action', compact('kriteria','nilaiKriteria'))->render();
                })
                ->rawColumns(['keterangan','aksi'])
                ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Kriteria $kriteria): View
    {
        $bobot = Bobot::select('nama', 'kode', 'nilai')->orderBy('id', 'asc')->get();
        return view('criteria.options.create', compact('kriteria','bobot'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Kriteria $kriteria, CriteriaOptionRequest $request): RedirectResponse|JsonResponse
    {
        if ($request->ajax()) {
            if($errMsg = $request->validate()) {
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
            $nilaiKriteria = new NilaiKriteria;
            $nilaiKriteria->id_kriteria = $kriteria->id;
            $nilaiKriteria->fill($request->all());
            $nilaiKriteria->save();
            DB::commit();
            return Redirect::route('kriteria.opsi', $kriteria->id)->with('notif-success', 'Opsi kriteria berhasil di buat.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return Redirect::back()->with('notif-danger', 'Opsi kriteria gagal di buat. silahkan cek formulir kembali atau hubungin admin.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriteria, NilaiKriteria $nilaiKriteria): View
    {
        $bobot = Bobot::select('nama', 'kode', 'nilai')->orderBy('id', 'asc')->get();
        return view('criteria.options.edit', compact('kriteria','nilaiKriteria','bobot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Kriteria $kriteria, CriteriaOptionRequest $request, NilaiKriteria $nilaiKriteria): RedirectResponse|JsonResponse
    {
        if ($request->ajax()) {
            if($errMsg = $request->validate()) {
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
            $nilaiKriteria->fill($request->all());
            $nilaiKriteria->save();
            DB::commit();
            return Redirect::route('kriteria.opsi', $kriteria->id)->with('notif-success', 'Opsi kriteria berhasil di ubah.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return Redirect::back()->with('notif-danger', 'Opsi kriteria gagal di ubah. silahkan cek formulir kembali atau hubungin admin.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriteria, NilaiKriteria $nilaiKriteria): JsonResponse
    {
        DB::beginTransaction();
        try {
            $nilaiKriteria->delete();
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
