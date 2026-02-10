<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function create()
    {
        if (Auth::user()->siswa) {
            return redirect()->route('katalog.index')->with('info', 'Data diri Anda sudah lengkap.');
        }

        return view('siswa.lengkapi_data');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'kelas' => 'required',
            'nomor_whatsapp' => 'required|numeric',
            'alamat' => 'required',
        ]);

        Siswa::create([
            'user_id' => Auth::id(),
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('katalog.index')->with('success', 'Data diri berhasil disimpan! Sekarang Anda bisa meminjam buku.');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $perPage = $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;

        $siswa = Siswa::with('user') 
            ->when($search, function ($query, $search) {
                return $query->where('nis', 'like', "%{$search}%")
                             ->orWhere('kelas', 'like', "%{$search}%")
                             ->orWhereHas('user', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->orderBy('kelas', 'asc') 
            ->paginate($perPage)->appends(['per_page' => $perPage, 'search' => $search]);

        return view('pustakawan.siswa', compact('siswa', 'search', 'perPage'));
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

        return redirect()->route('pustakawan.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    // 3. HAPUS SISWA
    public function destroy($id_siswa)
    {
        $siswa = Siswa::findOrFail($id_siswa);
        $user  = $siswa->user;

        $siswa->peminjaman()->delete();

        if ($user) {
            $user->delete();
        } else {
            $siswa->delete();
        }

        return redirect()->back()->with('success', 'Data siswa dan akun berhasil dihapus.');
    }

    public function editSaya()
    {
        $user = Auth::user();
        
        if (!$user->siswa) {
            return redirect()->route('siswa.create');
        }

        $siswa = $user->siswa;
        return view('siswa.profil_saya', compact('siswa'));
    }

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