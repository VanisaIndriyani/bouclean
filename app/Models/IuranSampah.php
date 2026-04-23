<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IuranSampah extends Model
{
    use HasFactory;

    protected $table = 'iuran_sampahs';

    protected $fillable = [
        'warga_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
        'tanggal_bayar',
        'petugas',
        'user_id',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status === 'lunas'
            ? '<span class="badge bg-success">Lunas</span>'
            : '<span class="badge bg-danger">Belum</span>';
    }
}
