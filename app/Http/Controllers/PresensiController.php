<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\PengaturanHariLibur;
use App\Models\PengaturanPresensi;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\LogPresensiManual;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    private array $petaHari = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    public function index()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $isLibur = $this->isHariLibur();

        return view('presensi.index', compact('kelasList', 'isLibur'));
    }

    public function scanner(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'sesi' => 'required|in:masuk,pulang',
        ]);

        if ($this->isHariLibur()) {
            return redirect()->route('presensi.index')
                ->with('error', 'Hari ini adalah hari libur. Sesi presensi tidak dibuka.');
        }

        $kelas = Kelas::findOrFail($validated['kelas_id']);
        $sesi = $validated['sesi'];
        $tanggal = Carbon::today()->toDateString();

        if ($sesi === 'masuk') {
            $siswaList = Siswa::where('kelas_id', $kelas->id)
                ->where('status_aktif', true)
                ->orderBy('nama')->get();
        } else {
            $siswaList = Siswa::where('kelas_id', $kelas->id)
                ->where('status_aktif', true)
                ->whereHas('presensi', fn($q) => $q->where('tanggal', $tanggal)->whereIn('status', ['hadir', 'terlambat']))
                ->orderBy('nama')->get();
        }

        $presensiHariIni = Presensi::where('tanggal', $tanggal)
            ->whereIn('siswa_id', $siswaList->pluck('id'))
            ->get()->keyBy('siswa_id');

        return view('presensi.scanner', compact('kelas', 'sesi', 'siswaList', 'presensiHariIni'));
    }

    public function prosesScan(Request $request)
    {
        $validated = $request->validate([
            'qr_token' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'sesi' => 'required|in:masuk,pulang',
        ]);

        if ($this->isHariLibur()) {
            return response()->json(['status' => 'libur', 'message' => 'Hari ini libur, sesi presensi tidak dibuka.'], 422);
        }

        $siswa = Siswa::where('qr_token', $validated['qr_token'])->first();

        if (!$siswa) {
            return response()->json(['status' => 'invalid', 'message' => 'QR Code tidak dikenali / tidak valid.'], 404);
        }

        if (!$siswa->status_aktif) {
            return response()->json(['status' => 'inactive', 'message' => "{$siswa->nama} berstatus nonaktif."], 422);
        }

        if ($siswa->kelas_id != $validated['kelas_id']) {
            return response()->json(['status' => 'wrong_class', 'message' => "{$siswa->nama} bukan siswa di kelas ini."], 422);
        }

        $tanggal = Carbon::today()->toDateString();
        $sekarang = Carbon::now();

        return $validated['sesi'] === 'masuk'
            ? $this->prosesMasuk($siswa, $tanggal, $sekarang)
            : $this->prosesPulang($siswa, $tanggal, $sekarang);
    }

    private function prosesMasuk(Siswa $siswa, string $tanggal, Carbon $sekarang)
    {
        $existing = Presensi::where('siswa_id', $siswa->id)->where('tanggal', $tanggal)->first();

        if ($existing && $existing->waktu_masuk) {
            return response()->json([
                'status' => 'duplikat',
                'message' => "{$siswa->nama} sudah presensi masuk pukul " . Carbon::parse($existing->waktu_masuk)->format('H:i'),
                'nama' => $siswa->nama,
            ]);
        }

        $pengaturan = PengaturanPresensi::current();
        $batasTerlambat = Carbon::parse($pengaturan->jam_masuk_standar)->addMinutes($pengaturan->toleransi_terlambat_menit);
        $statusMasuk = $sekarang->format('H:i:s') > $batasTerlambat->format('H:i:s') ? 'terlambat' : 'hadir';

        Presensi::updateOrCreate(
            ['siswa_id' => $siswa->id, 'tanggal' => $tanggal],
            [
                'status' => $statusMasuk,
                'waktu_masuk' => $sekarang->format('H:i:s'),
                'dicatat_oleh_masuk' => auth()->id(),
            ]
        );

        return response()->json([
            'status' => 'sukses',
            'sub_status' => $statusMasuk,
            'message' => "{$siswa->nama} tercatat " . ($statusMasuk === 'terlambat' ? 'Terlambat' : 'Hadir') . " pukul {$sekarang->format('H:i')}",
            'nama' => $siswa->nama,
        ]);
    }

    private function prosesPulang(Siswa $siswa, string $tanggal, Carbon $sekarang)
    {
        $existing = Presensi::where('siswa_id', $siswa->id)->where('tanggal', $tanggal)->first();

        if (!$existing || !in_array($existing->status, ['hadir', 'terlambat'])) {
            return response()->json([
                'status' => 'belum_masuk',
                'message' => "{$siswa->nama} belum tercatat presensi masuk hari ini.",
            ], 422);
        }

        if ($existing->waktu_pulang) {
            return response()->json([
                'status' => 'duplikat',
                'message' => "{$siswa->nama} sudah presensi pulang pukul " . Carbon::parse($existing->waktu_pulang)->format('H:i'),
                'nama' => $siswa->nama,
            ]);
        }

        $existing->update([
            'waktu_pulang' => $sekarang->format('H:i:s'),
            'dicatat_oleh_pulang' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'sukses',
            'message' => "{$siswa->nama} tercatat pulang pukul {$sekarang->format('H:i')}",
            'nama' => $siswa->nama,
        ]);
    }

    private function isHariLibur(): bool
    {
        $hariIni = $this->petaHari[Carbon::now()->dayOfWeek];

        return PengaturanHariLibur::where('hari', $hariIni)->exists();
    }

    public function manual(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kelasId = $request->kelas_id;
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();

        $siswaList = collect();

        if ($kelasId) {
            $siswaList = Siswa::where('kelas_id', $kelasId)
                ->where('status_aktif', true)
                ->whereDoesntHave('presensi', fn($q) => $q->where('tanggal', $tanggal)->whereIn('status', ['hadir', 'terlambat']))
                ->orderBy('nama')->get();
        }

        $presensiSaatIni = Presensi::where('tanggal', $tanggal)
            ->whereIn('siswa_id', $siswaList->pluck('id'))
            ->get()->keyBy('siswa_id');

        return view('presensi.manual', compact('kelasList', 'kelasId', 'tanggal', 'siswaList', 'presensiSaatIni'));
    }

    public function simpanManual(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|array',
            'status.*' => 'nullable|in:izin,sakit,alpa',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        $tanggal = $validated['tanggal'];
        $disimpan = 0;

        DB::transaction(function () use ($validated, $tanggal, &$disimpan) {
            foreach ($validated['status'] as $siswaId => $status) {
                if (empty($status)) {
                    continue; // baris yang tidak diisi admin, lewati saja
                }

                $existing = Presensi::where('siswa_id', $siswaId)->where('tanggal', $tanggal)->first();
                $statusLama = $existing?->status;

                Presensi::updateOrCreate(
                    ['siswa_id' => $siswaId, 'tanggal' => $tanggal],
                    ['status' => $status, 'keterangan' => $validated['keterangan'][$siswaId] ?? null]
                );

                LogPresensiManual::create([
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                    'status_lama' => $statusLama,
                    'status_baru' => $status,
                    'keterangan' => $validated['keterangan'][$siswaId] ?? null,
                    'diubah_oleh' => auth()->id(),
                ]);

                $disimpan++;
            }
        });

        return redirect()->route('presensi.manual', ['kelas_id' => $validated['kelas_id'], 'tanggal' => $tanggal])
            ->with('success', "{$disimpan} status presensi berhasil disimpan.");
    }

    public function manualSingle(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:izin,sakit,alpa,batal',
        ]);

        $siswa = Siswa::findOrFail($validated['siswa_id']);
        $tanggal = $validated['tanggal'];
        $existing = Presensi::where('siswa_id', $siswa->id)->where('tanggal', $tanggal)->first();

        // Kalau siswa sudah tercatat Hadir/Terlambat dari hasil scan, manual tidak boleh menimpa.
        if ($existing && in_array($existing->status, ['hadir', 'terlambat'])) {
            return response()->json([
                'status' => 'gagal',
                'message' => "{$siswa->nama} sudah tercatat via scan, tidak bisa ditandai manual.",
            ], 422);
        }

        $statusLama = $existing?->status;

        if ($validated['status'] === 'batal') {
            $existing?->delete();

            LogPresensiManual::create([
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal,
                'status_lama' => $statusLama,
                'status_baru' => 'dibatalkan',
                'diubah_oleh' => auth()->id(),
            ]);

            return response()->json(['status' => 'sukses', 'message' => "Penandaan {$siswa->nama} dibatalkan.", 'status_baru' => null]);
        }

        Presensi::updateOrCreate(
            ['siswa_id' => $siswa->id, 'tanggal' => $tanggal],
            ['status' => $validated['status']]
        );

        LogPresensiManual::create([
            'siswa_id' => $siswa->id,
            'tanggal' => $tanggal,
            'status_lama' => $statusLama,
            'status_baru' => $validated['status'],
            'diubah_oleh' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'sukses',
            'message' => "{$siswa->nama} ditandai " . ucfirst($validated['status']) . ".",
            'status_baru' => $validated['status'],
        ]);
    }
}