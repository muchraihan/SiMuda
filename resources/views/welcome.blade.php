<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SiMuda') }} - Perpustakaan Digital</title>
    <link rel="icon" href="{{ asset('storage/smuhduta.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-sans">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                {{-- Logo Kiri --}}
                <div class="flex items-center">
                    <img src="{{ asset('storage/smuhduta.png') }}" alt="Logo" class="h-12 w-auto mr-3">
                    <div class="flex flex-col">
                        <span class="text-lg sm:text-2xl font-bold text-green-700 tracking-tight">SiMuda - SMP Muhammadiyah 2 Kartasura</span>
                        <span class="text-xs sm:text-sm text-gray-500 font-semibold tracking-wide">PERPUSTAKAAN DIGITAL</span>
                    </div>
                </div>

                {{-- Menu Kanan (Auth) --}}
                <div class="hidden md:flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-gray-700 hover:text-green-600 transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-700 hover:text-green-600 transition">
                                Masuk
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-green-600 rounded-full hover:bg-green-700 transition shadow-lg hover:shadow-green-500/30">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                {{-- Mobile menu button --}}
                <div class="md:hidden flex items-center">
                    <button id="nav-toggle" aria-label="Toggle menu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-green-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- Mobile Auth Menu (hidden by default) --}}
    <div id="mobile-menu" class="md:hidden hidden bg-white border-b border-gray-100">
        <div class="px-4 py-3 space-y-2">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="block text-sm font-bold text-gray-700 hover:text-green-600">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block text-sm font-bold text-gray-700 hover:text-green-600">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block text-sm font-bold text-white bg-green-600 px-4 py-2 rounded-full text-center hover:bg-green-700">Daftar Sekarang</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    {{-- HERO SECTION --}}
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Jelajahi Dunia Lewat</span>
                            <span class="block text-green-600 xl:inline">Perpustakaan</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Selamat datang di Perpustakaan SMP Muhammadiyah 2 Kartasura. Temukan beragam koleksi buku menarik, pinjam dengan mudah, dan tingkatkan wawasanmu setiap hari.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10 transition">
                                    Mulai Membaca
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#fitur" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 md:py-4 md:text-lg md:px-10 transition">
                                    Fitur
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#kontak" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 md:py-4 md:text-lg md:px-10 transition">
                                    Kontak
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-green-50">
            {{-- Gambar Hero Lokal --}}
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full opacity-90" 
                src="{{ asset('storage/perpus smuhduta.jpg') }}" 
                alt="Perpustakaan SMP Muhammadiyah 2 Kartasura">
        </div>
    </div>

    {{-- FEATURES SECTION --}}
    <div id="fitur" class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Kenapa SiMuda?</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Lebih Dari Sekadar Rak Buku
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Kami menyediakan layanan perpustakaan modern untuk mendukung kegiatan belajar mengajar siswa SMP Muhammadiyah 2 Kartasura.
                </p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                    
                    {{-- Feature 1 --}}
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Koleksi Lengkap</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Akses beragam judul buku pelajaran, novel, ensiklopedia, dan majalah edukasi terbaru.
                        </dd>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Pinjam Kapan Saja</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Ajukan peminjaman buku secara online dari rumah, lalu ambil di perpustakaan tanpa antri.
                        </dd>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Notifikasi WA</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Dapatkan pengingat otomatis via WhatsApp saat buku disetujui atau jatuh tempo.
                        </dd>
                    </div>

                </dl>
            </div>
        </div>
    </div>

    {{-- INFORMASI LOKASI & KONTAK --}}
    <div id="kontak" class="bg-white py-12 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    
                    {{-- Peta / Informasi Kiri --}}
                <div class="rounded-lg overflow-hidden shadow-lg border border-gray-200 relative group">
                    {{-- Overlay judul saat di-hover --}}
                    <a href="https://maps.app.goo.gl/pBPF4JHqUfJ5R4ns5" target="_blank" class="absolute inset-0 z-10 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition duration-300 flex items-center justify-center">
                        <span class="opacity-0 group-hover:opacity-100 bg-white text-gray-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm transition">
                            Buka di Google Maps
                        </span>
                    </a>

                    {{-- Responsive embed wrapper (16:9) --}}
                    <div class="relative w-full" style="padding-top:56.25%;">
                        <iframe 
                            class="absolute inset-0 w-full h-full"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.152355557766!2d110.7485361741054!3d-7.558372374637651!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a14936d542385%3A0x62955f19069d4d23!2sSMP%20Muhammadiyah%202%20Kartasura!5e0!3m2!1sid!2sid!4v1715000000000!5m2!1sid!2sid" 
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                {{-- Kontak Kanan --}}
                <div class="bg-green-50 p-8 rounded-2xl h-full flex flex-col justify-center">
                    <h3 class="text-2xl font-bold text-green-800 mb-6">Hubungi Kami</h3>
                    
                    <ul class="space-y-6">
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="flex items-center justify-center h-10 w-10 rounded-full bg-green-200 text-green-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">Alamat</h4>
                                <p class="mt-1 text-gray-600">
                                    Jl. Dukuh No.12, Dusun II, Makamhaji, Kec. Kartasura, Kabupaten Sukoharjo, Jawa Tengah 57161
                                </p>
                            </div>
                        </li>

                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="flex items-center justify-center h-10 w-10 rounded-full bg-green-200 text-green-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">Email</h4>
                                <p class="mt-1 text-gray-600">
                                    muha02kts@gmail.com
                                </p>
                            </div>
                        </li>

                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="flex items-center justify-center h-10 w-10 rounded-full bg-green-200 text-green-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">Telepon</h4>
                                <p class="mt-1 text-gray-600">
                                    0821-3564-0678 (admin)
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <h3 class="text-xl font-bold text-green-400">SiMuda</h3>
                <p class="text-sm text-gray-400">Perpustakaan SMP Muhammadiyah 2 Kartasura</p>
            </div>
            <div class="flex space-x-6 text-sm text-gray-400">
                <span>&copy; {{ date('Y') }} All rights reserved.</span>
            </div>
        </div>
    </footer>

    <script>
        (function(){
            var btn = document.getElementById('nav-toggle');
            var menu = document.getElementById('mobile-menu');
            if(!btn || !menu) return;
            btn.addEventListener('click', function(){
                menu.classList.toggle('hidden');
            });
        })();
    </script>

</body>
</html>