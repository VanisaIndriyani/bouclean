<?php

namespace Database\Seeders;

use App\Models\IuranSampah;
use App\Models\PilahSampah;
use App\Models\User;
use App\Models\Warga;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Utama
        $admin = User::firstOrCreate(['email' => 'admin@bouclean.com'], [
            'name' => 'Admin Bouclear',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $user = User::firstOrCreate(['email' => 'user@bouclean.com'], [
            'name' => 'Petugas Bouclear',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 2. Data Wilayah
        for ($i = 1; $i <= 10; $i++) {
            Wilayah::create([
                'kecamatan' => 'Semarang Utara',
                'kelurahan' => 'Plombokan',
                'rt' => str_pad($i, 3, '0', STR_PAD_LEFT),
                'rw' => '002',
                'dasawisma' => 'Bougenville '.$i,
                'nama_pengguna' => 'Warga Plombokan '.$i,
            ]);
        }

        // 3. Data Warga & Statistik Bulanan (Januari - April)
        $bulanNama = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        for ($i = 1; $i <= 15; $i++) {
            $warga = Warga::create([
                'user_id' => $user->id,
                'nama_lengkap' => 'Warga '.$i.' Plombokan',
                'nik' => '337401'.str_pad($i, 10, '0', STR_PAD_LEFT),
                'jenis_kelamin' => ($i % 2 == 0) ? 'Laki-laki' : 'Perempuan',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1990-01-01',
                'kecamatan' => 'Semarang Utara',
                'kelurahan' => 'Plombokan',
                'rt' => '001',
                'rw' => '002',
                'dasawisma' => 'Bougenville 1',
            ]);

            // Data untuk grafik tahun 2026 (Januari - April)
            foreach ([1, 2, 3, 4] as $num) {
                $name = $bulanNama[$num];

                // Pilah Sampah (Berat variatif tiap bulan)
                $createdAt = Carbon::create(2026, $num, rand(1, 28));
                PilahSampah::create([
                    'warga_id' => $warga->id,
                    'user_id' => $user->id,
                    'jenis_kelamin' => $warga->jenis_kelamin,
                    'berat' => rand(1000, 8000), // 1kg - 8kg
                    'sedekah' => rand(0, 1),
                    'harga' => rand(5000, 20000),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // Iuran Sampah
                $bayarAt = Carbon::create(2026, $num, rand(1, 10));
                IuranSampah::create([
                    'warga_id' => $warga->id,
                    'user_id' => $user->id,
                    'bulan' => $name,
                    'tahun' => '2026',
                    'nominal' => 10000,
                    'status' => 'lunas',
                    'tanggal_bayar' => $bayarAt,
                    'petugas' => 'Petugas Plombokan',
                    'created_at' => $bayarAt,
                    'updated_at' => $bayarAt,
                ]);
            }

            // Data untuk grafik tahun 2025 (Januari - Desember)
            foreach ($bulanNama as $num => $name) {
                $createdAt = Carbon::create(2025, $num, rand(1, 28));
                PilahSampah::create([
                    'warga_id' => $warga->id,
                    'user_id' => $user->id,
                    'jenis_kelamin' => $warga->jenis_kelamin,
                    'berat' => rand(800, 7000),
                    'sedekah' => rand(0, 1),
                    'harga' => rand(5000, 20000),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $bayarAt = Carbon::create(2025, $num, rand(1, 10));
                IuranSampah::create([
                    'warga_id' => $warga->id,
                    'user_id' => $user->id,
                    'bulan' => $name,
                    'tahun' => '2025',
                    'nominal' => 10000,
                    'status' => 'lunas',
                    'tanggal_bayar' => $bayarAt,
                    'petugas' => 'Petugas Plombokan',
                    'created_at' => $bayarAt,
                    'updated_at' => $bayarAt,
                ]);
            }
        }
    }
}
