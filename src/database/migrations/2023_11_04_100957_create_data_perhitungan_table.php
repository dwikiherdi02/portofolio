<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_perhitungan', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("id_tahun_ajaran");
            $table->foreignUuid("id_siswa");
            $table->foreignUuid("id_kriteria");
            $table->char('kode_nilai_kriteria', 10);
            $table->smallInteger('bobot_nilai_kriteria');
            $table->timestamps();
            // $table->foreign('id_tahun_ajaran')->references('id')->on('tahun_ajaran');
            // $table->foreign('id_siswa')->references('id')->on('siswa');
            // $table->foreign('id_kriteria')->references('id')->on('kriteria');
            // $table->foreign('kode_nilai_kriteria')->references('kode')->on('nilai_kriteria');
            $table->index(['kode_nilai_kriteria']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_perhitungan');
    }
};
