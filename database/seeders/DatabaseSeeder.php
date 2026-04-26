<?php

namespace Database\Seeders;

use App\Models\IuranSampah;
use App\Models\PilahSampah;
use App\Models\User;
use App\Models\Warga;
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

        $admin = User::query()->where('email', 'admin@bouclear.com')->first() ?? User::query()->first();
        if (! $admin) {
            return;
        }

        if (Warga::query()->count() === 0) {
            Warga::factory(10)->state(['user_id' => $admin->id])->create();
        }

        $wargas = Warga::query()->get(['id']);
        if ($wargas->isEmpty()) {
            return;
        }

        $tahun = (int) now()->format('Y');
        $bulanList = ['Januari', 'Februari', 'Maret', 'April'];

        foreach ($wargas as $warga) {
            foreach ($bulanList as $bulan) {
                $tanggalBayar = match ($bulan) {
                    'Januari' => now()->setDate($tahun, 1, 5)->startOfDay(),
                    'Februari' => now()->setDate($tahun, 2, 5)->startOfDay(),
                    'Maret' => now()->setDate($tahun, 3, 5)->startOfDay(),
                    'April' => now()->setDate($tahun, 4, 5)->startOfDay(),
                    default => now()->startOfDay(),
                };

                IuranSampah::query()->updateOrCreate(
                    [
                        'warga_id' => $warga->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ],
                    [
                        'nominal' => 10000,
                        'status' => 'lunas',
                        'tanggal_bayar' => $tanggalBayar,
                        'petugas' => $admin->name,
                        'user_id' => $admin->id,
                    ],
                );
            }
        }

        if (PilahSampah::query()->whereYear('created_at', $tahun)->count() === 0) {
            $jenisSampahList = ['Plastik', 'Kertas', 'Logam', 'Kaca', 'Organik', 'Campuran'];
            $bulanToMonth = [
                'Januari' => 1,
                'Februari' => 2,
                'Maret' => 3,
                'April' => 4,
            ];

            foreach ($wargas as $warga) {
                $w = Warga::query()->find($warga->id);
                if (! $w) {
                    continue;
                }

                foreach ($bulanList as $bulan) {
                    $month = $bulanToMonth[$bulan] ?? 1;
                    $date = now()->setDate($tahun, $month, 10)->startOfDay();

                    $berat = random_int(3000, 8000);
                    $sedekah = (bool) random_int(0, 1);
                    $harga = $berat * random_int(2, 6);

                    $pilah = PilahSampah::query()->create([
                        'warga_id' => $w->id,
                        'kecamatan' => $w->kecamatan,
                        'kelurahan' => $w->kelurahan,
                        'rt' => $w->rt,
                        'rw' => $w->rw,
                        'dasawisma' => $w->dasawisma,
                        'jenis_sampah' => $jenisSampahList[array_rand($jenisSampahList)],
                        'jenis_kelamin' => $w->jenis_kelamin,
                        'berat' => $berat,
                        'sedekah' => $sedekah,
                        'harga' => $harga,
                        'foto' => null,
                        'user_id' => $admin->id,
                    ]);

                    $pilah->forceFill([
                        'created_at' => $date,
                        'updated_at' => $date,
                    ])->saveQuietly();
                }
            }
        }
    }
}
