<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaImportController;
use App\Http\Controllers\QrMassalController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin-akun', [AdminController::class, 'index'])->name('admin-akun.index');
    Route::get('admin-akun/tambah', [AdminController::class, 'create'])->name('admin-akun.create');
    Route::post('admin-akun', [AdminController::class, 'store'])->name('admin-akun.store');
    Route::get('admin-akun/{user}/edit', [AdminController::class, 'edit'])->name('admin-akun.edit');
    Route::put('admin-akun/{user}', [AdminController::class, 'update'])->name('admin-akun.update');
    Route::patch('admin-akun/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin-akun.toggle-status');
    Route::resource('kelas', KelasController::class);
    Route::resource('guru', GuruController::class)->except(['destroy']);
    Route::resource('siswa', SiswaController::class)->except(['show']);
    Route::patch('guru/{guru}/toggle-status', [GuruController::class, 'toggleStatus'])->name('guru.toggle-status');
    Route::patch('siswa/{siswa}/toggle-status', [SiswaController::class, 'toggleStatus'])->name('siswa.toggle-status');
    Route::get('siswa/{siswa}/qr-card', [SiswaController::class, 'qrCard'])->name('siswa.qr-card');
    Route::get('siswa/{siswa}/qr-image', [SiswaController::class, 'qrImage'])->name('siswa.qr-image');
    Route::post('siswa/{siswa}/regenerate-qr', [SiswaController::class, 'regenerateQr'])->name('siswa.regenerate-qr');
    Route::get('siswa/qr-massal', [QrMassalController::class, 'index'])->name('siswa.qr-massal');
    Route::post('siswa/qr-massal/regenerate', [QrMassalController::class, 'regenerate'])->name('siswa.qr-massal.regenerate');
    Route::post('siswa/qr-massal/download', [QrMassalController::class, 'downloadPdf'])->name('siswa.qr-massal.download');
    Route::get('siswa/import', [SiswaImportController::class, 'form'])->name('siswa.import.form');
    Route::get('siswa/import/template', [SiswaImportController::class, 'template'])->name('siswa.import.template');
    Route::post('siswa/import', [SiswaImportController::class, 'import'])->name('siswa.import.store');
    Route::get('pengaturan', [PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
});

    Route::middleware(['auth', 'role:admin|guru'])->group(function () {
    Route::get('presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('presensi/scan', [PresensiController::class, 'scanner'])->name('presensi.scanner');
    Route::post('presensi/proses-scan', [PresensiController::class, 'prosesScan'])
        ->middleware('throttle:90,1')
        ->name('presensi.proses-scan');
    Route::get('presensi/manual', [PresensiController::class, 'manual'])->name('presensi.manual');
    Route::post('presensi/manual', [PresensiController::class, 'simpanManual'])->name('presensi.manual.store');
    Route::post('presensi/manual-single', [PresensiController::class, 'manualSingle'])->name('presensi.manual-single');
    Route::get('laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
    Route::get('laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    Route::get('laporan/harian/pdf', [LaporanController::class, 'harianPdf'])->name('laporan.harian.pdf');
    Route::get('laporan/bulanan/excel', [LaporanController::class, 'bulananExcel'])->name('laporan.bulanan.excel');
});

require __DIR__ . '/auth.php';
