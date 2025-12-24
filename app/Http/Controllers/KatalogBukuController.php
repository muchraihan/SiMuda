<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class KatalogBukuController extends Controller
{
    public function index(Request $request)
    {
        // Fitur pencarian (opsional)
        $search = $request->input('search');

        $books = Buku::query()
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('tahun_terbit', 'like', "%{$search}%");
            })
            ->paginate();

        return view('siswa.katalogbuku', compact('books'));
    }

    public function show($id)
    {
        // Mengambil data buku berdasarkan ID, jika tidak ada akan error 404
        $book = \App\Models\Buku::findOrFail($id);

        // Mengirim data buku ke view detail
        return view('siswa.show', compact('book'));
    }
}
