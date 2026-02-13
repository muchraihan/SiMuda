<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Agar baris 1 dianggap judul kolom
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Buat User Baru (Akun Login)
        // Password default disamakan dengan NIS agar mudah diingat siswa
        $user = User::create([
            'name'     => $row['nama'],
            'email'    => $row['email'],
            'password' => Hash::make($row['nis']), 
            'peran'    => 'siswa',
        ]);

        // 2. Buat Data Siswa (Tergabung ke User tadi)
        return new Siswa([
            'user_id'        => $user->id_user,
            'nis'            => $row['nis'],
            'kelas'          => $row['kelas'],
            'nomor_whatsapp' => $row['nomor_wa'], // Pastikan di excel namanya 'nomor_wa'
            'alamat'         => $row['alamat'],
        ]);
    }

    // Validasi agar data excel tidak duplikat/kosong
    public function rules(): array
    {
        return [
            'nama'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'nis'      => 'required|unique:siswa,nis',
            'kelas'    => 'required',
            'nomor_wa' => 'required',
            'alamat'   => 'required',
        ];
    }
}