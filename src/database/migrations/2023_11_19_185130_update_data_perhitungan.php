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
        Schema::table('data_perhitungan', function (Blueprint $table) {
            $table->dropColumn('kode_nilai_kriteria');
            $table->foreignUuid("id_nilai_kriteria")->after('id_kriteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_perhitungan', function (Blueprint $table) {
            $table->dropColumn('id_nilai_kriteria');
            $table->char('kode_nilai_kriteria', 10)->after('id_kriteria');
            $table->index(['kode_nilai_kriteria']);
        });
    }
};
