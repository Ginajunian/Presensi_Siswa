<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Format;
use Illuminate\Http\UploadedFile;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\LogQr;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::with('kelas')
            ->when($request->kelas_id, fn($q) => $q->where('kelas_id', $request->kelas_id))
            ->when($request->search, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('nis', 'like', "%{$request->search}%");
            }))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.index', compact('siswa', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|digits:10|unique:siswa,nis',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->simpanFotoTerkompresi($request->file('foto'));
        }

        Siswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => ['required', 'digits:10', Rule::unique('siswa', 'nis')->ignore($siswa->id)],
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $validated['foto'] = $this->simpanFotoTerkompresi($request->file('foto'));
        }

        $siswa->update($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function toggleStatus(Siswa $siswa)
    {
        $siswa->update(['status_aktif' => !$siswa->status_aktif]);

        $status = $siswa->status_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('siswa.index')->with('success', "Siswa \"{$siswa->nama}\" berhasil {$status}.");
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->presensi()->exists()) {
            return redirect()->route('siswa.index')
                ->with('error', "Siswa \"{$siswa->nama}\" tidak bisa dihapus karena sudah memiliki riwayat presensi. Gunakan tombol \"Nonaktifkan\" sebagai gantinya.");
        }

        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', "Siswa \"{$siswa->nama}\" berhasil dihapus.");
    }
    public function qrCard(Siswa $siswa)
    {
        $siswa->load('kelas');

        return view('siswa.qr-card', compact('siswa'));
    }

    public function qrImage(Request $request, Siswa $siswa)
    {
        $size = (int) $request->query('size', 300);
        $size = max(60, min($size, 600)); // jaga-jaga dari nilai aneh/ekstrem lewat URL

        $result = (new Builder())->build(
            writer: new PngWriter(),
            data: $siswa->qr_token,
            size: $size,
            margin: 10,
        );

        return response($result->getString())
            ->header('Content-Type', $result->getMimeType());
    }

    public function regenerateQr(Request $request, Siswa $siswa)
    {
        DB::transaction(fn() => $siswa->regenerateQrToken($request->user()->id));

        return redirect()->route('siswa.qr-card', $siswa)
            ->with('success', 'QR Code berhasil di-generate ulang. Kartu lama otomatis tidak berlaku lagi.');
    }

    private function simpanFotoTerkompresi(UploadedFile $file): string
    {
        $manager = ImageManager::usingDriver(GdDriver::class);
        $image = $manager->decodeSplFileInfo($file);
        $image->scaleDown(width: 500);

        $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 75);

        $path = 'siswa-foto/' . Str::uuid() . '.jpg';
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }
}