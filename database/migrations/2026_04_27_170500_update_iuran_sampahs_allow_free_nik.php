<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iuran_sampahs', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('warga_id');
        });

        Schema::table('iuran_sampahs', function (Blueprint $table) {
            $table->dropForeign(['warga_id']);
        });

        Schema::table('iuran_sampahs', function (Blueprint $table) {
            $table->foreignId('warga_id')->nullable()->change();
        });

        Schema::table('iuran_sampahs', function (Blueprint $table) {
            $table->foreign('warga_id')->references('id')->on('wargas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        DB::table('iuran_sampahs')->whereNull('warga_id')->delete();

        Schema::table('iuran_sampahs', function (Blueprint $table) {
            $table->dropForeign(['warga_id']);
        });

        Schema::table('iuran_sampahs', function (Blueprint $table) {
            $table->foreignId('warga_id')->nullable(false)->change();
        });

        Schema::table('iuran_sampahs', function (Blueprint $table) {
            $table->foreign('warga_id')->references('id')->on('wargas')->onDelete('cascade');
            $table->dropColumn('nik');
        });
    }
};
