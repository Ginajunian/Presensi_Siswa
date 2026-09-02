<?php

namespace App\Models;

use App\Models\LogQr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'kelas_id',
        'foto',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Siswa $siswa) {
            if (empty($siswa->qr_token)) {
                $siswa->qr_token = (string) Str::uuid();
            }
        });
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function logQr(): HasMany
    {
        return $this->hasMany(LogQr::class);
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    public function regenerateQrToken(int $diubahOleh): void
    {
        $tokenLama = $this->qr_token;
        $this->qr_token = (string) Str::uuid();
        $this->save();

        LogQr::create([
            'siswa_id' => $this->id,
            'qr_token_lama' => $tokenLama,
            'qr_token_baru' => $this->qr_token,
            'diubah_oleh' => $diubahOleh,
        ]);
    }
}

