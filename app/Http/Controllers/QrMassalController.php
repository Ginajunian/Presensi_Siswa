<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\LogQr;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QrMassalController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kelasId = $request->kelas_id;

        $siswaList = Siswa::with('kelas')
            ->where('status_aktif', true)
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->orderBy('nama')
            ->get();

        return view('siswa.qr-massal', compact('kelasList', 'kelasId', 'siswaList'));
    }

    public function regenerate(Request $request)
    {
        $validated = $request->validate([
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            foreach (Siswa::whereIn('id', $validated['siswa_ids'])->get() as $siswa) {
                $siswa->regenerateQrToken($request->user()->id);
            }
        });

        $jumlah = count($validated['siswa_ids']);

        return redirect()->route('siswa.qr-massal')
            ->with('success', "{$jumlah} QR Code berhasil di-generate ulang. Kartu lama otomatis tidak berlaku lagi.");
    }

    public function downloadPdf(Request $request)
    {
        $validated = $request->validate([
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        $siswaList = Siswa::with('kelas')->whereIn('id', $validated['siswa_ids'])->orderBy('nama')->get();

        $kartuData = $siswaList->map(function ($siswa) {
            $qr = (new Builder())->build(
                writer: new PngWriter(),
                data: $siswa->qr_token,
                size: 220,
                margin: 8,
            );

            $fotoBase64 = null;
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                $fotoBase64 = base64_encode(Storage::disk('public')->get($siswa->foto));
            }

            return [
                'siswa' => $siswa,
                'qr_base64' => base64_encode($qr->getString()),
                'foto_base64' => $fotoBase64,
            ];
        });

        $pdf = Pdf::loadView('siswa.qr-massal-pdf', compact('kartuData'))->setPaper('a4', 'portrait');

        return $pdf->download('kartu-qr-siswa-' . now()->format('Ymd-His') . '.pdf');
    }
}