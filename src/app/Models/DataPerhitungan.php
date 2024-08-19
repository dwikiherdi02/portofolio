<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DataPerhitungan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'data_perhitungan';

    protected $fillable = [
        'id_tahun_ajaran',
        'id_siswa',
        'id_kriteria',
        'id_nilai_kriteria',
        'bobot_nilai_kriteria',
        'bobot_nilai_maks',
        'bobot_nilai_min',
        'rumus_normalisasi',
        'nilai_normalisasi',
        'bobot_kriteria',
        'rumus_preferensi',
        'nilai_preferensi',
    ];

    public function siswa(): HasOne {
        return $this->hasOne(Siswa::class,'id', 'id_siswa');
    }

    public function kriteria(): HasOne {
        return $this->hasOne(Kriteria::class, 'id', 'id_kriteria');
    }

    public function nilaiKriteria(): HasOne {
        return $this->hasOne(NilaiKriteria::class, 'id', 'id_nilai_kriteria');
    }
}
