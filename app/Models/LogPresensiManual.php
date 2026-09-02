<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPresensiManual extends Model
{
    protected $table = 'log_presensi_manual';

    public $timestamps = false;

    protected $fillable = [
        'siswa_id', 'tanggal', 'status_lama', 'status_baru', 'keterangan', 'diubah_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function diubahOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}