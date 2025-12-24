<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('storage/smuhduta.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Script SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

    <div class="relative bg-gray-100">

        {{-- Tombol Toggle Mobile (Hamburger) --}}
        <button 
            id="sidebar-toggle-mobile"
            class="fixed top-4 left-4 z-50 p-2 rounded-md text-white bg-gray-800 hover:bg-gray-700 transition duration-200 lg:hidden">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Overlay Mobile --}}
        <div 
            id="sidebar-overlay" 
            class="fixed inset-0 z-30 hidden bg-black opacity-50 lg:hidden transition-opacity">
        </div>

        {{-- Sidebar --}}
        @include('layouts.partials._sidebar')

        {{-- Konten Utama --}}
        <div 
            id="main-content-wrapper" 
            class="flex flex-col min-h-screen transition-all duration-300 ml-0 lg:ml-72">

            @include('layouts.partials._header')

            {{-- Header Halaman --}}
            @if (isset($header))
                <header class="bg-white shadow relative z-10">
                    <div class="flex items-center justify-between max-w-7xl px-4 py-6 mx-auto sm:px-6 lg:px-8">
                        <div>{{ $header }}</div>
                        {{-- Tombol Buka Sidebar LAMA sudah dihapus dari sini --}}
                    </div>
                </header>
            @endif

            {{-- Isi Halaman --}}
            <main class="flex-grow p-6">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            @include('layouts.partials._footer')
        </div>
    </div>

    {{-- === SCRIPT TOGGLE SIDEBAR === --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content-wrapper');

            // === Mobile Logic ===
            const toggleButtonMobile = document.getElementById('sidebar-toggle-mobile');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleButtonMobileNav = document.getElementById('sidebar-toggle-mobile-nav'); // Tombol di Navbar Mobile

            // Fungsi Buka/Tutup Mobile
            const toggleSidebarMobile = () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            };

            if (toggleButtonMobile) toggleButtonMobile.addEventListener('click', toggleSidebarMobile);
            if (toggleButtonMobileNav) toggleButtonMobileNav.addEventListener('click', toggleSidebarMobile);
            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }

            // === Desktop Logic ===
            // Tombol TUTUP ada di dalam Sidebar (_sidebar.blade.php)
            const btnClose = document.getElementById('btn-close-sidebar'); 
            // Tombol BUKA ada di Header (_header.blade.php)
            const btnOpen = document.getElementById('btn-open-sidebar');   

            if (btnClose) {
                btnClose.addEventListener('click', () => {
                    // TUTUP SIDEBAR DESKTOP
                    sidebar.classList.add('lg:w-0', 'lg:p-0', 'overflow-hidden');
                    sidebar.classList.remove('lg:w-72');
                    
                    // Geser konten ke kiri (full width)
                    mainContent.classList.remove('lg:ml-72');
                    mainContent.classList.add('lg:ml-0');

                    // Munculkan tombol BUKA di header
                    if(btnOpen) {
                        btnOpen.classList.remove('hidden');
                        btnOpen.classList.add('lg:block');
                    }
                });
            }

            if (btnOpen) {
                btnOpen.addEventListener('click', () => {
                    // BUKA SIDEBAR DESKTOP
                    sidebar.classList.remove('lg:w-0', 'lg:p-0', 'overflow-hidden');
                    sidebar.classList.add('lg:w-72');
                    
                    // Geser konten ke kanan
                    mainContent.classList.remove('lg:ml-0');
                    mainContent.classList.add('lg:ml-72');

                    // Sembunyikan tombol BUKA ini sendiri
                    btnOpen.classList.add('hidden');
                    btnOpen.classList.remove('lg:block');
                });
            }
        });
    </script>
</body>
</html>