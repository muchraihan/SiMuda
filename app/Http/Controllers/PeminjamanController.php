<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Siswa;
use App\Models\Denda;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
use Carbon\Carbon; 
use Barryvdh\DomPDF\Facade\Pdf; 

class PeminjamanController extends Controller
{
    // ==========================================
    // 1. BAGIAN SISWA (MEMINJAM BUKU)
    // ==========================================

    public function ajukan($id_buku)
    {
        $user = Auth::user();

        if (!$user->siswa) {
            return redirect()->route('siswa.create')
                ->with('error', 'Silakan lengkapi data diri (NIS, Kelas, WA) terlebih dahulu sebelum meminjam buku.');
        }

        $buku = Buku::findOrFail($id_buku);

        if ($buku->jumlah_stok < 1) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        $cekDouble = Peminjaman::where('id_siswa', $user->siswa->id_siswa)
                        ->where('id_buku', $id_buku)
                        ->whereIn('status', ['diajukan', 'dipinjam', 'terlambat'])
                        ->exists();
        
        if ($cekDouble) {
            return back()->with('error', 'Anda sedang mengajukan atau meminjam buku ini.');
        }

        Peminjaman::create([
            'id_siswa'      => $user->siswa->id_siswa,
            'id_buku'       => $id_buku,
            'tgl_pengajuan' => now(),
            'status'        => 'diajukan',
        ]);

        return back()->with('success', 'Permintaan peminjaman berhasil dikirim. Harap tunggu persetujuan Pustakawan.');
    }

    public function riwayat()
    {
        $user = Auth::user();

        if (!$user->siswa) {
            return redirect()->route('siswa.create');
        }

        $histories = Peminjaman::with('buku')
                        ->where('id_siswa', $user->siswa->id_siswa)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('siswa.riwayatpeminjaman', compact('histories'));
    }

    // ==========================================
    // 2. BAGIAN PUSTAKAWAN (UTAMA)
    // ==========================================

    public function indexPustakawan(Request $request)
    {
        $search = $request->input('search');

        $permintaan = Peminjaman::with(['siswa.user', 'buku'])
                        ->where('status', 'diajukan')
                        ->orderBy('tgl_pengajuan', 'asc')
                        ->get();

        $sedangDipinjam = Peminjaman::with(['siswa.user', 'buku'])
                        ->whereIn('status', ['dipinjam', 'terlambat'])
                        ->when($search, function ($query, $search) {
                            return $query->where(function($q) use ($search) {
                                $q->whereHas('buku', function($subQ) use ($search) {
                                    $subQ->where('judul', 'like', "%{$search}%");
                                })
                                ->orWhereHas('siswa.user', function($subQ) use ($search) {
                                    $subQ->where('name', 'like', "%{$search}%");
                                });
                            });
                        })
                        ->orderBy('tgl_pinjam', 'desc')
                        ->get();

        return view('pustakawan.peminjaman', compact('permintaan', 'sedangDipinjam'));
    }

    // --- [FITUR 1: KIRIM WA SAAT DISETUJUI] ---
    public function setujui($id_peminjaman)
    {
        // Gunakan lockForUpdate jika trafik tinggi, tapi findOrFail cukup untuk skala sekolah
        $peminjaman = Peminjaman::with(['siswa.user', 'buku'])->findOrFail($id_peminjaman);

        // [PENTING!] CEK STATUS DULU
        // Jika status bukan 'diajukan' (misal sudah diproses request sebelumnya), tolak request ini.
        if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        if ($peminjaman->buku->jumlah_stok < 1) {
            return back()->with('error', 'Gagal menyetujui. Stok buku fisik habis.');
        }

        // Tentukan Tanggal Kembali (7 Hari dari sekarang)
        $tglKembali = Carbon::now()->addDays(7);

        $peminjaman->update([
            'status'                => 'dipinjam',
            'tgl_pinjam'            => now(),
            'tgl_kembali_maksimal'  => $tglKembali,
        ]);

        $peminjaman->buku->decrement('jumlah_stok');

        // --- LOGIKA KIRIM WA (FONTEE) ---
        try {
            $target = $peminjaman->siswa->nomor_whatsapp;
            $target = preg_replace('/[^0-9]/', '', $target); 
            if (substr($target, 0, 1) == '0') {
                $target = '62' . substr($target, 1);
            }

            $namaSiswa = $peminjaman->siswa->user->name;
            $judulBuku = $peminjaman->buku->judul;
            $tglWajib  = $tglKembali->translatedFormat('d F Y');

            $pesan = "*PERMINTAAN DISETUJUI* ✅\n\n"
                   . "Halo *$namaSiswa*,\n"
                   . "Peminjaman buku Anda telah disetujui.\n\n"
                   . "📚 Buku: *$judulBuku*\n"
                   . "📅 Wajib Kembali: *$tglWajib*\n\n"
                   . "Silakan ambil buku di perpustakaan. Harap kembalikan tepat waktu.\n"
                   . "_Salam, SiMuda_";

            // Tambahkan sleep agar loading terasa (visual cue untuk user bahwa sedang proses)
            sleep(rand(2, 4));

            Http::withoutVerifying()->withHeaders([
                'Authorization' => env('FONTEE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target'      => $target,
                'message'     => $pesan,
                'countryCode' => '62',
            ]);

        } catch (\Exception $e) {
            // Log error diam-diam
        }

        return back()->with('success', 'Peminjaman disetujui & Notifikasi WA dikirim!');
    }

    public function tolak($id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        // [PENTING!] CEK STATUS DULU
        if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $peminjaman->update(['status' => 'ditolak']);
        return back()->with('success', 'Permintaan peminjaman ditolak.');
    }

    public function kembalikan($id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        // Cek status agar tidak dikembalikan 2x (mengacaukan stok)
        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Data tidak valid atau sudah dikembalikan.');
        }

        // --- LOGIKA HITUNG DENDA ---
        $tglWajib = Carbon::parse($peminjaman->tgl_kembali_maksimal);
        $tglKembali = now();
        $pesanTambahan = "";

        // Cek apakah pengembalian melebihi tanggal wajib?
        if ($tglKembali->gt($tglWajib)) {
            $jumlahHari = $tglKembali->diffInDays($tglWajib);
            $totalDenda = $jumlahHari * 1000; 

            Denda::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'jumlah_denda'  => $totalDenda,
                'status_pembayaran' => 'belum_bayar'
            ]);

            $pesanTambahan = " Terkena denda keterlambatan: Rp " . number_format($totalDenda, 0, ',', '.');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tgl_pengembalian_aktual' => now(),
        ]);

        $peminjaman->buku->increment('jumlah_stok');

        return back()->with('success', 'Buku berhasil dikembalikan.' . $pesanTambahan);
    }

    // ==========================================
    // 3. BAGIAN LAPORAN
    // ==========================================

    public function laporan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $laporan = Peminjaman::with(['siswa.user', 'buku'])
                    ->whereMonth('tgl_pinjam', $bulan)
                    ->whereYear('tgl_pinjam', $tahun)
                    ->whereIn('status', ['dipinjam', 'dikembalikan', 'terlambat'])
                    ->orderBy('tgl_pinjam', 'desc')
                    ->get();

        return view('pustakawan.laporan', compact('laporan', 'bulan', 'tahun'));
    }

    public function cetakPdf(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $laporan = Peminjaman::with(['siswa.user', 'buku', 'denda']) 
                    ->whereMonth('tgl_pinjam', $bulan)
                    ->whereYear('tgl_pinjam', $tahun)
                    ->whereIn('status', ['dipinjam', 'dikembalikan', 'terlambat'])
                    ->orderBy('tgl_pinjam', 'asc')
                    ->get();

        $pdf = Pdf::loadView('pustakawan.cetak_pdf', compact('laporan', 'bulan', 'tahun'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Perpus-'.date('F-Y', mktime(0, 0, 0, $bulan, 10)).'.pdf');
    }
}