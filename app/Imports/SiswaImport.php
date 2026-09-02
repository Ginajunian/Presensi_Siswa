<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public int $berhasil = 0;
    public array $dilewati = [];
    public array $gagal = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $baris = $index + 2;

            $nis = trim((string) ($row['nis'] ?? ''));
            $nama = trim((string) ($row['nama'] ?? ''));
            $jenisKelamin = strtoupper(trim((string) ($row['jenis_kelamin_lp'] ?? '')));
            $namaKelas = trim((string) ($row['nama_kelas'] ?? ''));

            if ($nis === '' || $nama === '' || $namaKelas === '') {
                $this->gagal[] = ['baris' => $baris, 'nis' => $nis ?: '-', 'alasan' => 'Kolom NIS/Nama/Nama Kelas ada yang kosong.'];
                continue;
            }

            if (!preg_match('/^\d{10}$/', $nis)) {
                $this->gagal[] = ['baris' => $baris, 'nis' => $nis, 'alasan' => 'NIS harus 10 digit angka (format NISN).'];
                continue;
            }

            if (!in_array($jenisKelamin, ['L', 'P'])) {
                $this->gagal[] = ['baris' => $baris, 'nis' => $nis, 'alasan' => 'Kolom Jenis Kelamin harus diisi "L" atau "P".'];
                continue;
            }

            $kelas = Kelas::whereRaw('LOWER(nama_kelas) = ?', [strtolower($namaKelas)])->first();

            if (!$kelas) {
                $this->gagal[] = ['baris' => $baris, 'nis' => $nis, 'alasan' => "Kelas \"{$namaKelas}\" tidak ditemukan di sistem."];
                continue;
            }

            if (Siswa::where('nis', $nis)->exists()) {
                $this->dilewati[] = ['baris' => $baris, 'nis' => $nis, 'nama' => $nama, 'alasan' => 'NIS sudah terdaftar.'];
                continue;
            }

            Siswa::create([
                'nis' => $nis,
                'nama' => $nama,
                'jenis_kelamin' => $jenisKelamin,
                'kelas_id' => $kelas->id,
            ]);

            $this->berhasil++;
        }
    }
}