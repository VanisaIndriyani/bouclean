<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'wargas';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'kecamatan',
        'kelurahan',
        'rt',
        'rw',
        'dasawisma',
        'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function perpindahans(): HasMany
    {
        return $this->hasMany(Perpindahan::class);
    }

    public function pilahSampahs(): HasMany
    {
        return $this->hasMany(PilahSampah::class);
    }

    public function iuranSampahs(): HasMany
    {
        return $this->hasMany(IuranSampah::class);
    }
}
