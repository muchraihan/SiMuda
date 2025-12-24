<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Siswa;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth; // <--- Jangan lupa import Auth

class DashboardController extends Controller
{
    public function index()
    {
        // --- DATA UMUM (GLOBAL) ---
        $jumlahBuku = Buku::count();
        $jumlahSiswa = Siswa::count();
        $jumlahPeminjaman = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count();
        
        $jumlahTerlambat = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])
                            ->where('tgl_kembali_maksimal', '<', now())
                            ->count();

        // --- DATA KHUSUS SISWA (RIWAYAT SAYA) ---
        $riwayatSaya = 0;
        if (Auth::user()->peran == 'siswa' && Auth::user()->siswa) {
            // Hitung semua transaksi milik siswa ini (active & history)
            $riwayatSaya = Peminjaman::where('id_siswa', Auth::user()->siswa->id_siswa)->count();
        }

        return view('dashboard', compact(
            'jumlahBuku', 
            'jumlahSiswa', 
            'jumlahPeminjaman', 
            'jumlahTerlambat',
            'riwayatSaya' // <--- Kirim variabel baru ini
        ));
    }
}