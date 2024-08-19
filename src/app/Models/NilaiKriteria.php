<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class NilaiKriteria extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'nilai_kriteria';

    protected $fillable = [
        'id_kriteria',
        'kode',
        'keterangan',
        'bobot',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (NilaiKriteria $nilaiKriteria) {
            if (!isset($nilaiKriteria->kode) || empty($nilaiKriteria->kode)) {
                $kriteria = $nilaiKriteria->kriteria;
                $count = $nilaiKriteria->where('id_kriteria', $kriteria->id)->withTrashed()->count() + 1;
                $nilaiKriteria->kode = "{$kriteria->kode}{$count}";
                unset($nilaiKriteria->kriteria);
            }
        });
    }

    public function kriteria(): HasOne {
        return $this->hasOne(Kriteria::class,'id','id_kriteria');
    }

    public function bobotByNilai(): HasOne {
        return $this->hasOne(Bobot::class, 'nilai', 'bobot');
    }
}
