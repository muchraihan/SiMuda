<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendOverdueNotification extends Command
{
    protected $signature = 'notifikasi:kirim';

    protected $description = 'Kirim notifikasi WA ke siswa yang terlambat mengembalikan buku';

    public function handle()
    {
        // Log penanda mulai (Cek storage/logs/laravel.log)
        Log::info('🤖 BOT WA: Memulai pengecekan keterlambatan...');

        $terlambat = Peminjaman::with(['siswa.user', 'buku'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereDate('tgl_kembali_maksimal', '<', Carbon::now())
            ->where('notifikasi_terkirim', false)
            ->get();

        if ($terlambat->isEmpty()) {
            Log::info('✅ BOT WA: Tidak ada siswa yang perlu dinotifikasi hari ini.');
            return;
        }

        $total = $terlambat->count();
        Log::info("found: Ditemukan $total siswa terlambat. Memulai pengiriman...");

        foreach ($terlambat as $index => $item) {
            
            try {
                // 1. Format Nomor
                $target = $item->siswa->nomor_whatsapp;
                $target = preg_replace('/[^0-9]/', '', $target);
                if (substr($target, 0, 1) === '0') $target = '62' . substr($target, 1);

                // 2. Siapkan Data
                $nama = $item->siswa->user->name;
                $buku = $item->buku->judul;
                $tgl  = Carbon::parse($item->tgl_kembali_maksimal)->translatedFormat('d F Y');
                
                // Hitung Telat & Denda
                $telat = Carbon::now()->diffInDays($item->tgl_kembali_maksimal);
                $telatFormatted = number_format($telat);
                $denda = $telat * 1000; 
                $dendaFormatted = number_format($denda, 0, ',', '.');

                $pesan = "*PERINGATAN DENDA & KETERLAMBATAN* ⚠️\n\n"
                       . "Halo *$nama*,\n"
                       . "Masa peminjaman buku Anda telah HABIS.\n\n"
                       . "📚 Buku: *$buku*\n"
                       . "📅 Jatuh Tempo: *$tgl*\n"
                       . "❗ Telat: *$telat Hari*\n"
                       . "💰 *Estimasi Denda: Rp $dendaFormatted*\n\n"
                       . "Mohon segera kembalikan buku ke perpustakaan.\n"
                       . "_SiMuda Library_";

                // 3. Kirim WA
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => env('FONTEE_TOKEN'),
                ])->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $pesan,
                    'countryCode' => '62', 
                ]);

                if ($response->successful()) {
                    Log::info("✅ BOT WA: Terkirim ke $nama");
                    
                    $item->update([
                        'status' => 'terlambat',
                        'notifikasi_terkirim' => true 
                    ]);

                    // Jeda Waktu (Agar tidak dianggap spam)
                    if ($index < $total - 1) {
                        $jeda = rand(4, 8); 
                        sleep($jeda); 
                    }
                    
                } else {
                    Log::error("❌ BOT WA: Gagal kirim ke $nama. API Response: " . $response->body());
                }

            } catch (\Exception $e) {
                Log::error("❌ BOT WA: Error Koneksi - " . $e->getMessage());
            }
        }

        Log::info('🏁 BOT WA: Selesai.');
    }
}