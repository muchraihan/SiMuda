<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('buku') && !Schema::hasColumn('buku', 'kategori')) {
            Schema::table('buku', function (Blueprint $table) {
                // Menambahkan kolom kategori setelah judul, nullable agar data lama aman
                $table->string('kategori', 50)->nullable()->after('judul');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('buku') && Schema::hasColumn('buku', 'kategori')) {
            Schema::table('buku', function (Blueprint $table) {
                $table->dropColumn('kategori');
            });
        }
    }
};