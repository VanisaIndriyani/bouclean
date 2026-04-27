<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE pilah_sampahs MODIFY kepala_keluarga_nik VARCHAR(255) NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN kepala_keluarga_nik TYPE VARCHAR(255)');
            return;
        }

        if ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN kepala_keluarga_nik VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE pilah_sampahs MODIFY kepala_keluarga_nik VARCHAR(16) NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN kepala_keluarga_nik TYPE VARCHAR(16)');
            return;
        }

        if ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN kepala_keluarga_nik VARCHAR(16) NULL');
        }
    }
};

