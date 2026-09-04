<?php

namespace App\Http\Controllers;


use App\Services\KelasAksesService;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function __construct(private KelasAksesService $kelasAkses)
    {
    }
    public function index()
    {
        $kelasList = $this->kelasAkses->untukUser(auth()->user());
        $kelasIds = $kelasList->pluck('id');
        $tanggal = Carbon::today()->toDateString();

        $totalSiswaAktif = Siswa::whereIn('kelas_id', $kelasIds)->where('status_aktif', true)->count();

        $presensiHariIni = Presensi::where('tanggal', $tanggal)
            ->whereHas('siswa', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->get();

        $hadirHariIni = $presensiHariIni->whereIn('status', ['hadir', 'terlambat'])->count();
        $izinHariIni = $presensiHariIni->where('status', 'izin')->count();
        $sakitHariIni = $presensiHariIni->where('status', 'sakit')->count();
        $alpaHariIni = $presensiHariIni->where('status', 'alpa')->count();
        $belumTercatat = max(0, $totalSiswaAktif - $presensiHariIni->count());

        $persentaseHariIni = $totalSiswaAktif > 0 ? round($hadirHariIni / $totalSiswaAktif * 100, 1) : 0;

        // --- Bagian trend, pastikan ini ADA dan ADA DI ATAS return view() ---
        $kemarin = Carbon::yesterday()->toDateString();
        $hadirKemarin = Presensi::where('tanggal', $kemarin)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->whereHas('siswa', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->count();
        $persentaseKemarin = $totalSiswaAktif > 0 ? round($hadirKemarin / $totalSiswaAktif * 100, 1) : 0;
        $trendKehadiran = $persentaseHariIni - $persentaseKemarin;
        // --- akhir bagian trend ---

        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();

        $topAlpa = Presensi::where('status', 'alpa')
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->whereHas('siswa', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->selectRaw('siswa_id, count(*) as total')
            ->groupBy('siswa_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('siswa')
            ->get();

        $awal7Hari = Carbon::today()->subDays(6)->toDateString();
        $akhir7Hari = Carbon::today()->toDateString();

        $rekapPerHari = Presensi::whereIn('status', ['hadir', 'terlambat'])
            ->whereBetween('tanggal', [$awal7Hari, $akhir7Hari])
            ->whereHas('siswa', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->selectRaw('tanggal, count(*) as total')
            ->groupBy('tanggal')
            ->get()
            ->keyBy(fn($row) => Carbon::parse($row->tanggal)->toDateString());

        $tren = collect(range(6, 0))->map(function ($i) use ($rekapPerHari, $totalSiswaAktif) {
            $tgl = Carbon::today()->subDays($i);
            $hadir = optional($rekapPerHari->get($tgl->toDateString()))->total ?? 0;

            return [
                'label' => $tgl->translatedFormat('d M'),
                'persentase' => $totalSiswaAktif > 0 ? round($hadir / $totalSiswaAktif * 100, 1) : 0,
            ];
        });

        return view('dashboard', compact(
            'kelasList',
            'totalSiswaAktif',
            'hadirHariIni',
            'izinHariIni',
            'sakitHariIni',
            'alpaHariIni',
            'belumTercatat',
            'persentaseHariIni',
            'trendKehadiran',
            'topAlpa',
            'tren'
        ));
    }
}