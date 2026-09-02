<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogQr extends Model
{
    protected $table = 'log_qr';

    public $timestamps = false;

    protected $fillable = [
        'siswa_id',
        'qr_token_lama',
        'qr_token_baru',
        'diubah_oleh',
    ];

    protected $casts = [
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