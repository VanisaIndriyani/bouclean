<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pilah_sampahs', function (Blueprint $table) {
            $table->unsignedTinyInteger('bulan')->nullable()->after('kepala_keluarga_nik');
            $table->unsignedSmallInteger('tahun')->nullable()->after('bulan');
        });

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement("UPDATE pilah_sampahs SET bulan = CAST(strftime('%m', created_at) AS INTEGER), tahun = CAST(strftime('%Y', created_at) AS INTEGER) WHERE bulan IS NULL OR tahun IS NULL");
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('UPDATE pilah_sampahs SET bulan = MONTH(created_at), tahun = YEAR(created_at) WHERE bulan IS NULL OR tahun IS NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('UPDATE pilah_sampahs SET bulan = EXTRACT(MONTH FROM created_at)::int, tahun = EXTRACT(YEAR FROM created_at)::int WHERE bulan IS NULL OR tahun IS NULL');
            return;
        }

        if ($driver === 'sqlsrv') {
            DB::statement('UPDATE pilah_sampahs SET bulan = MONTH(created_at), tahun = YEAR(created_at) WHERE bulan IS NULL OR tahun IS NULL');
        }
    }

    public function down(): void
    {
        Schema::table('pilah_sampahs', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
        });
    }
};

