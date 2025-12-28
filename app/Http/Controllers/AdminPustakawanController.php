<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth; // Tambahkan Import Facade Auth

class AdminPustakawanController extends Controller
{
    // 1. Tampilkan Daftar Pustakawan
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil user yang perannya 'pustakawan'
        $pustakawan = User::where('peran', 'pustakawan')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('pustakawan.akun.index', compact('pustakawan'));
    }

    // 2. Tampilkan Form Tambah
    public function create()
    {
        return view('pustakawan.akun.create');
    }

    // 3. Simpan Pustakawan Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => 'pustakawan', // KUNCI: Set peran jadi pustakawan
        ]);

        return redirect()->route('pustakawan.akun.index')->with('success', 'Akun Pustakawan berhasil ditambahkan!');
    }

    // 4. Hapus Pustakawan
    public function destroy($id)
    {
        // Ambil user yang sedang login menggunakan Facade Auth
        $currentUser = Auth::user();

        // Cek apakah user mencoba menghapus dirinya sendiri
        // Kita akses atribut 'id_user' secara langsung
        if ($currentUser && $currentUser->id_user == $id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        // PERBAIKAN UTAMA:
        // Gunakan where()->delete() agar tidak bergantung pada konfigurasi primaryKey di Model User.
        // Ini menghindari error "Unknown column 'id'" jika Model masih default.
        $deleted = User::where('id_user', $id)->delete();

        if ($deleted) {
            return back()->with('success', 'Akun Pustakawan berhasil dihapus.');
        } else {
            return back()->with('error', 'Gagal menghapus akun. Data tidak ditemukan.');
        }
    }
}