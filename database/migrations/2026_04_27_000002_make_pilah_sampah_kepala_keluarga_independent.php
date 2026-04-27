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
            $table->string('kepala_keluarga_nama')->nullable()->after('id');
            $table->string('kepala_keluarga_nik', 16)->nullable()->after('kepala_keluarga_nama');
        });

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');

            Schema::create('pilah_sampahs_tmp', function (Blueprint $table) {
                $table->id();
                $table->string('kepala_keluarga_nama')->nullable();
                $table->string('kepala_keluarga_nik', 16)->nullable();
                $table->unsignedBigInteger('warga_id')->nullable();
                $table->string('kecamatan')->nullable();
                $table->string('kelurahan')->nullable();
                $table->string('rt')->nullable();
                $table->string('rw')->nullable();
                $table->string('dasawisma')->nullable();
                $table->string('jenis_sampah')->nullable();
                $table->string('jenis_kelamin')->nullable();
                $table->decimal('berat', 10, 2);
                $table->boolean('sedekah')->default(false);
                $table->decimal('harga', 12, 2);
                $table->string('foto')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
            });

            DB::statement(
                'INSERT INTO pilah_sampahs_tmp (id, kepala_keluarga_nama, kepala_keluarga_nik, warga_id, kecamatan, kelurahan, rt, rw, dasawisma, jenis_sampah, jenis_kelamin, berat, sedekah, harga, foto, user_id, created_at, updated_at)
                 SELECT id, kepala_keluarga_nama, kepala_keluarga_nik, warga_id, kecamatan, kelurahan, rt, rw, dasawisma, jenis_sampah, jenis_kelamin, berat, sedekah, harga, foto, user_id, created_at, updated_at
                 FROM pilah_sampahs'
            );

            Schema::drop('pilah_sampahs');
            DB::statement('ALTER TABLE pilah_sampahs_tmp RENAME TO pilah_sampahs');

            DB::statement('PRAGMA foreign_keys=ON');
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE pilah_sampahs DROP FOREIGN KEY pilah_sampahs_warga_id_foreign');
            DB::statement('ALTER TABLE pilah_sampahs MODIFY warga_id BIGINT UNSIGNED NULL');
            DB::statement("ALTER TABLE pilah_sampahs MODIFY jenis_kelamin ENUM('Laki-laki','Perempuan') NULL");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN warga_id DROP NOT NULL');
            DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN jenis_kelamin DROP NOT NULL');
            return;
        }

        if ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE pilah_sampahs DROP CONSTRAINT pilah_sampahs_warga_id_foreign');
            DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN warga_id BIGINT NULL');
            DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN jenis_kelamin VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');

            Schema::create('pilah_sampahs_tmp', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('warga_id');
                $table->string('jenis_kelamin');
                $table->decimal('berat', 10, 2);
                $table->boolean('sedekah')->default(false);
                $table->decimal('harga', 12, 2);
                $table->string('foto')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
                $table->string('kecamatan')->nullable();
                $table->string('kelurahan')->nullable();
                $table->string('rt')->nullable();
                $table->string('rw')->nullable();
                $table->string('dasawisma')->nullable();
                $table->string('jenis_sampah')->nullable();
            });

            DB::statement(
                'INSERT INTO pilah_sampahs_tmp (id, warga_id, jenis_kelamin, berat, sedekah, harga, foto, user_id, created_at, updated_at, kecamatan, kelurahan, rt, rw, dasawisma, jenis_sampah)
                 SELECT id, COALESCE(warga_id, 0), COALESCE(jenis_kelamin, \'\'), berat, sedekah, harga, foto, user_id, created_at, updated_at, kecamatan, kelurahan, rt, rw, dasawisma, jenis_sampah
                 FROM pilah_sampahs'
            );

            Schema::drop('pilah_sampahs');
            DB::statement('ALTER TABLE pilah_sampahs_tmp RENAME TO pilah_sampahs');
            DB::statement('PRAGMA foreign_keys=ON');
            return;
        } else {
            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                DB::statement("ALTER TABLE pilah_sampahs MODIFY jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL");
                DB::statement('ALTER TABLE pilah_sampahs MODIFY warga_id BIGINT UNSIGNED NOT NULL');
                DB::statement('ALTER TABLE pilah_sampahs ADD CONSTRAINT pilah_sampahs_warga_id_foreign FOREIGN KEY (warga_id) REFERENCES wargas(id) ON DELETE CASCADE');
            } elseif ($driver === 'pgsql') {
                DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN warga_id SET NOT NULL');
                DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN jenis_kelamin SET NOT NULL');
                DB::statement('ALTER TABLE pilah_sampahs ADD CONSTRAINT pilah_sampahs_warga_id_foreign FOREIGN KEY (warga_id) REFERENCES wargas(id) ON DELETE CASCADE');
            } elseif ($driver === 'sqlsrv') {
                DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN warga_id BIGINT NOT NULL');
                DB::statement('ALTER TABLE pilah_sampahs ALTER COLUMN jenis_kelamin VARCHAR(255) NOT NULL');
                DB::statement('ALTER TABLE pilah_sampahs ADD CONSTRAINT pilah_sampahs_warga_id_foreign FOREIGN KEY (warga_id) REFERENCES wargas(id) ON DELETE CASCADE');
            }
        }

        Schema::table('pilah_sampahs', function (Blueprint $table) {
            $table->dropColumn(['kepala_keluarga_nama', 'kepala_keluarga_nik']);
        });
    }
};
