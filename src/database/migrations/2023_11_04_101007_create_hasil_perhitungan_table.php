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
        Schema::create('hasil_perhitungan', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("id_tahun_ajaran");
            $table->foreignUuid("id_siswa");
            $table->float('total_bobot')->default(0);
            $table->bigInteger('biaya')->default(0);
            $table->timestamps();
            // $table->foreign('id_tahun_ajaran')->references('id')->on('tahun_ajaran');
            // $table->foreign('id_siswa')->references('id')->on('siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_perhitungan');
    }
};
