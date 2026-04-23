<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perpindahan extends Model
{
    use HasFactory;

    protected $table = 'perpindahans';

    protected $fillable = [
        'warga_id',
        'asal',
        'tujuan',
        'diusulkan_oleh',
        'status',
        'tindak_lanjut',
        'user_id',
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
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}
