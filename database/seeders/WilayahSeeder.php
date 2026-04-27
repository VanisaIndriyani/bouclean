<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $wilayahRows = [
            [
                'kecamatan' => 'SEMARANG UTARA',
                'kelurahan' => 'PLOMBOKAN',
                'rw' => 'III',
                'rt' => '5',
                'dasawisma' => 'BOUGENVILLE 3',
            ],
            [
                'kecamatan' => 'SEMARANG UTARA',
                'kelurahan' => 'PLOMBOKAN',
                'rw' => 'III',
                'rt' => '5',
                'dasawisma' => 'BOUGENVILLE 2',
            ],
            [
                'kecamatan' => 'SEMARANG UTARA',
                'kelurahan' => 'PLOMBOKAN',
                'rw' => 'III',
                'rt' => '5',
                'dasawisma' => 'BOUGENVILLE 1',
            ],
            [
                'kecamatan' => 'SEMARANG UTARA',
                'kelurahan' => 'PLOMBOKAN',
                'rw' => 'III',
                'rt' => '5',
                'dasawisma' => '_DAHLIA 3',
            ],
            [
                'kecamatan' => 'SEMARANG UTARA',
                'kelurahan' => 'PLOMBOKAN',
                'rw' => 'III',
                'rt' => '5',
                'dasawisma' => '_DAHLIA 4',
            ],
            [
                'kecamatan' => 'SEMARANG UTARA',
                'kelurahan' => 'PLOMBOKAN',
                'rw' => 'III',
                'rt' => '5',
                'dasawisma' => '_DAHLIA1',
            ],
            [
                'kecamatan' => 'SEMARANG UTARA',
                'kelurahan' => 'PLOMBOKAN',
                'rw' => 'III',
                'rt' => '5',
                'dasawisma' => '_DAHLIA2',
            ],
        ];

        foreach ($wilayahRows as $row) {
            Wilayah::firstOrCreate(
                [
                    'kecamatan' => $row['kecamatan'],
                    'kelurahan' => $row['kelurahan'],
                    'rt' => $row['rt'],
                    'rw' => $row['rw'],
                    'dasawisma' => $row['dasawisma'],
                ],
                [
                    'nama_pengguna' => mb_strtoupper((string) $row['dasawisma']),
                ],
            );
        }

        if (! Schema::hasColumn('users', 'username') || ! Schema::hasColumn('users', 'dasawisma')) {
            return;
        }

        $username = 'semarang_utara_plombokan_iii_5__dahlia_3';

        User::updateOrCreate(
            ['username' => $username],
            [
                'name' => 'RW III RT 5 _DAHLIA 3',
                'dasawisma' => '_DAHLIA 3, _DAHLIA 4, _DAHLIA1, _DAHLIA2',
                'email' => $username.'@bouclear.invalid',
                'password' => Hash::make('password'),
                'role' => 'user',
                'last_login_at' => null,
            ],
        );

        User::query()
            ->whereIn('username', [
                'dahlia_3_rwiii_rt5',
                'dahlia_4_rwiii_rt5',
                'dahlia1_rwiii_rt5',
                'dahlia2_rwiii_rt5',
            ])
            ->delete();
    }
}
