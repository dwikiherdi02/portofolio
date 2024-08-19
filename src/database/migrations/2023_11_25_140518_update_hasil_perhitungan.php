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
        Schema::table('hasil_perhitungan', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('total_bobot');
            $table->float('total_preferensi')->default(0)->after('id_siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_perhitungan', function (Blueprint $table) {
            $table->dropColumn('total_preferensi');
            $table->float('total_bobot')->default(0)->after('id_siswa');
            $table->uuid("id")->first();
        });
    }
};
