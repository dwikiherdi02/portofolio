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
            $table->dropColumn('id');
            $table->smallInteger('bobot_nilai_maks')->after('bobot_nilai_kriteria');
            $table->smallInteger('bobot_nilai_min')->after('bobot_nilai_maks');
            $table->text('rumus_normalisasi')->after('bobot_nilai_min');
            $table->float('nilai_normalisasi')->default(0)->after('rumus_normalisasi');
            $table->smallInteger('bobot_kriteria')->after('nilai_normalisasi');
            $table->text('rumus_preferensi')->after('bobot_kriteria');
            $table->float('nilai_preferensi')->default(0)->after('rumus_preferensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_perhitungan', function (Blueprint $table) {
            $table->dropColumn('bobot_nilai_maks');
            $table->dropColumn('bobot_nilai_min');
            $table->dropColumn('rumus_normalisasi');
            $table->dropColumn('nilai_normalisasi');
            $table->dropColumn('bobot_kriteria');
            $table->dropColumn('rumus_preferensi');
            $table->dropColumn('nilai_preferensi');
            $table->uuid("id")->first();
        });
    }
};
