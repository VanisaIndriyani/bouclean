<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayahs';

    protected $fillable = [
        'kecamatan',
        'kelurahan',
        'rt',
        'rw',
        'dasawisma',
        'nama_pengguna',
    ];

    public function getFullWilayahAttribute()
    {
        return "{$this->kelurahan}, RT {$this->rt}/RW {$this->rw}, {$this->kecamatan}";
    }
}
