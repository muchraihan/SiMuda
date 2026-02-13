<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User; // Diperlukan untuk hapus user
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel; // Import Excel
use App\Imports\SiswaImport;         // Import Class SiswaImport

class SiswaController extends Controller
{
    // ==========================================
    // BAGIAN PUSTAKAWAN (ADMIN)
    // ==========================================

    // 1. Tampilkan Daftar Siswa (Index)
    public function index(Request $request)
    {
        $search = $request->input('search');

        $siswa = Siswa::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('nis', 'like', "%{$search}%")
                             ->orWhere('kelas', 'like', "%{$search}%")
                             ->orWhereHas('user', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->orderBy('kelas', 'asc')
            ->paginate(10);

        return view('pustakawan.siswa', compact('siswa'));
    }

    // 2. Tampilkan Detail Siswa (INI YANG KURANG KEMARIN)
    public function show($id_siswa)
    {
        $siswa = Siswa::with('user')->findOrFail($id_siswa);
        return view('pustakawan.siswa_show', compact('siswa'));
    }

    // 3. Form Edit Siswa (Admin)
    public function edit($id_siswa)
    {
        $siswa = Siswa::with('user')->findOrFail($id_siswa);
        return view('pustakawan.siswa_edit', compact('siswa'));
    }

    // 4. Update Data Siswa (Admin)
    public function update(Request $request, $id_siswa)
    {
        $siswa = Siswa::findOrFail($id_siswa);
        $user  = $siswa->user;

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'nis'            => 'required|numeric|unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa',
            'kelas'          => 'required|string',
            'nomor_whatsapp' => 'required|numeric',
            'alamat'         => 'required|string',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $siswa->update([
            'nis'            => $request->nis,
            'kelas'          => $request->kelas,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'alamat'         => $request->alamat,
        ]);

        return redirect()->route('pustakawan.siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    // 5. Hapus Siswa (Admin)
    public function destroy($id_siswa)
    {
        $siswa = Siswa::findOrFail($id_siswa);
        $user  = $siswa->user;

        if ($user) {
            $user->delete(); // Data siswa otomatis terhapus karena relasi/cascade atau soft delete
        } else {
            $siswa->delete();
        }

        return redirect()->back()->with('success', 'Data siswa dan akun berhasil dihapus.');
    }

    // 6. Import Excel (View)
    public function importView()
    {
        return view('pustakawan.siswa_import');
    }

    // 7. Import Excel (Proses)
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
            return redirect()->route('pustakawan.siswa.index')->with('success', 'Data siswa berhasil diimport!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $pesanError = "Gagal Import. Baris ke-" . $failures[0]->row() . ": " . $failures[0]->errors()[0];
            return back()->with('error', $pesanError);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    // ==========================================
    // BAGIAN SISWA (MANDIRI)
    // ==========================================

    // 1. Tampilkan Form Lengkapi Data (Pertama Kali)
    public function create()
    {
        if (Auth::user()->siswa) {
            return redirect()->route('katalog.index')->with('info', 'Data diri Anda sudah lengkap.');
        }
        return view('siswa.lengkapi_data');
    }

    // 2. Simpan Data Pertama Kali
    public function store(Request $request)
    {
        $request->validate([
            'nis'            => 'required|unique:siswa,nis',
            'kelas'          => 'required',
            'nomor_whatsapp' => 'required|numeric',
            'alamat'         => 'required',
        ]);

        Siswa::create([
            'user_id'        => Auth::id(),
            'nis'            => $request->nis,
            'kelas'          => $request->kelas,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'alamat'         => $request->alamat,
        ]);

        return redirect()->route('katalog.index')->with('success', 'Data diri berhasil disimpan! Sekarang Anda bisa meminjam buku.');
    }

    // 3. Edit Profil Sendiri (View)
    public function editSaya()
    {
        $user = Auth::user();
        
        if (!$user->siswa) {
            return redirect()->route('siswa.create');
        }

        $siswa = $user->siswa;
        return view('siswa.profil_saya', compact('siswa'));
    }

    // 4. Update Profil Sendiri (Proses)
    public function updateSaya(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $request->validate([
            'kelas'          => 'required|string',
            'nomor_whatsapp' => 'required|numeric',
            'alamat'         => 'required|string',
        ]);

        $siswa->update([
            'kelas'          => $request->kelas,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'alamat'         => $request->alamat,
        ]);

        return back()->with('success', 'Data profil kesiswaan berhasil diperbarui.');
    }
}