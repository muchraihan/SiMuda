<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KirimNotifikasiTerlambat extends Command
{
    /**
     * Nama perintah console
     */
    protected $signature = 'notifikasi:kirim';

    protected $description = 'Kirim notifikasi WA ke siswa yang terlambat (Pengingat Harian)';

    public function handle()
    {
        Log::info('🤖 BOT WA: Memulai pengecekan harian...');

        // 1. Cari SEMUA siswa yang terlambat
        // PERUBAHAN: Saya MENGHAPUS ->where('notifikasi_terkirim', false)
        // Agar pesan dikirim berulang setiap kali perintah ini dijalankan.
        $terlambat = Peminjaman::with(['siswa.user', 'buku'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereDate('tgl_kembali_maksimal', '<', Carbon::now()) // Hanya yang sudah lewat tanggal
            ->get();

        if ($terlambat->isEmpty()) {
            Log::info('✅ BOT WA: Tidak ada siswa yang terlambat hari ini.');
            return;
        }

        $total = $terlambat->count();
        Log::info("found: Ditemukan $total siswa terlambat. Mengirim pengingat harian...");

        foreach ($terlambat as $index => $item) {
            
            try {
                // Format Nomor
                $target = $item->siswa->nomor_whatsapp;
                $target = preg_replace('/[^0-9]/', '', $target);
                if (substr($target, 0, 1) === '0') $target = '62' . substr($target, 1);

                $nama = $item->siswa->user->name;
                $buku = $item->buku->judul;
                $tgl  = Carbon::parse($item->tgl_kembali_maksimal)->translatedFormat('d F Y');
                
                // Hitung Telat & Denda
                $telat = Carbon::now()->diffInDays($item->tgl_kembali_maksimal);
                $denda = $telat * 1000; 
                $dendaFormatted = number_format($denda, 0, ',', '.');

                // Pesan sedikit diubah agar cocok untuk pengingat harian
                $pesan = "*PENGINGAT HARIAN* 🔔\n\n"
                       . "Halo *$nama*,\n"
                       . "Ini adalah pengingat otomatis bahwa buku yang Anda pinjam:\n\n"
                       . "📚 Judul: *$buku*\n"
                       . "📅 Jatuh Tempo: *$tgl*\n"
                       . "❗ Telat: *$telat Hari*\n"
                       . "💰 *Total Denda: Rp $dendaFormatted*\n\n"
                       . "Mohon **SEGERA** kembalikan buku ke perpustakaan.\n"
                       . "Pesan ini akan terus dikirim setiap hari sampai buku dikembalikan.\n\n"
                       . "_SiMuda Library_";

                // Kirim WA
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => env('FONTEE_TOKEN'),
                ])->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $pesan,
                    'countryCode' => '62', 
                ]);

                if ($response->successful()) {
                    Log::info("✅ BOT WA: Pengingat terkirim ke $nama");
                    
                    // Pastikan status diupdate jadi terlambat (jika belum)
                    if ($item->status !== 'terlambat') {
                        $item->update(['status' => 'terlambat']);
                    }
                    
                    // Jeda Waktu Random (Safety Anti-Banned)
                    if ($index < $total - 1) {
                        $jeda = rand(5, 10); // Jeda agak lama sedikit biar aman
                        sleep($jeda); 
                    }
                    
                } else {
                    Log::error("❌ BOT WA: Gagal kirim ke $nama. API: " . $response->body());
                }

            } catch (\Exception $e) {
                Log::error("❌ BOT WA: Error Koneksi - " . $e->getMessage());
            }
        }

        Log::info('🏁 BOT WA: Selesai.');
    }
}