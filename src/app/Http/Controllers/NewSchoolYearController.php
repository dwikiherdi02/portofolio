<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewSchoolYearRequest;
use App\Models\TahunAjaran;
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

class NewSchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('newschoolyear.index');
    }

    public function dataTables(Request $request, DataTables $dataTables): JsonResponse
    {
        $model = TahunAjaran::query();
        return $dataTables->eloquent($model)
                ->filter(function ($query) use ($request) {
                    if (!empty($request->get('search'))) {
                        $search = strtolower($request->get('search'));
                        $query->where(function($query) use ($search) {
                            $query->where(DB::raw('lower(tahun)'), 'like', '%'. $search .'%')
                                ->orWhere(DB::raw('lower(keterangan)'),'like', '%'. $search .'%');
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
                ->addColumn('keterangan', '{{ $keterangan }}')
                ->addColumn('aksi', function(TahunAjaran $tahunAjaran) {
                    return view('newschoolyear.datatables.action', compact('tahunAjaran'))->render();
                })
                ->rawColumns(['aksi'])
                ->toJson();
    }

    public function exportPdf(): Response {
        $tahunAjaran = [];
        TahunAjaran::chunk(200, function(Collection $queries) use (&$tahunAjaran) {
            foreach ($queries as $query) {
                $tahunAjaran[] = $query;
            }
        });
        $pdf = Pdf::loadView('newschoolyear.export', compact('tahunAjaran'));
        return $pdf->download('tahun-ajaran.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('newschoolyear.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewSchoolYearRequest $request): RedirectResponse|JsonResponse
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
            $tahunAjaran = new TahunAjaran;
            $tahunAjaran->fill($request->all());
            $tahunAjaran->save();
            DB::commit();
            return Redirect::route('tahun_ajaran')->with('notif-success', 'Tahun ajaran berhasil di buat.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return Redirect::back()->with('notif-danger', 'Tahun ajaran gagal di buat. silahkan cek formulir kembali atau hubungin admin.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAjaran $tahunAjaran): View
    {
        return view('newschoolyear.edit', compact('tahunAjaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NewSchoolYearRequest $request, TahunAjaran $tahunAjaran): RedirectResponse|JsonResponse
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
            $tahunAjaran->fill($request->all());
            $tahunAjaran->save();
            DB::commit();
            return Redirect::route('tahun_ajaran')->with('notif-success', 'Tahun ajaran berhasil di ubah.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return Redirect::back()->with('notif-danger', 'Tahun ajaran gagal di ubah. silahkan cek formulir kembali atau hubungin admin.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAjaran $tahunAjaran): JsonResponse
    {
        DB::beginTransaction();
        try {
            $tahunAjaran->delete();
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
