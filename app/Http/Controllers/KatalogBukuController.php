<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class KatalogBukuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori'); // [1] Ambil input kategori
        $perPage = $request->input('per_page', 10);

        $books = Buku::query()
            // Logika Pencarian Teks
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('tahun_terbit', 'like', "%{$search}%");
                });
            })
            // [2] Logika Filter Kategori (BARU)
            ->when($kategori, function ($query, $kategori) {
                $query->where('kategori', $kategori);
            })
            ->paginate($perPage)
            // [3] Pastikan parameter tetap ada saat ganti halaman
            ->appends(['search' => $search, 'kategori' => $kategori, 'per_page' => $perPage]);

        return view('siswa.katalogbuku', compact('books'));
    }

    public function show($id)
    {
        $book = Buku::findOrFail($id);
        return view('siswa.show', compact('book'));
    }
}