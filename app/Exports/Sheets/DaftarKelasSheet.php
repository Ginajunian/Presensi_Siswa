<?php

namespace App\Exports\Sheets;

use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DaftarKelasSheet implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return Kelas::orderBy('nama_kelas')->pluck('nama_kelas')
            ->map(fn ($nama) => [$nama])
            ->toArray();
    }

    public function headings(): array
    {
        return ['Nama Kelas (copy-paste persis dari sini ke kolom "Nama Kelas" di sheet Template)'];
    }

    public function title(): string
    {
        return 'Daftar Kelas';
    }
}