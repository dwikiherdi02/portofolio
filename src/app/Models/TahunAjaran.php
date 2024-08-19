<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TahunAjaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        'keterangan',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleted(function (TahunAjaran $tahunAjaran) {
            // $tahunAjaran->biayaSPP->each->delete();
            // $tahunAjaran->siswa->each->delete();
            BiayaSPP::where('id_tahun_ajaran', $tahunAjaran->id)->delete();
            Siswa::where('id_tahun_ajaran', $tahunAjaran->id)->delete();
            DataPerhitungan::where('id_tahun_ajaran', $tahunAjaran->id)->delete();
            HasilPerhitungan::where('id_tahun_ajaran', $tahunAjaran->id)->delete();
        });
    }

    public function biayaSPP(): HasMany {
        return $this->hasMany(BiayaSPP::class, 'id_tahun_ajaran', 'id');
    }

    public function siswa(): HasMany {
        return $this->hasMany(Siswa::class,'id_tahun_ajaran', 'id');
    }

    public function dataPerhitungan(): HasMany {
        return $this->hasMany(DataPerhitungan::class,'id_tahun_ajaran', 'id');
    }

    public function hasilPerhitungan(): HasMany {
        return $this->hasMany(HasilPerhitungan::class,'id_tahun_ajaran', 'id');
    }
}
