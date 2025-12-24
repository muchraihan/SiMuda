<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    // Tampilkan Form Lengkapi Data
    public function create()
    {
        // Cek jika sudah punya data, langsung lempar ke halaman lain (misal dashboard/katalog)
        if (Auth::user()->siswa) {
            return redirect()->route('katalog.index')->with('info', 'Data diri Anda sudah lengkap.');
        }

        return view('siswa.lengkapi_data');
    }

    // Simpan Data ke Database
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'kelas' => 'required',
            'nomor_whatsapp' => 'required|numeric',
            'alamat' => 'required',
        ]);

        // 2. Simpan ke Tabel Siswa
        Siswa::create([
            'user_id' => Auth::id(), // Ambil ID User yang sedang login
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'alamat' => $request->alamat,
        ]);

        // 3. Redirect Balik ke Katalog agar bisa pinjam
        return redirect()->route('katalog.index')->with('success', 'Data diri berhasil disimpan! Sekarang Anda bisa meminjam buku.');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $siswa = Siswa::with('user') // Ambil data user (nama/email) sekalian
            ->when($search, function ($query, $search) {
                return $query->where('nis', 'like', "%{$search}%")
                             ->orWhere('kelas', 'like', "%{$search}%")
                             ->orWhereHas('user', function ($q) use ($search) {
                                 // Cari berdasarkan nama di tabel users
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->orderBy('kelas', 'asc') // Urutkan berdasarkan kelas
            ->paginate(10); // Batasi 10 baris per halaman

        return view('pustakawan.siswa', compact('siswa'));
    }

    public function edit($id_siswa)
    {
        $siswa = Siswa::with('user')->findOrFail($id_siswa);
        return view('pustakawan.siswa_edit', compact('siswa'));
    }

    // 2. PROSES UPDATE DATA
    public function update(Request $request, $id_siswa)
    {
        $siswa = Siswa::findOrFail($id_siswa);
        $user  = $siswa->user; // Ambil data user terkait

        // Validasi
        $request->validate([
            'name'           => 'required|string|max:255',
            // Validasi unik email kecuali milik user ini sendiri
            'email'          => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            // Validasi unik NIS kecuali milik siswa ini sendiri
            'nis'            => 'required|numeric|unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa',
            'kelas'          => 'required|string',
            'nomor_whatsapp' => 'required|numeric',
            'alamat'         => 'required|string',
        ]);

        // Update Tabel Users (Nama & Email)
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Update Tabel Siswa (Data diri)
        $siswa->update([
            'nis'            => $request->nis,
            'kelas'          => $request->kelas,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'alamat'         => $request->alamat,
        ]);

        return redirect()->route('pustakawan.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    // 3. HAPUS SISWA
    public function destroy($id_siswa)
    {
        $siswa = Siswa::findOrFail($id_siswa);
        $user  = $siswa->user;

        // Hapus User (Karena Cascade On Delete di database, data di tabel siswa otomatis ikut terhapus)
        // Jika tidak setting cascade di migration, hapus $siswa->delete() dulu baru $user->delete()
        if ($user) {
            $user->delete();
        } else {
            // Jika data user sudah hilang duluan (jarang terjadi), hapus siswanya saja
            $siswa->delete();
        }

        return redirect()->back()->with('success', 'Data siswa dan akun berhasil dihapus.');
    }

    public function editSaya()
    {
        $user = Auth::user();
        
        // Jika belum punya data siswa, lempar ke form lengkapi data awal
        if (!$user->siswa) {
            return redirect()->route('siswa.create');
        }

        $siswa = $user->siswa;
        return view('siswa.profil_saya', compact('siswa'));
    }

    // 2. Simpan Perubahan Data Diri
    public function updateSaya(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $request->validate([
            'kelas'          => 'required|string',
            'nomor_whatsapp' => 'required|numeric',
            'alamat'         => 'required|string',
            // NIS biasanya tidak boleh diubah sembarangan oleh siswa, jadi tidak divalidasi/diupdate
        ]);

        $siswa->update([
            'kelas'          => $request->kelas,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'alamat'         => $request->alamat,
        ]);

        return back()->with('success', 'Data profil kesiswaan berhasil diperbarui.');
    }
}