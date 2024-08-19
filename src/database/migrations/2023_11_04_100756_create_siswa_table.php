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
        Schema::create('siswa', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("id_tahun_ajaran");
            $table->char('nis', 10);
            $table->char('nama', 255);
            $table->timestamps();
            // $table->foreign('id_tahun_ajaran')->references('id')->on('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
