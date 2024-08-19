<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BiayaSPP extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'biaya_spp';

    protected $fillable = [
        'id_tahun_ajaran',
        'nilai',
        'bobot_minimal',
        'bobot_maksimal',
    ];

    protected static function booted(): void
    {
        static::deleted(function (BiayaSPP $biayaSPP) {
            DataPerhitungan::where('id_tahun_ajaran', $biayaSPP->tahunAjaran->id)->delete();
            HasilPerhitungan::where('id_tahun_ajaran', $biayaSPP->tahunAjaran->id)->delete();
        });
    }

    public function tahunAjaran(): HasOne {
        return $this->hasOne(TahunAjaran::class,'id', 'id_tahun_ajaran');
    }
}
