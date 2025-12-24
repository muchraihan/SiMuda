<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KatalogBukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DendaController; // Pastikan import ini ada

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- HALAMAN UTAMA (GUEST) ---
Route::get('/', function () {
    return view('welcome');
});

// --- AUTHENTICATED ROUTES (UMUM) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Umum (Akan disesuaikan view-nya di Controller/View berdasarkan role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Akun (User)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// --- GROUP SISWA ---
Route::middleware(['auth'])->group(function () {

    // 1. Lengkapi Data Diri (Awal)
    Route::get('/lengkapi-profil', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('/lengkapi-profil', [SiswaController::class, 'store'])->name('siswa.store');

    // 2. Profil Kesiswaan (Edit Sendiri)
    Route::get('/profil-saya', [SiswaController::class, 'editSaya'])->name('siswa.profil.edit');
    Route::put('/profil-saya', [SiswaController::class, 'updateSaya'])->name('siswa.profil.update');

    // 3. Katalog Buku
    Route::get('/katalog', [KatalogBukuController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/{id}', [KatalogBukuController::class, 'show'])->name('katalog.show');

    // 4. Transaksi Peminjaman
    Route::post('/buku/ajukan/{id_buku}', [PeminjamanController::class, 'ajukan'])->name('peminjaman.ajukan');
    Route::get('/riwayatbuku', [PeminjamanController::class, 'riwayat'])->name('siswa.riwayatpeminjaman.index');

});

// --- GROUP PUSTAKAWAN (ADMIN) ---
Route::middleware(['auth', 'peran:pustakawan'])->prefix('pustakawan')->group(function () {

    // 1. Dashboard Khusus Pustakawan
    // (Note: Sebenarnya dashboard umum di atas sudah cukup jika controller handle logicnya, 
    // tapi route ini tetap saya pertahankan sesuai request agar tidak error)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pustakawan.dashboard');

    // 2. Manajemen Siswa
    Route::get('/kelolasiswa', [SiswaController::class, 'index'])->name('pustakawan.siswa.index');
    Route::get('/kelolasiswa/{id_siswa}/edit', [SiswaController::class, 'edit'])->name('pustakawan.siswa.edit');
    Route::put('/kelolasiswa/{id_siswa}', [SiswaController::class, 'update'])->name('pustakawan.siswa.update');
    Route::delete('/kelolasiswa/{id_siswa}', [SiswaController::class, 'destroy'])->name('pustakawan.siswa.destroy');
    Route::get('/kelolasiswa/{id_siswa}', [SiswaController::class, 'show'])->name('pustakawan.siswa.show'); // Tambahan route detail siswa jika ada

    // 3. Manajemen Buku (CRUD)
    Route::get('/buku', [BukuController::class, 'index'])->name('pustakawan.buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('pustakawan.buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('pustakawan.buku.store');
    Route::get('/buku/{id_buku}/edit', [BukuController::class, 'edit'])->name('pustakawan.buku.edit');
    Route::put('/buku/{id_buku}', [BukuController::class, 'update'])->name('pustakawan.buku.update');
    Route::delete('/buku/{id_buku}', [BukuController::class, 'destroy'])->name('pustakawan.buku.destroy');

    // 4. Manajemen Peminjaman (Approval & Sirkulasi)
    Route::get('/peminjaman', [PeminjamanController::class, 'indexPustakawan'])->name('pustakawan.peminjaman');
    Route::patch('/peminjaman/{id_peminjaman}/acc', [PeminjamanController::class, 'setujui'])->name('peminjaman.acc');
    Route::patch('/peminjaman/{id_peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    Route::patch('/peminjaman/{id_peminjaman}/kembali', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

    // 5. Laporan & PDF
    Route::get('/laporan', [PeminjamanController::class, 'laporan'])->name('pustakawan.laporan');
    Route::get('/laporan/cetak', [PeminjamanController::class, 'cetakPdf'])->name('pustakawan.laporan.cetak');

    // 6. Manajemen Denda
    Route::get('/denda', [DendaController::class, 'index'])->name('pustakawan.denda.index');
    Route::patch('/denda/{id_denda}/lunas', [DendaController::class, 'lunas'])->name('pustakawan.denda.lunas');

});

// Auth Routes (Login, Register, dll)
require __DIR__.'/auth.php';