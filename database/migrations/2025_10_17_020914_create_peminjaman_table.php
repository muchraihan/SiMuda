<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa');
            $table->foreignId('id_buku')->constrained('buku', 'id_buku');
            $table->timestamp('tgl_pengajuan')->useCurrent();
            $table->date('tgl_pinjam')->nullable();
            $table->date('tgl_kembali_maksimal')->nullable();
            $table->date('tgl_pengembalian_aktual')->nullable();
            $table->enum('status', ['diajukan', 'dipinjam', 'dikembalikan', 'terlambat', 'ditolak']);
            $table->boolean('notifikasi_terkirim')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
