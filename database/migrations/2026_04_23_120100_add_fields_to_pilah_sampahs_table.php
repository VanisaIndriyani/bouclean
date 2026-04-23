<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pilah_sampahs', function (Blueprint $table) {
            $table->string('kecamatan')->nullable()->after('warga_id');
            $table->string('kelurahan')->nullable()->after('kecamatan');
            $table->char('rt', 3)->nullable()->after('kelurahan');
            $table->char('rw', 3)->nullable()->after('rt');
            $table->string('dasawisma')->nullable()->after('rw');
            $table->string('jenis_sampah')->nullable()->after('dasawisma');
        });
    }

    public function down(): void
    {
        Schema::table('pilah_sampahs', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'kelurahan', 'rt', 'rw', 'dasawisma', 'jenis_sampah']);
        });
    }
};
