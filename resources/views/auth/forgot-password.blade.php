<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi - SiMuda Perpustakaan</title>
    <link rel="icon" href="{{ asset('storage/smuhduta.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fallback CDN Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-white">

    {{-- WRAPPER UTAMA: h-screen (tinggi layar pas) & overflow-hidden (hilangkan scroll body) --}}
    <div class="h-screen flex overflow-hidden">
        
        <!-- BAGIAN KIRI: STATIC & STICKY (Tidak ikut scroll) -->
        <div class="hidden lg:flex w-1/2 bg-green-900 relative h-full items-center justify-center">
            <!-- Gambar Background -->
            <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-multiply" 
                 alt="Library Background">
            
            <!-- Dekorasi Overlay Hijau -->
            <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-green-800/80 to-transparent opacity-90"></div>

            <!-- Konten Branding -->
            <div class="relative z-10 text-center text-white p-12 max-w-lg">
                <img src="{{ asset('storage/smuhduta.png') }}" alt="Logo SiMuda" class="h-28 w-auto mx-auto mb-8 drop-shadow-2xl hover:scale-105 transition-transform duration-500">
                <h1 class="text-5xl font-extrabold tracking-tight mb-4 drop-shadow-md">SiMuda</h1>
                <p class="text-xl font-medium text-green-100 leading-relaxed">
                    Sistem Informasi Perpustakaan Digital<br>SMP Muhammadiyah 2 Kartasura.
                </p>
            </div>
        </div>

        <!-- BAGIAN KANAN: SCROLLABLE (Hanya bagian ini yang bisa di-scroll) -->
        <div class="w-full lg:w-1/2 h-full overflow-y-auto bg-gray-50 lg:bg-white">
            
            {{-- Container Form (Min-h-full agar vertikal center jika konten sedikit, scroll jika banyak) --}}
            <div class="min-h-full flex items-center justify-center p-8 sm:p-12">
                
                <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-2xl shadow-xl lg:shadow-none border border-gray-100 lg:border-none">
                
                <!-- Header Mobile -->
                <div class="text-center lg:text-left">
                    <div class="lg:hidden flex justify-center mb-6">
                        <img src="{{ asset('storage/smuhduta.png') }}" alt="Logo" class="h-16 w-auto">
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Lupa Kata Sandi?</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Tidak masalah! Kami akan mengirimkan link untuk mengatur ulang kata sandi Anda.
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="username" required value="{{ old('email') }}"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition duration-200 placeholder-gray-400"
                                   placeholder="nama@sekolah.sch.id">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-0.5">
                            Kirim Link Reset
                            {{-- Icon Panah Kanan --}}
                            <span class="absolute right-0 inset-y-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-green-500 group-hover:text-green-300 transition duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Back to Login Link -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium text-green-600 hover:text-green-700 transition duration-200">
                        Kembali ke halaman login
                    </a>
                </div>

            </div>
        </div>
        </div>
    </div>

</body>
</html>
