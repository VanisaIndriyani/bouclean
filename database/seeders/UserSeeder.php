<?php

namespace Database\Seeders;

use App\Models\IuranSampah;
use App\Models\KesehatanWarga;
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
    }
}

       