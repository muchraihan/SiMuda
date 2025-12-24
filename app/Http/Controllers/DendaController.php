<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda; // Pastikan Model Denda diimport
use Carbon\Carbon;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil data denda beserta relasi ke peminjaman -> siswa -> user & buku
        $dataDenda = Denda::with(['peminjaman.siswa.user', 'peminjaman.buku'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('peminjaman.siswa.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('peminjaman.buku', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc') // Tampilkan yang terbaru dulu
            ->paginate(10);

        return view('pustakawan.denda', compact('dataDenda'));
    }

    // Method untuk mengubah status jadi Lunas
    public function lunas($id_denda)
    {
        $denda = Denda::findOrFail($id_denda);
        
        $denda->update([
            'status_pembayaran' => 'lunas'
        ]);

        return back()->with('success', 'Status denda berhasil diubah menjadi Lunas.');
    }
}