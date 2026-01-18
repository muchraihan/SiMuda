<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendOverdueNotification extends Command
{
    /**
     * Nama perintah yang akan diketik di terminal
     * Pastikan ini sama dengan yang ada di routes/console.php
     */
    protected $signature = 'notifikasi:kirim';

    protected $description = 'Kirim notifikasi WA harian ke siswa yang terlambat';

    public function handle()
    {
        // 1. Cek Token Dulu
        $token = env('FONTEE_TOKEN');
        if (empty($token)) {
            $this->error('❌ FONTEE_TOKEN belum diisi di file .env');
            return;
        }

        $this->info('🚀 Memulai proses pengecekan keterlambatan...');

        // 2. Ambil Data Terlambat
        // Syarat: Status dipinjam/terlambat DAN Tanggal Kembali < Hari Ini
        $terlambat = Peminjaman::with(['siswa.user', 'buku'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereDate('tgl_kembali_maksimal', '<', Carbon::now()->format('Y-m-d')) 
            ->get();

        if ($terlambat->isEmpty()) {
            $this->info('✅ Tidak ada siswa yang perlu dinotifikasi hari ini (Semua tepat waktu).');
            return;
        }

        $total = $terlambat->count();
        $this->info("Found: Ditemukan $total siswa terlambat. Memulai pengiriman...");

        foreach ($terlambat as $index => $item) {
            
            try {
                // A. Format Nomor WA (Pastikan 62)
                $target = $item->siswa->nomor_whatsapp;
                $target = preg_replace('/[^0-9]/', '', $target); // Hapus karakter non-angka
                if (substr($target, 0, 1) === '0') {
                    $target = '62' . substr($target, 1);
                }

                // B. Siapkan Data Pesan
                $nama = $item->siswa->user->name;
                $buku = $item->buku->judul;
                $tgl  = Carbon::parse($item->tgl_kembali_maksimal)->translatedFormat('d F Y');
                
                // --- PERBAIKAN DI SINI ---
                // Gunakan abs() untuk memastikan nilai positif (menghilangkan minus)
                // floor() untuk membulatkan ke bawah atau ceil() ke atas, tapi diffInDays biasanya sudah int/float bersih
                // Urutan parameter: tgl_kembali_maksimal dibandingkan dengan SEKARANG
                
                $tglKembali = Carbon::parse($item->tgl_kembali_maksimal);
                $sekarang = Carbon::now();

                // Hitung selisih hari secara absolut (positif)
                $telat = abs($tglKembali->diffInDays($sekarang, false)); // false agar bisa negatif, lalu di-abs-kan
                
                // Pastikan minimal 1 hari (jika selisih jam masih dianggap 0)
                $telat = $telat < 1 ? 1 : round($telat); // round() menghilangkan desimal
                
                $denda = $telat * 1000; 
                $dendaFormatted = number_format($denda, 0, ',', '.'); // Format Rupiah tanpa desimal

                $pesan = "*PENGINGAT HARIAN* 🔔\n\n"
                       . "Halo *$nama*,\n"
                       . "Ini adalah pengingat otomatis bahwa buku yang Anda pinjam:\n\n"
                       . "📚 Judul: *$buku*\n"
                       . "📅 Jatuh Tempo: *$tgl*\n"
                       . "❗ Telat: *" . number_format($telat, 0, ',', '.') . " Hari*\n"
                       . "💰 *Total Denda: Rp $dendaFormatted*\n\n"
                       . "Mohon **SEGERA** kembalikan buku ke perpustakaan.\n"
                       . "_SiMuda Library_";

                // C. Kirim Request ke Fontee
                $this->line("   📤 Mengirim ke $nama ($target)...");
                
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $pesan,
                    'countryCode' => '62', 
                ]);

                // D. Cek Hasil
                if ($response->successful()) {
                    $this->info("   ✅ Berhasil terkirim!");
                    
                    // Update status di database jika belum 'terlambat'
                    if ($item->status !== 'terlambat') {
                        $item->update(['status' => 'terlambat']);
                    }
                } else {
                    $this->error("   ❌ Gagal kirim. Response: " . $response->body());
                }

            } catch (\Exception $e) {
                $this->error("   ❌ Error Sistem: " . $e->getMessage());
            }

            // E. Jeda Waktu (PENTING: Agar tidak diblokir WA)
            if ($index < $total - 1) {
                $jeda = rand(4, 7); 
                $this->comment("   ⏳ Jeda $jeda detik sebelum pesan berikutnya...");
                sleep($jeda); 
            }
        }

        $this->info('🏁 Selesai. Semua antrian telah diproses.');
    }
}