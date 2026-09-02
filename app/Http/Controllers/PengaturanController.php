<?php

namespace App\Http\Controllers;

use App\Models\PengaturanHariLibur;
use App\Models\PengaturanPresensi;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    private array $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function edit()
    {
        $pengaturan = PengaturanPresensi::current();
        $hariLiburAktif = PengaturanHariLibur::pluck('hari')->toArray();

        return view('pengaturan.edit', [
            'pengaturan' => $pengaturan,
            'hariLiburAktif' => $hariLiburAktif,
            'hariList' => $this->hariList,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'jam_masuk_standar' => 'required|date_format:H:i',
            'toleransi_terlambat_menit' => 'required|integer|min:0|max:120',
            'jam_pulang_standar' => 'required|date_format:H:i',
            'hari_libur' => 'nullable|array',
            'hari_libur.*' => 'in:' . implode(',', $this->hariList),
        ]);

        PengaturanPresensi::current()->update([
            'jam_masuk_standar' => $validated['jam_masuk_standar'],
            'toleransi_terlambat_menit' => $validated['toleransi_terlambat_menit'],
            'jam_pulang_standar' => $validated['jam_pulang_standar'],
        ]);

        PengaturanHariLibur::query()->delete();
        foreach ($validated['hari_libur'] ?? [] as $hari) {
            PengaturanHariLibur::create(['hari' => $hari]);
        }

        return redirect()->route('pengaturan.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }
}