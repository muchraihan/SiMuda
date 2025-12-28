<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SiMuda Perpustakaan</title>
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

    <div class="min-h-screen flex">
        
        <!-- BAGIAN KIRI: GAMBAR & BRANDING (Hanya tampil di layar besar) -->
        <div class="hidden lg:flex w-1/2 bg-green-900 items-center justify-center relative overflow-hidden">
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
                <div class="mt-8 flex justify-center space-x-2">
                    <span class="h-1 w-12 bg-white rounded-full"></span>
                    <span class="h-1 w-3 bg-green-400 rounded-full opacity-50"></span>
                    <span class="h-1 w-3 bg-green-400 rounded-full opacity-50"></span>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: FORM LOGIN -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-gray-50 lg:bg-white">
            <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-2xl shadow-xl lg:shadow-none border border-gray-100 lg:border-none">
                
                <!-- Header Mobile (Logo muncul di sini untuk HP) -->
                <div class="text-center lg:text-left">
                    <div class="lg:hidden flex justify-center mb-6">
                        <img src="{{ asset('storage/smuhduta.png') }}" alt="Logo" class="h-16 w-auto">
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Selamat Datang</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Silakan masuk dengan akun Anda untuk melanjutkan.
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
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

                    <!-- Password Input -->
                    <div class="space-y-1" x-data="{ show: false }">
                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                        
                        <div class="relative rounded-md shadow-sm">
                            {{-- Ikon Gembok (Kiri) --}}
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>

                            {{-- Input Password --}}
                            {{-- Perhatikan: pr-3 diubah jadi pr-10 agar teks tidak tertabrak tombol mata --}}
                            <input id="password" 
                                name="password" 
                                :type="show ? 'text' : 'password'" 
                                autocomplete="current-password" 
                                required
                                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition duration-200 placeholder-gray-400"
                                placeholder="••••••••">

                            {{-- Tombol Show/Hide (Kanan) --}}
                            <button type="button" 
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-green-600 focus:outline-none transition">
                                
                                {{-- Ikon Mata (Muncul saat password tersembunyi) --}}
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                {{-- Ikon Mata Dicoret (Muncul saat password terlihat) --}}
                                <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer transition">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer select-none">
                                Ingat Saya
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-green-600 hover:text-green-500 hover:underline">
                                    Lupa sandi?
                                </a>
                            </div>
                        @endif
                    </div> --}}

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-0.5">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-green-500 group-hover:text-green-400 transition duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Masuk Sekarang
                        </button>
                    </div>
                </form>

                <!-- Footer / Register Link -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                Siswa baru?
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('register') }}"
                           class="w-full flex justify-center py-3 px-4 border-2 border-green-600 rounded-lg text-sm font-bold text-green-600 bg-transparent hover:bg-green-50 transition duration-200">
                            Buat Akun Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>