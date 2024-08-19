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
        Schema::create('biaya_spp', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("id_tahun_ajaran");
            $table->bigInteger('nilai');
            $table->integer('bobot_minimal')->nullable();
            $table->integer('bobot_maksimal')->nullable();
            $table->timestamps();
            // $table->foreign('id_tahun_ajaran')->references('id')->on('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_spp');
    }
};
