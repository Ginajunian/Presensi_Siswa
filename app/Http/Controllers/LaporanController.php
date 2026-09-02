<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use App\Services\KelasAksesService;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Exports\RekapBulananExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{

    public function __construct(private KelasAksesService $kelasAkses)
    {
    }
    public function harian(Request $request)
    {
        $kelasList = $this->kelasAkses->untukUser(auth()->user());
        $kelasId = $request->kelas_id;
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();

        $this->pastikanAksesKelas($kelasList, $kelasId);

        $data = collect();

        if ($kelasId) {
            $presensiMap = Presensi::where('tanggal', $tanggal)
                ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
                ->get()->keyBy('siswa_id');

            $data = Siswa::where('kelas_id', $kelasId)
                ->where('status_aktif', true)
                ->orderBy('nama')
                ->get()
                ->map(fn($siswa) => [
                    'siswa' => $siswa,
                    'presensi' => $presensiMap->get($siswa->id),
                ]);
        }

        return view('laporan.harian', compact('kelasList', 'kelasId', 'tanggal', 'data'));
    }

    public function harianPdf(Request $request)
    {
        $kelasList = $this->kelasAkses->untukUser(auth()->user());
        $kelasId = $request->kelas_id;
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();

        $this->pastikanAksesKelas($kelasList, $kelasId);

        if (!$kelasId) {
            abort(400, 'Kelas wajib dipilih.');
        }

        $kelas = Kelas::findOrFail($kelasId);
        $presensiMap = Presensi::where('tanggal', $tanggal)
            ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
            ->get()->keyBy('siswa_id');

        $data = Siswa::where('kelas_id', $kelasId)->where('status_aktif', true)->orderBy('nama')->get()
            ->map(fn($siswa) => ['siswa' => $siswa, 'presensi' => $presensiMap->get($siswa->id)]);

        $pdf = Pdf::loadView('laporan.harian-pdf', compact('kelas', 'tanggal', 'data'));

        $namaKelasAman = $this->namaFileAman($kelas->nama_kelas);
        $tanggalFormat = Carbon::parse($tanggal)->translatedFormat('d-F-Y'); // contoh: 11-Agustus-2026
        $namaFile = "Presensi-Harian_{$namaKelasAman}_{$tanggalFormat}.pdf";

        return $pdf->download($namaFile);
    }

    public function bulanan(Request $request)
    {
        $kelasList = $this->kelasAkses->untukUser(auth()->user());
        $kelasId = $request->kelas_id;
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $this->pastikanAksesKelas($kelasList, $kelasId);

        $data = collect();

        if ($kelasId) {
            $awal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $akhir = $awal->copy()->endOfMonth();

            $siswaList = Siswa::where('kelas_id', $kelasId)->where('status_aktif', true)->orderBy('nama')->get();
            $data = $this->hitungRekapBulanan($siswaList, $awal->toDateString(), $akhir->toDateString());
        }

        return view('laporan.bulanan', compact('kelasList', 'kelasId', 'bulan', 'tahun', 'data'));
    }

    public function bulananExcel(Request $request)
    {
        $kelasList = $this->kelasAkses->untukUser(auth()->user());
        $kelasId = $request->kelas_id;
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $this->pastikanAksesKelas($kelasList, $kelasId);

        if (!$kelasId) {
            abort(400, 'Kelas wajib dipilih.');
        }

        $kelas = Kelas::findOrFail($kelasId);
        $awal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhir = $awal->copy()->endOfMonth();
        $siswaList = Siswa::where('kelas_id', $kelasId)->where('status_aktif', true)->orderBy('nama')->get();
        $data = $this->hitungRekapBulanan($siswaList, $awal->toDateString(), $akhir->toDateString());

        $namaKelasAman = $this->namaFileAman($kelas->nama_kelas);
        $namaBulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F'); // contoh: Agustus

        $namaFile = "Presensi-Bulanan_{$namaKelasAman}_{$namaBulan}-{$tahun}.xlsx";

        return Excel::download(new RekapBulananExport($data), $namaFile);
    }

    private function namaFileAman(string $text): string
    {
        $text = trim($text);
        $text = str_replace(' ', '-', $text);

        return preg_replace('/[^A-Za-z0-9\-_]/', '', $text);
    }

    /**
     * Admin bisa lihat semua kelas. Guru cuma bisa lihat kelas yang dia jadi wali kelasnya.
     */

    private function pastikanAksesKelas(Collection $kelasList, ?string $kelasId): void
    {
        if ($kelasId && !$kelasList->contains('id', (int) $kelasId)) {
            abort(403, 'Anda tidak memiliki akses ke laporan kelas ini.');
        }
    }

    private function hitungRekapBulanan(Collection $siswaList, string $awal, string $akhir): Collection
    {
        $rekapSemua = Presensi::whereIn('siswa_id', $siswaList->pluck('id'))
            ->whereBetween('tanggal', [$awal, $akhir])
            ->selectRaw('siswa_id, status, count(*) as total')
            ->groupBy('siswa_id', 'status')
            ->get()
            ->groupBy('siswa_id');

        return $siswaList->map(function ($siswa) use ($rekapSemua) {
            $rekap = ($rekapSemua->get($siswa->id) ?? collect())->pluck('total', 'status');
            $totalHadir = ($rekap['hadir'] ?? 0) + ($rekap['terlambat'] ?? 0);
            $totalTercatat = $rekap->sum();

            return [
                'siswa' => $siswa,
                'hadir' => $rekap['hadir'] ?? 0,
                'terlambat' => $rekap['terlambat'] ?? 0,
                'izin' => $rekap['izin'] ?? 0,
                'sakit' => $rekap['sakit'] ?? 0,
                'alpa' => $rekap['alpa'] ?? 0,
                'persentase' => $totalTercatat > 0 ? round($totalHadir / $totalTercatat * 100, 1) : 0,
            ];
        });
    }

}