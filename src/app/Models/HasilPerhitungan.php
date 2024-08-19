<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HasilPerhitungan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'hasil_perhitungan';

    protected $fillable = [
        'id_tahun_ajaran',
        'id_siswa',
        'total_preferensi',
        'biaya',
    ];

    protected $casts = [
        'id_tahun_ajaran' => 'string',
        'id_siswa' => 'string',
        'total_preferensi' => 'double',
        'biaya' => 'integer',
    ];

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'id', 'id_siswa');
    }
}
