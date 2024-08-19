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
        Schema::create('nilai_kriteria', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("id_kriteria");
            $table->char("kode", 10)->unique();
            $table->text('keterangan')->nullable();
            $table->smallInteger("bobot");
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('id_kriteria')->references('id')->on('kriteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_kriteria');
    }
};
