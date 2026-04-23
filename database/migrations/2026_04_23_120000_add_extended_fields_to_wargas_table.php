<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wargas', function (Blueprint $table) {
            $table->string('status_dalam_keluarga')->nullable()->after('dasawisma');
            $table->string('no_kk')->nullable()->after('status_dalam_keluarga');
            $table->string('no_register_pkk')->nullable()->after('no_kk');
            $table->string('agama')->nullable()->after('no_register_pkk');
            $table->string('status_perkawinan')->nullable()->after('agama');
            $table->text('alamat')->nullable()->after('status_perkawinan');
            $table->string('pendidikan')->nullable()->after('alamat');
            $table->string('pekerjaan')->nullable()->after('pendidikan');
            $table->string('status_tinggal')->nullable()->after('pekerjaan');
            $table->string('merantau_ke')->nullable()->after('status_tinggal');
            $table->string('perantau_dari')->nullable()->after('merantau_ke');
            $table->boolean('akseptor_kb')->default(false)->after('perantau_dari');
            $table->boolean('aktif_posyandu')->default(false)->after('akseptor_kb');
            $table->boolean('bina_keluarga_balita')->default(false)->after('aktif_posyandu');
            $table->boolean('memiliki_tabungan')->default(false)->after('bina_keluarga_balita');
            $table->boolean('mengikuti_kelompok_belajar')->default(false)->after('memiliki_tabungan');
            $table->string('jenis_kelompok_belajar')->nullable()->after('mengikuti_kelompok_belajar');
            $table->boolean('ikut_kegiatan_operasional')->default(false)->after('jenis_kelompok_belajar');
            $table->string('jenis_operasi')->nullable()->after('ikut_kegiatan_operasional');
            $table->boolean('mengikuti_paud')->default(false)->after('jenis_operasi');
            $table->boolean('berkebutuhan_khusus')->default(false)->after('mengikuti_paud');
            $table->boolean('buta')->default(false)->after('berkebutuhan_khusus');
            $table->boolean('hamil')->default(false)->after('buta');
            $table->boolean('menyusui')->default(false)->after('hamil');
            $table->string('status')->nullable()->after('menyusui');
        });
    }

    public function down(): void
    {
        Schema::table('wargas', function (Blueprint $table) {
            $table->dropColumn([
                'status_dalam_keluarga',
                'no_kk',
                'no_register_pkk',
                'agama',
                'status_perkawinan',
                'alamat',
                'pendidikan',
                'pekerjaan',
                'status_tinggal',
                'merantau_ke',
                'perantau_dari',
                'akseptor_kb',
                'aktif_posyandu',
                'bina_keluarga_balita',
                'memiliki_tabungan',
                'mengikuti_kelompok_belajar',
                'jenis_kelompok_belajar',
                'ikut_kegiatan_operasional',
                'jenis_operasi',
                'mengikuti_paud',
                'berkebutuhan_khusus',
                'buta',
                'hamil',
                'menyusui',
                'status',
            ]);
        });
    }
};
