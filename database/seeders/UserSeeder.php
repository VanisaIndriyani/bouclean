<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

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

        if (Schema::hasColumn('users', 'username')) {
            if (! $admin->username) {
                $admin->username = 'admin';
            }
            if (! $user->username) {
                $user->username = 'user';
            }
            $admin->save();
            $user->save();
        }
    }
}
