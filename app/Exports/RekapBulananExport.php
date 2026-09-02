<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekapBulananExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected $data)
    {
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return ['Nama', 'Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa', '% Kehadiran'];
    }

    public function map($row): array
    {
        return [
            $row['siswa']->nama,
            $row['hadir'],
            $row['terlambat'],
            $row['izin'],
            $row['sakit'],
            $row['alpa'],
            $row['persentase'] . '%',
        ];
    }
}