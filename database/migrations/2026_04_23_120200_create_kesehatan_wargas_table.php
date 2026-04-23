<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kesehatan_wargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('wargas')->onDelete('cascade');
            $table->boolean('kek')->default(false);
            $table->boolean('anemia')->default(false);
            $table->boolean('haid_lebih_7_hari')->default(false);
            $table->boolean('belum_imunisasi')->default(false);
            $table->boolean('tbc_mangkir')->default(false);
            $table->boolean('remaja_rokok')->default(false);
            $table->boolean('ada_jentik')->default(false);
            $table->date('tanggal_laporan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kesehatan_wargas');
    }
};
