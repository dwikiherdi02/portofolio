<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'siswa';

    protected static function booted(): void
    {
        static::deleted(function (Siswa $siswa) {
            DataPerhitungan::where('id_siswa', $siswa->id)->delete();
            HasilPerhitungan::where('id_siswa', $siswa->id)->delete();
        });
    }

    protected $fillable = [
        'id_tahun_ajaran',
        'nis',
        'nama',
    ];
}
