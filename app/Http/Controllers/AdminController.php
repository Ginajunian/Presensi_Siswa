<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::role('admin')->orderBy('name')->paginate(10);

        return view('admin-akun.index', compact('admins'));
    }

    public function create()
    {
        return view('admin-akun.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);
        $user->assignRole('admin');

        return redirect()->route('admin-akun.index')->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin-akun.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = ['name' => $validated['name']];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin-akun.index')->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return redirect()->route('admin-akun.index')->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        if ($user->is_active && User::role('admin')->where('is_active', true)->count() <= 1) {
            return redirect()->route('admin-akun.index')->with('error', 'Tidak bisa menonaktifkan admin terakhir yang aktif. Minimal harus ada 1 admin aktif.');
        }

        $user->update(['is_active' => ! $user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin-akun.index')->with('success', "Akun \"{$user->name}\" berhasil {$status}.");
    }
}