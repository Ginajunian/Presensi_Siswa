<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Collection;

class KelasAksesService
{
    /**
     * Admin bisa lihat semua kelas. Guru cuma bisa lihat kelas yang dia jadi wali kelasnya.
     */
    public function untukUser(User $user): Collection
    {
        if ($user->hasRole('admin')) {
            return Kelas::orderBy('nama_kelas')->get();
        }

        $guru = Guru::where('user_id', $user->id)->first();

        return $guru ? Kelas::where('wali_kelas_id', $guru->id)->orderBy('nama_kelas')->get() : collect();
    }
}