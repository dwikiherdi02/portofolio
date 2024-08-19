<?php

namespace App\Http\Controllers;

use App\Http\Requests\TuitionRequest;
use App\Models\BiayaSPP;
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

class TuitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('tuition.index');
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
                    if ($tahunAjaran->biayaSPP->count() > 0) {
                        return 'Data sudah di entri';
                    } else {
                        return 'Data belum di entri';
                    }
                })
                ->addColumn('aksi', function(TahunAjaran $tahunAjaran) {
                    return view('tuition.datatables.action', compact('tahunAjaran'))->render();
                })
                ->rawColumns(['aksi'])
                ->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAjaran $tahunAjaran): View
    {
        $biayaSPP = BiayaSPP::where('id_tahun_ajaran', $tahunAjaran->id)
                        ->orderBy('id', 'asc')
                        ->get();
        return view('tuition.edit', compact('tahunAjaran', 'biayaSPP'));
    }

    public function exportPdf(TahunAjaran $tahunAjaran): Response {
        // dd($tahunAjaran);
        $biayaSPP = [];
        BiayaSPP::where('id_tahun_ajaran', $tahunAjaran->id)
                    ->orderBy('id', 'asc')
                    ->chunk(200, function(Collection $queries) use (&$biayaSPP) {
                        foreach ($queries as $query) {
                            $biayaSPP[] = $query;
                        }
                    });

        $pdf = Pdf::loadView('tuition.export', compact('tahunAjaran','biayaSPP'));
        $filename = "Biaya-SPP-Tahun-{$tahunAjaran->tahun}.pdf";
        return $pdf->download($filename);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TuitionRequest $request, TahunAjaran $tahunAjaran)
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
            // delete existing data
            BiayaSPP::where('id_tahun_ajaran', $tahunAjaran->id)->delete();
            foreach ($request->get('biaya') as $key => $val) {
                $biayaSPP = new BiayaSPP;
                $val['id_tahun_ajaran'] = $tahunAjaran->id;
                $biayaSPP->fill($val);
                $biayaSPP->save();
            }
            DB::commit();
            return Redirect::route('biaya_spp')->with('notif-success', 'Tahun ajaran berhasil di ubah.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            dd($e->getMessage());
            return Redirect::back()->with('notif-danger', 'Tahun ajaran gagal di ubah. silahkan cek formulir kembali atau hubungin admin.');
        }
    }
}
