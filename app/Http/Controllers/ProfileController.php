<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Peminjaman;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // [LOGIKA BARU: CEK PEMINJAMAN SEBELUM HAPUS]
        if ($user->siswa) {
            // Cek apakah ada data di tabel peminjaman milik siswa ini
            $adaPeminjaman = Peminjaman::where('id_siswa', $user->siswa->id_siswa)->exists();

            if ($adaPeminjaman) {
                // Jika ada, batalkan hapus dan kirim pesan error agar ditangkap SweetAlert
                return Redirect::to('/profile')->with('error', 'Gagal menghapus akun! Anda masih memiliki riwayat peminjaman buku. selesaikan peminjaman buku!');
            }
        }

        // Jika aman (tidak ada peminjaman), baru proses hapus
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
