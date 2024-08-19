<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $dataBobot = [
            ["id" => Str::orderedUuid(), "nama" => "Sangat Rendah", "kode" => "SR", "nilai" => 1],
            ["id" => Str::orderedUuid(), "nama" => "Rendah", "kode" => "R", "nilai" => 2],
            ["id" => Str::orderedUuid(), "nama" => "Cukup", "kode" => "C", "nilai" => 3],
            ["id" => Str::orderedUuid(), "nama" => "Tinggi", "kode" => "T", "nilai" => 4],
            ["id" => Str::orderedUuid(), "nama" => "Sangat Tinggi", "kode" => "ST", "nilai" => 5],
        ];
        DB::table('bobot')->insert($dataBobot);

        $dataJenisKriteria = [
            ["id" => Str::orderedUuid(), "nama" => "Keuntungan (Benefit)", "kode" => "b", "keterangan" => "Semakin tinggi/besar nilai bobot maka akan semakin baik", "created_at" => $now, "updated_at" => $now],
            ["id" => Str::orderedUuid(), "nama" => "Biaya (Cost)", "kode" => "c", "keterangan" => "Semakin rendah/kecil nilai bobot maka akan semakin baik", "created_at" => $now, "updated_at" => $now],
        ];
        DB::table('jenis_kriteria')->insert($dataJenisKriteria);
    }
}
