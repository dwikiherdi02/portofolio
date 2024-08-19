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
        Schema::create('kriteria', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("id_jenis_kriteria");
            $table->char("nama", 100);
            $table->char("kode", 5)->unique();
            $table->smallInteger("bobot");
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('id_jenis_kriteria')->references('id')->on('jenis_kriteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
