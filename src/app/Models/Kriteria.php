<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kriteria extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'kriteria';

    protected $fillable = [
        'id_jenis_kriteria',
        'nama',
        'kode',
        'bobot',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (Kriteria $kriteria) {
            if (!isset($kriteria->kode) || empty($kriteria->kode)) {
                $count = $kriteria->withTrashed()->count() + 1;
                $kriteria->kode = "K{$count}";
            }
        });

        static::deleted(function (Kriteria $kriteria) {
            $kriteria->nilaiKriteria->each->delete();
        });
    }

    public function jenisKriteria(): HasOne {
        return $this->hasOne(JenisKriteria::class,'id','id_jenis_kriteria');
    }

    public function bobotByNilai(): HasOne {
        return $this->hasOne(Bobot::class, 'nilai', 'bobot');
    }

    public function nilaiKriteria(): HasMany {
        return $this->hasMany(NilaiKriteria::class, 'id_kriteria', 'id');
    }
}
