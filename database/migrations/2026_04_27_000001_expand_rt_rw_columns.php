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
            DB::statement("ALTER TABLE wargas MODIFY rt VARCHAR(10) NOT NULL");
            DB::statement("ALTER TABLE wargas MODIFY rw VARCHAR(10) NOT NULL");

            DB::statement("ALTER TABLE wilayahs MODIFY rt VARCHAR(10) NOT NULL");
            DB::statement("ALTER TABLE wilayahs MODIFY rw VARCHAR(10) NOT NULL");

            DB::statement("ALTER TABLE pilah_sampahs MODIFY rt VARCHAR(10) NULL");
            DB::statement("ALTER TABLE pilah_sampahs MODIFY rw VARCHAR(10) NULL");

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE wargas ALTER COLUMN rt TYPE VARCHAR(10)");
            DB::statement("ALTER TABLE wargas ALTER COLUMN rw TYPE VARCHAR(10)");

            DB::statement("ALTER TABLE wilayahs ALTER COLUMN rt TYPE VARCHAR(10)");
            DB::statement("ALTER TABLE wilayahs ALTER COLUMN rw TYPE VARCHAR(10)");

            DB::statement("ALTER TABLE pilah_sampahs ALTER COLUMN rt TYPE VARCHAR(10)");
            DB::statement("ALTER TABLE pilah_sampahs ALTER COLUMN rw TYPE VARCHAR(10)");

            return;
        }

        if ($driver === 'sqlsrv') {
            DB::statement("ALTER TABLE wargas ALTER COLUMN rt VARCHAR(10) NOT NULL");
            DB::statement("ALTER TABLE wargas ALTER COLUMN rw VARCHAR(10) NOT NULL");

            DB::statement("ALTER TABLE wilayahs ALTER COLUMN rt VARCHAR(10) NOT NULL");
            DB::statement("ALTER TABLE wilayahs ALTER COLUMN rw VARCHAR(10) NOT NULL");

            DB::statement("ALTER TABLE pilah_sampahs ALTER COLUMN rt VARCHAR(10) NULL");
            DB::statement("ALTER TABLE pilah_sampahs ALTER COLUMN rw VARCHAR(10) NULL");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE wargas MODIFY rt CHAR(3) NOT NULL");
            DB::statement("ALTER TABLE wargas MODIFY rw CHAR(3) NOT NULL");

            DB::statement("ALTER TABLE wilayahs MODIFY rt CHAR(3) NOT NULL");
            DB::statement("ALTER TABLE wilayahs MODIFY rw CHAR(3) NOT NULL");

            DB::statement("ALTER TABLE pilah_sampahs MODIFY rt CHAR(3) NULL");
            DB::statement("ALTER TABLE pilah_sampahs MODIFY rw CHAR(3) NULL");

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE wargas ALTER COLUMN rt TYPE CHAR(3)");
            DB::statement("ALTER TABLE wargas ALTER COLUMN rw TYPE CHAR(3)");

            DB::statement("ALTER TABLE wilayahs ALTER COLUMN rt TYPE CHAR(3)");
            DB::statement("ALTER TABLE wilayahs ALTER COLUMN rw TYPE CHAR(3)");

            DB::statement("ALTER TABLE pilah_sampahs ALTER COLUMN rt TYPE CHAR(3)");
            DB::statement("ALTER TABLE pilah_sampahs ALTER COLUMN rw TYPE CHAR(3)");

            return;
        }

        if ($driver === 'sqlsrv') {
            DB::statement("ALTER TABLE wargas ALTER COLUMN rt CHAR(3) NOT NULL");
            DB::statement("ALTER TABLE wargas ALTER COLUMN rw CHAR(3) NOT NULL");

            DB::statement("ALTER TABLE wilayahs ALTER COLUMN rt CHAR(3) NOT NULL");
            DB::statement("ALTER TABLE wilayahs ALTER COLUMN rw CHAR(3) NOT NULL");

            DB::statement("ALTER TABLE pilah_sampahs ALTER COLUMN rt CHAR(3) NULL");
            DB::statement("ALTER TABLE pilah_sampahs ALTER COLUMN rw CHAR(3) NULL");
        }
    }
};

