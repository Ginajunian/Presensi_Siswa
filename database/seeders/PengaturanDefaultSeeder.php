<?php

namespace Database\Seeders;

use App\Models\PengaturanHariLibur;
use App\Models\PengaturanPresensi;
use Illuminate\Database\Seeder;

class PengaturanDefaultSeeder extends Seeder
{
    public function run(): void
    {
        PengaturanPresensi::current();

        PengaturanHariLibur::firstOrCreate(['hari' => 'Minggu']);
    }
}