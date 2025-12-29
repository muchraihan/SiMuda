<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $perPage = $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;

        $buku = Buku::when($search, function ($query, $search) {
            $query->where('judul', 'like', "%{$search}%")
                ->orWhere('penulis', 'like', "%{$search}%")
                ->orWhere('penerbit', 'like', "%{$search}%");
        })->paginate($perPage)->appends(['per_page' => $perPage, 'search' => $search]);

        return view('pustakawan.buku.kelolabuku', compact('buku', 'search', 'perPage'));
    }

    /**
     * Menampilkan form tambah buku.
     */
    public function create()
    {
        return view('pustakawan.buku.create');
    }

    /**
     * Simpan data buku baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'nullable|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20|unique:buku,isbn',
            'jumlah_stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'rak' => 'nullable|string|max:50',
            'url_sampul' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload gambar
        $path = $request->hasFile('url_sampul')
            ? $request->file('url_sampul')->store('sampul_buku', 'public')
            : null;

        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'isbn' => $request->isbn,
            'jumlah_stok' => $request->jumlah_stok,
            'deskripsi' => $request->deskripsi,
            'rak' => $request->rak,
            'url_sampul' => $path,
        ]);

        return redirect()->route('pustakawan.buku.index', request()->only(['search', 'per_page']))->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit($id_buku)
    {
        $buku = Buku::findOrFail($id_buku);
        return view('pustakawan.buku.update', compact('buku'));
    }

    /**
     * Update data buku.
     */
    public function update(Request $request, $id_buku)
    {
        $buku = Buku::findOrFail($id_buku);

        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'nullable|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20|unique:buku,isbn,' . $buku->id_buku . ',id_buku',
            'jumlah_stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'rak' => 'nullable|string|max:50',
            'url_sampul' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update gambar
        if ($request->hasFile('url_sampul')) {
            if ($buku->url_sampul) {
                Storage::disk('public')->delete($buku->url_sampul);
            }
            $buku->url_sampul = $request->file('url_sampul')->store('sampul_buku', 'public');
        }

        $buku->update($request->only([
            'judul',
            'penulis',
            'penerbit',
            'tahun_terbit',
            'isbn',
            'jumlah_stok',
            'deskripsi',
            'rak',
        ]));

        return redirect()->route('pustakawan.buku.index', request()->only(['search', 'per_page']))->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Hapus buku.
     */
    public function destroy($id_buku)
    {
        $buku = Buku::findOrFail($id_buku);

        if ($buku->url_sampul) {
            Storage::disk('public')->delete($buku->url_sampul);
        }

        $buku->delete();
        return redirect()->route('pustakawan.buku.index', request()->only(['search', 'per_page']))->with('success', 'Buku berhasil dihapus!');
    }
}
