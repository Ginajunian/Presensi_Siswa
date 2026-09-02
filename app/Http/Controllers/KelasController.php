<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $kelas = Kelas::with('waliKelas')->latest()->paginate(10);

        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $guruList = Guru::orderBy('nama')->get();

        return view('kelas.create', compact('guruList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:guru,id',
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $guruList = Guru::orderBy('nama')->get();

        return view('kelas.edit', compact('kela', 'guruList'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:guru,id',
        ]);

        $kela->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

public function destroy(Kelas $kela)
{
    if ($kela->siswa()->exists()) {
        return redirect()->route('kelas.index')
            ->with('error', "Kelas \"{$kela->nama_kelas}\" tidak bisa dihapus karena masih memiliki siswa terdaftar. Pindahkan siswa ke kelas lain terlebih dahulu.");
    }

    $kela->delete();
    return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
}
}