<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JenisKriteria extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'jenis_kriteria';

    protected $fillable = [
        'nama',
        'kode',
        'keterangan'
    ];
}
