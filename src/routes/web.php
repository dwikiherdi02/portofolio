<?php

use App\Http\Controllers\CalcTuitionCostsController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\CriteriaOptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewSchoolYearController;
use App\Http\Controllers\ResultCalcTuitionCostsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TuitionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/dasbor');
});

/** Dasbor **/
Route::get('/dasbor', [DashboardController::class, 'index'])->name('dasbor');

/** Kriteria **/
Route::prefix('kriteria')->group(function () {
    Route::get('/', [CriteriaController::class, 'index'])->name('kriteria');
    Route::get('/data-tabel', [CriteriaController::class, 'dataTables'])->name('kriteria.datatabel');
    Route::get('/unduh-pdf', [CriteriaController::class, 'exportPdf'])->name('kriteria.unduh_pdf');
    Route::get('/tambah', [CriteriaController::class, 'create'])->name('kriteria.tambah');
    Route::post('/tambah', [CriteriaController::class, 'store'])->name('kriteria.proses_tambah');
    Route::get('/{kriteria}', [CriteriaController::class, 'edit'])->name('kriteria.ubah');
    Route::put('/{kriteria}', [CriteriaController::class, 'update'])->name('kriteria.proses_ubah');
    Route::delete('/{kriteria}', [CriteriaController::class, 'destroy'])->name('kriteria.hapus');

    Route::prefix('{kriteria}/opsi')->group(function () {
        Route::get('/', [CriteriaOptionController::class, 'index'])->name('kriteria.opsi');
        Route::get('/data-tabel', [CriteriaOptionController::class, 'dataTables'])->name('kriteria.opsi.datatabel');
        Route::get('/tambah', [CriteriaOptionController::class, 'create'])->name('kriteria.opsi.tambah');
        Route::post('/tambah', [CriteriaOptionController::class, 'store'])->name('kriteria.opsi.proses_tambah');
        Route::get('/{nilaiKriteria}', [CriteriaOptionController::class, 'edit'])->name('kriteria.opsi.ubah');
        Route::put('/{nilaiKriteria}', [CriteriaOptionController::class, 'update'])->name('kriteria.opsi.proses_ubah');
        Route::delete('/{nilaiKriteria}', [CriteriaOptionController::class, 'destroy'])->name('kriteria.opsi.hapus');
    });
});

/** Tahun Ajaran **/
Route::prefix('tahun-ajaran')->group(function () {
    Route::get('/', [NewSchoolYearController::class, 'index'])->name('tahun_ajaran');
    Route::get('/data-tabel', [NewSchoolYearController::class, 'dataTables'])->name('tahun_ajaran.datatabel');
    Route::get('/unduh-pdf', [NewSchoolYearController::class, 'exportPdf'])->name('tahun_ajaran.unduh_pdf');
    Route::get('/tambah', [NewSchoolYearController::class, 'create'])->name('tahun_ajaran.tambah');
    Route::post('/tambah', [NewSchoolYearController::class, 'store'])->name('tahun_ajaran.proses_tambah');
    Route::get('/{tahunAjaran}', [NewSchoolYearController::class, 'edit'])->name('tahun_ajaran.ubah');
    Route::put('/{tahunAjaran}', [NewSchoolYearController::class, 'update'])->name('tahun_ajaran.proses_ubah');
    Route::delete('/{tahunAjaran}', [NewSchoolYearController::class, 'destroy'])->name('tahun_ajaran.hapus');
});

/** Biaya SPP **/
Route::prefix('biaya-spp')->group(function () {
    Route::get('/', [TuitionController::class, 'index'])->name('biaya_spp');
    Route::get('/data-tabel', [TuitionController::class, 'dataTables'])->name('biaya_spp.datatabel');
    Route::get('/{tahunAjaran}', [TuitionController::class, 'edit'])->name('biaya_spp.data_entri');
    Route::get('/{tahunAjaran}/unduh-pdf', [TuitionController::class, 'exportPdf'])->name('biaya_spp.data_entri.unduh_pdf');
    Route::post('/{tahunAjaran}', [TuitionController::class, 'update'])->name('biaya_spp.proses_data_entri');
});

/** Data Siswa **/
Route::prefix('data-siswa')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('data_siswa');
    Route::get('/data-tabel', [StudentController::class, 'dataTables'])->name('data_siswa.datatabel');
    Route::get('/unduh-template', [StudentController::class, 'downloadStudentTemplate'])->name('data_siswa.unduh_template');
    Route::get('/{tahunAjaran}', [StudentController::class, 'show'])->name('data_siswa.data_entri');
    Route::get('/{tahunAjaran}/data-table', [StudentController::class, 'showDataTables'])->name('data_siswa.data_entri.datatabel');
    Route::get('/{tahunAjaran}/unduh-pdf', [StudentController::class, 'exportPdf'])->name('data_siswa.data_entri.unduh_pdf');
    Route::post('/{tahunAjaran}/proses-pengambilan-hasil-data', [StudentController::class, 'processFetchingExcelData'])->name('data_siswa.data_entri.proses_ambil_data');
    Route::post('/{tahunAjaran}/proses-menyimpan-data', [StudentController::class, 'processStoringData'])->name('data_siswa.data_entri.proses_simpan_data');
    Route::delete('/{tahunAjaran}/proses-hapus-data', [StudentController::class, 'showDestroy'])->name('data_siswa.data_entri.proses_hapus_data');
});

/** Proses Penentuan Hitung & Hasil Biaya SPP **/
Route::prefix('proses-penentuan')->group(function () {
    /** Proses Hitung SPP **/
    Route::prefix('hitung')->group(function () {
        Route::get('/', [CalcTuitionCostsController::class, 'index'])->name('hitung_biaya_spp');
        Route::get('/unduh-berkas', [CalcTuitionCostsController::class, 'downladFile'])->name('hitung_biaya_spp.unduh_berkas');
        Route::post('/proses-cek', [CalcTuitionCostsController::class, 'storeCheck'])->name('hitung_biaya_spp.proses_cek');
        Route::post('/proses-impor', [CalcTuitionCostsController::class, 'storeImport'])->name('hitung_biaya_spp.proses_impor');
        Route::post('/proses-hitung', [CalcTuitionCostsController::class, 'storeCalculate'])->name('hitung_biaya_spp.proses_hitung');
        Route::delete('/proses-hapus', [CalcTuitionCostsController::class, 'destroyByNewSchoolYear'])->name('hitung_biaya_spp.proses_hapus');
    });


    /** Hasil Hitung SPP **/
    Route::prefix('hasil')->group(function () {
        Route::get('/', [ResultCalcTuitionCostsController::class, 'index'])->name('hasil_biaya_spp');
        Route::get('/data-tabel', [ResultCalcTuitionCostsController::class, 'dataTables'])->name('hasil_biaya_spp.datatabel');
        Route::get('/{tahunAjaran}', [ResultCalcTuitionCostsController::class, 'show'])->name('hasil_biaya_spp.lihat');
        Route::get('/{tahunAjaran}/data-tabel/detail', [ResultCalcTuitionCostsController::class, 'dataTablesDetail'])->name('hasil_biaya_spp.lihat.datatabel.detail');
        Route::get('/{tahunAjaran}/data-tabel/calc/criteria-student', [ResultCalcTuitionCostsController::class, 'dataTablesCalcCriteriaStudent'])->name('hasil_biaya_spp.lihat.datatabel.calc.students');
        Route::get('/{tahunAjaran}/data-tabel/calc/criteria-weight', [ResultCalcTuitionCostsController::class, 'dataTablesCalcCriteriaWeight'])->name('hasil_biaya_spp.lihat.datatabel.calc.weight');
        Route::get('/{tahunAjaran}/data-tabel/calc/criteria-value', [ResultCalcTuitionCostsController::class, 'dataTablesCalcCriteriaValue'])->name('hasil_biaya_spp.lihat.datatabel.calc.value');
        Route::get('/{tahunAjaran}/data-tabel/calc/criteria-result', [ResultCalcTuitionCostsController::class, 'dataTablesCalcCriteriaResult'])->name('hasil_biaya_spp.lihat.datatabel.calc.result');
        Route::get('/{tahunAjaran}/data-tabel/calc/preference-weigth', [ResultCalcTuitionCostsController::class, 'dataTablesCalcPreferenceWeight'])->name('hasil_biaya_spp.lihat.datatabel.calc.preference.weight');
        Route::get('/{tahunAjaran}/data-tabel/calc/preference-result', [ResultCalcTuitionCostsController::class, 'dataTablesCalcPreferenceResult'])->name('hasil_biaya_spp.lihat.datatabel.calc.preference.result');
        Route::get('/{tahunAjaran}/data-tabel/result', [ResultCalcTuitionCostsController::class, 'dataTablesResult'])->name('hasil_biaya_spp.lihat.datatabel.result');
        Route::get('/{tahunAjaran}/unduh-pdf', [ResultCalcTuitionCostsController::class, 'exportPdf'])->name('hasil_biaya_spp.lihat.unduh_pdf');
    });
});
