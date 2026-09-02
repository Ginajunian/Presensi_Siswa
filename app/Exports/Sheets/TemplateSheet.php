<?php

namespace App\Exports\Sheets;

use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TemplateSheet implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        $kelasList = Kelas::orderBy('nama_kelas')->limit(3)->pluck('nama_kelas');

        if ($kelasList->isEmpty()) {
            return [['2026000001', 'Contoh Nama Siswa', 'L', '(Belum ada data kelas — tambahkan kelas dulu di menu Kelas)']];
        }

        return $kelasList->values()->map(function ($namaKelas, $i) {
            return [
                '2026' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'Contoh Siswa ' . ($i + 1),
                $i % 2 === 0 ? 'L' : 'P',
                $namaKelas,
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return ['NIS', 'Nama', 'Jenis Kelamin (L/P)', 'Nama Kelas'];
    }

    public function title(): string
    {
        return 'Template';
    }
}