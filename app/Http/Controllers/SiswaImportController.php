<?php

namespace App\Http\Controllers;

use App\Exports\SiswaImportTemplateExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiswaImportController extends Controller
{
    public function form()
    {
        return view('siswa.import');
    }

    public function template()
    {
        return Excel::download(new SiswaImportTemplateExport(), 'template-import-siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $import = new SiswaImport();
        Excel::import($import, $request->file('file'));

        return view('siswa.import-hasil', [
            'berhasil' => $import->berhasil,
            'dilewati' => $import->dilewati,
            'gagal' => $import->gagal,
        ]);
    }
}