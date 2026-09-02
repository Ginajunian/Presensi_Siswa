<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with('user')->latest()->paginate(10);

        return view('guru.index', compact('guru'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'nip' => 'nullable|string|max:30|unique:guru,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_active' => true,
            ]);
            $user->assignRole('guru');

            Guru::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'nip' => $validated['nip'] ?? null,
            ]);
        });

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'nip' => ['nullable', 'string', 'max:30', Rule::unique('guru', 'nip')->ignore($guru->id)],
            'password' => 'nullable|string|min:8',
        ]);

        $guru->update([
            'nama' => $validated['nama'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'nip' => $validated['nip'] ?? null,
        ]);

        $userUpdate = ['name' => $validated['nama']];

        if (!empty($validated['password'])) {
            $userUpdate['password'] = Hash::make($validated['password']);
        }

        $guru->user->update($userUpdate);

        $message = !empty($validated['password'])
            ? 'Data guru diperbarui dan password baru berhasil di-set.'
            : 'Data guru berhasil diperbarui.';

        return redirect()->route('guru.index')->with('success', $message);
    }

    public function toggleStatus(Guru $guru)
    {
        $guru->user->update(['is_active' => !$guru->user->is_active]);

        $status = $guru->user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('guru.index')->with('success', "Akun guru \"{$guru->nama}\" berhasil {$status}.");
    }
}