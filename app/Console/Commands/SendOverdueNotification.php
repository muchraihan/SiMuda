<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SendOverdueNotification extends Command
{
    /**
     * Nama perintah yang akan kita panggil nanti.
     */
    protected $signature = 'notifikasi:terlambat';

    /**
     * Deskripsi perintah.
     */
    protected $description = 'Kirim notifikasi WA ke siswa yang terlambat mengembalikan buku';

    /**
     * Eksekusi perintah.
     */
public function handle()
    {
        $this->info('🚀 Memulai proses pengecekan keterlambatan...');

        $terlambat = Peminjaman::with(['siswa.user', 'buku'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereDate('tgl_kembali_maksimal', '<', Carbon::now())
            ->where('notifikasi_terkirim', false)
            ->get();

        if ($terlambat->isEmpty()) {
            $this->info('✅ Tidak ada siswa yang perlu dinotifikasi hari ini.');
            return;
        }

        $total = $terlambat->count();
        $this->info("Ditemukan $total siswa terlambat. Memulai pengiriman...");

        foreach ($terlambat as $index => $item) {
            
            // ... (Kode format nomor & pesan sama seperti sebelumnya) ...
            $target = $item->siswa->nomor_whatsapp;
            $target = preg_replace('/[^0-9]/', '', $target);
            if (substr($target, 0, 1) === '0') $target = '62' . substr($target, 1);

            $nama = $item->siswa->user->name;
            $buku = $item->buku->judul;
            $tgl  = Carbon::parse($item->tgl_kembali_maksimal)->translatedFormat('d F Y');
            
            // Hitung Telat & Denda
            $telat = Carbon::now()->diffInDays($item->tgl_kembali_maksimal);
            $denda = $telat * 1000; // Kalkulasi Rp 1.000 x Hari
            $dendaFormatted = number_format($denda, 0, ',', '.');

            $pesan = "*PERINGATAN DENDA & KETERLAMBATAN* ⚠️\n\n"
                   . "Halo *$nama*,\n"
                   . "Masa peminjaman buku Anda telah HABIS.\n\n"
                   . "📚 Buku: *$buku*\n"
                   . "📅 Jatuh Tempo: *$tgl*\n"
                   . "❗ Telat: *$telat Hari*\n"
                   . "💰 *Denda: Rp $dendaFormatted*\n\n"
                   . "Mohon segera kembalikan buku ke perpustakaan untuk menghindari denda yang semakin besar.\n"
                   . "_SiMuda Library_";

            // Kirim WA
            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => env('FONTEE_TOKEN'),
                ])->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $pesan,
                    'countryCode' => '62', 
                ]);

                if ($response->successful()) {
                    $this->info("[" . ($index + 1) . "/$total] ✅ Terkirim ke: $nama");
                    
                    $item->update([
                        'status' => 'terlambat',
                        'notifikasi_terkirim' => true 
                    ]);

                    // ========================================================
                    // [UPDATE] JEDA WAKTU ACAK (HUMAN LIKE BEHAVIOR)
                    // ========================================================
                    // Jika ini bukan pesan terakhir, kita istirahat dulu
                    if ($index < $total - 1) {
                        // Acak waktu antara 4 sampai 8 detik
                        $jeda = rand(4, 8); 
                        
                        $this->info("   ☕ Mengetik pesan selanjutnya... (Jeda $jeda detik)");
                        sleep($jeda); 
                    }
                    
                } else {
                    $this->error("❌ Gagal kirim ke: $nama. API Error: " . $response->body());
                }

            } catch (\Exception $e) {
                $this->error("❌ Koneksi Error: " . $e->getMessage());
            }
        }

        $this->info('🏁 Semua notifikasi selesai dikirim.');
    }
}