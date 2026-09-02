<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanPresensi extends Model
{
    protected $table = 'pengaturan_presensi';

    protected $fillable = [
        'jam_masuk_standar',
        'toleransi_terlambat_menit',
        'jam_pulang_standar',
    ];

    /**
     * Ambil baris pengaturan (buat otomatis kalau belum ada sama sekali).
     * Karena single-tenant, kita jamin cuma ada 1 baris lewat method ini.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'jam_masuk_standar' => '07:00:00',
            'toleransi_terlambat_menit' => 15,
            'jam_pulang_standar' => '15:00:00',
        ]);
    }
}