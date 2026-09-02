<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_presensi', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk_standar')->default('07:00:00');
            $table->unsignedInteger('toleransi_terlambat_menit')->default(15);
            $table->time('jam_pulang_standar')->default('15:00:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_presensi');
    }
};