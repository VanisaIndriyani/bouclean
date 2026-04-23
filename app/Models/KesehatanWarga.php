<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KesehatanWarga extends Model
{
    use HasFactory;

    protected $table = 'kesehatan_wargas';

    protected $fillable = [
        'warga_id',
        'kek',
        'anemia',
        'haid_lebih_7_hari',
        'belum_imunisasi',
        'tbc_mangkir',
        'remaja_rokok',
        'ada_jentik',
        'tanggal_laporan',
    ];

    protected $casts = [
        'kek' => 'boolean',
        'anemia' => 'boolean',
        'haid_lebih_7_hari' => 'boolean',
        'belum_imunisasi' => 'boolean',
        'tbc_mangkir' => 'boolean',
        'remaja_rokok' => 'boolean',
        'ada_jentik' => 'boolean',
        'tanggal_laporan' => 'date',
    ];

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }
}
