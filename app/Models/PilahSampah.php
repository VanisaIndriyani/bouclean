<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PilahSampah extends Model
{
    use HasFactory;

    protected $table = 'pilah_sampahs';

    protected $fillable = [
        'warga_id',
        'kecamatan',
        'kelurahan',
        'rt',
        'rw',
        'dasawisma',
        'jenis_sampah',
        'jenis_kelamin',
        'berat',
        'sedekah',
        'harga',
        'foto',
        'user_id',
    ];

    protected $casts = [
        'berat' => 'decimal:2',
        'harga' => 'decimal:2',
        'sedekah' => 'boolean',
    ];

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSedekahBadgeAttribute()
    {
        return $this->sedekah
            ? '<span class="badge bg-success">Ya</span>'
            : '<span class="badge bg-secondary">Tidak</span>';
    }

    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/'.$this->foto) : null;
    }
}
