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
        'status_dalam_keluarga',
        'no_kk',
        'no_register_pkk',
        'agama',
        'status_perkawinan',
        'alamat',
        'pendidikan',
        'pekerjaan',
        'status_tinggal',
        'merantau_ke',
        'perantau_dari',
        'akseptor_kb',
        'aktif_posyandu',
        'bina_keluarga_balita',
        'memiliki_tabungan',
        'mengikuti_kelompok_belajar',
        'jenis_kelompok_belajar',
        'ikut_kegiatan_operasional',
        'jenis_operasi',
        'mengikuti_paud',
        'berkebutuhan_khusus',
        'buta',
        'hamil',
        'menyusui',
        'status',
        'ajukan_perpindahan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'akseptor_kb' => 'boolean',
        'aktif_posyandu' => 'boolean',
        'bina_keluarga_balita' => 'boolean',
        'memiliki_tabungan' => 'boolean',
        'mengikuti_kelompok_belajar' => 'boolean',
        'ikut_kegiatan_operasional' => 'boolean',
        'mengikuti_paud' => 'boolean',
        'berkebutuhan_khusus' => 'boolean',
        'buta' => 'boolean',
        'hamil' => 'boolean',
        'menyusui' => 'boolean',
    ];

    public function getNikMaskedAttribute(): string
    {
        $nik = preg_replace('/\D+/', '', (string) ($this->attributes['nik'] ?? ''));

        if (strlen($nik) === 16) {
            return substr($nik, 0, 4).'*'.substr($nik, 5, 2).'*'.substr($nik, 8, 3).'*'.substr($nik, 12, 4);
        }

        if ($nik === '') {
            return '-';
        }

        if (strlen($nik) <= 8) {
            return str_repeat('*', strlen($nik));
        }

        return substr($nik, 0, 4).str_repeat('*', max(0, strlen($nik) - 8)).substr($nik, -4);
    }

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

    public function kesehatanWargas(): HasMany
    {
        return $this->hasMany(KesehatanWarga::class);
    }

    public function iuranSampahs(): HasMany
    {
        return $this->hasMany(IuranSampah::class);
    }
}
