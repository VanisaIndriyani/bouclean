<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\IuranSampah;
use App\Models\Perpindahan;
use App\Models\User;
use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        if (ContactMessage::query()->count() === 0) {
            $rows = [
                ['nama_lengkap' => 'Siti Aminah', 'pesan' => 'Halo admin, saya ingin daftar jadi warga. Prosedurnya bagaimana?', 'is_read' => false],
                ['nama_lengkap' => 'Budi Santoso', 'pesan' => 'Saya mau tanya jadwal setor sampah untuk wilayah saya kapan ya?', 'is_read' => false],
                ['nama_lengkap' => 'Rina Wulandari', 'pesan' => 'Akun saya tidak bisa login, mohon dibantu reset.', 'is_read' => false],
                ['nama_lengkap' => 'Agus Pratama', 'pesan' => 'Kalau setor sampah, jenis plastik campur bisa dihitung satu jenis atau dipisah?', 'is_read' => false],
                ['nama_lengkap' => 'Dewi Lestari', 'pesan' => 'Saya ingin lapor ada kesalahan data RT/RW di profil warga.', 'is_read' => false],
            ];

            foreach ($rows as $row) {
                ContactMessage::query()->create($row);
            }
        }

        $admin = User::query()->where('email', 'admin@bouclear.com')->first();
        $adminId = $admin?->id ?? 1;

        if (Wilayah::query()->count() === 0) {
            $wilayahRows = [
                [
                    'kecamatan' => 'Semarang Utara',
                    'kelurahan' => 'Plombokan',
                    'rt' => '005',
                    'rw' => '003',
                    'dasawisma' => 'Dahlia 4',
                    'nama_pengguna' => 'RW III RT 5 _DAHLIA 4',
                ],
                [
                    'kecamatan' => 'Semarang Utara',
                    'kelurahan' => 'Plombokan',
                    'rt' => '001',
                    'rw' => '002',
                    'dasawisma' => 'Bougenville 1',
                    'nama_pengguna' => 'BOUGENVILLE 1',
                ],
                [
                    'kecamatan' => 'Semarang Utara',
                    'kelurahan' => 'Plombokan',
                    'rt' => '002',
                    'rw' => '002',
                    'dasawisma' => 'Bougenville 2',
                    'nama_pengguna' => 'BOUGENVILLE 2',
                ],
                [
                    'kecamatan' => 'Semarang Utara',
                    'kelurahan' => 'Plombokan',
                    'rt' => '003',
                    'rw' => '002',
                    'dasawisma' => 'Bougenville 3',
                    'nama_pengguna' => 'BOUGENVILLE 3',
                ],
                [
                    'kecamatan' => 'Semarang Utara',
                    'kelurahan' => 'Plombokan',
                    'rt' => '004',
                    'rw' => '002',
                    'dasawisma' => 'Bougenville 4',
                    'nama_pengguna' => 'BOUGENVILLE 4',
                ],
            ];

            foreach ($wilayahRows as $row) {
                Wilayah::query()->create($row);

                $warga = Warga::query()
                    ->where('kecamatan', $row['kecamatan'])
                    ->where('kelurahan', $row['kelurahan'])
                    ->where('rt', $row['rt'])
                    ->where('rw', $row['rw'])
                    ->where('dasawisma', $row['dasawisma'])
                    ->whereNotNull('account_user_id')
                    ->first();

                if (! $warga) {
                    $wargaUser = User::factory()->create([
                        'name' => 'Warga '.$row['dasawisma'],
                        'email' => strtolower(str_replace(' ', '_', $row['dasawisma'])).'_'.$row['rt'].$row['rw'].'@example.com',
                        'role' => 'warga',
                        'last_login_at' => now(),
                    ]);

                    $warga = Warga::factory()->state([
                        'user_id' => $adminId,
                        'kecamatan' => $row['kecamatan'],
                        'kelurahan' => $row['kelurahan'],
                        'rt' => $row['rt'],
                        'rw' => $row['rw'],
                        'dasawisma' => $row['dasawisma'],
                        'account_user_id' => $wargaUser->id,
                    ])->create();
                }
            }
        }

        if (Warga::query()->count() < 10) {
            $need = 10 - Warga::query()->count();
            Warga::factory($need)->state(['user_id' => $adminId])->create();
        }

        if (Perpindahan::query()->where('status', 'pending')->count() === 0) {
            $wargas = Warga::query()->limit(5)->get(['id', 'nama_lengkap']);
            foreach ($wargas as $w) {
                Perpindahan::query()->create([
                    'warga_id' => $w->id,
                    'asal' => 'Alamat lama '.$w->nama_lengkap,
                    'tujuan' => 'Alamat baru '.$w->nama_lengkap,
                    'diusulkan_oleh' => $w->nama_lengkap,
                    'status' => 'pending',
                    'tindak_lanjut' => null,
                    'user_id' => $adminId,
                ]);
            }
        }

        if (IuranSampah::query()->where('status', 'belum')->count() === 0) {
            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $tahun = (int) now()->format('Y');
            $wargas = Warga::query()->limit(5)->get(['id', 'nama_lengkap']);

            foreach ($wargas as $idx => $w) {
                IuranSampah::query()->create([
                    'warga_id' => $w->id,
                    'bulan' => $bulan[$idx % count($bulan)],
                    'tahun' => $tahun,
                    'nominal' => 10000,
                    'status' => 'belum',
                    'tanggal_bayar' => null,
                    'petugas' => null,
                    'user_id' => $adminId,
                ]);
            }
        }
    }
}
