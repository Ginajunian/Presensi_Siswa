<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    protected $table = 'presensi';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'status',
        'waktu_masuk',
        'dicatat_oleh_masuk',
        'waktu_pulang',
        'dicatat_oleh_pulang',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function dicatatOlehMasuk(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh_masuk');
    }

    public function dicatatOlehPulang(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh_pulang');
    }
}