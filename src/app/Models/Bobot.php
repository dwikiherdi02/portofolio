<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Bobot extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'bobot';

    protected $fillable = [
        'nama',
        'kode',
        'nilai',
    ];
}
