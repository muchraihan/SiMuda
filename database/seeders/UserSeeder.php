<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Pustakawan (Admin)
        User::create([
            'name' => 'Admin Pustakawan',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin111'), // Password default
            'peran' => 'pustakawan',
            'email_verified_at' => now(),
        ]);

        // 2. Buat Akun Siswa 1
        User::create([
            'name' => 'siswa',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('siswa111'),
            'peran' => 'siswa',
            'email_verified_at' => now(),
        ]);

        // 3. Buat Akun Siswa 2
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'peran' => 'siswa',
            'email_verified_at' => now(),
        ]);
        
        // 4. Buat Akun Siswa 3
        User::create([
            'name' => 'Rizky Ramadhan',
            'email' => 'rizky@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'peran' => 'siswa',
            'email_verified_at' => now(),
        ]);
    }
}