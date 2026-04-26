<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(['email' => 'admin@bouclear.com'], [
            'name' => 'Admin Bouclear',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $user = User::firstOrCreate(['email' => 'user@bouclear.com'], [
            'name' => 'Petugas Bouclear',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
