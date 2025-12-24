<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- BAGIAN KIRI: TOGGLE SIDEBAR & LOGO --}}
            <div class="flex items-center">
                
                {{-- 1. TOMBOL HAMBURGER (KHUSUS MOBILE) --}}
                {{-- Tombol ini akan mentrigger ID sidebar untuk muncul/hilang --}}
                <button id="sidebar-toggle" 
                        class="lg:hidden mr-3 p-2 rounded-md text-gray-500 hover:text-green-600 hover:bg-green-50 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- 2. LOGO APLIKASI --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{ asset('storage/smuhduta.png') }}" 
                             alt="Logo" 
                             class="block h-9 w-auto mr-2">
                        
                        {{-- Teks hanya muncul di layar besar (md ke atas) agar HP tidak sempit --}}
                        <div class="hidden md:flex flex-col">
                            <span class="text-gray-800 text-lg font-bold leading-tight">SiMuda</span>
                            <span class="text-xs text-gray-500 font-medium">Perpustakaan SMP Muhammadiyah 2</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- BAGIAN KANAN: DROPDOWN PROFIL --}}
            <div class="flex items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            
                            {{-- Info User (Nama & Role) --}}
                            <div class="text-right mr-2">
                                {{-- Nama dipotong jika terlalu panjang di HP --}}
                                <div class="text-sm font-bold text-gray-800 max-w-[100px] sm:max-w-none truncate">
                                    {{ Auth::user()->name }}
                                </div>
                                {{-- Role disembunyikan di HP yg sangat kecil, muncul di SM ke atas --}}
                                <div class="text-xs text-green-600 font-semibold hidden sm:block">
                                    {{ ucfirst(Auth::user()->peran) }}
                                </div>
                            </div>

                            {{-- Avatar / Icon --}}
                            <div class="ml-1 bg-green-100 p-1 rounded-full">
                                <svg class="h-5 w-5 text-green-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Header Dropdown --}}
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <p class="text-sm font-bold text-gray-900">Akun Saya</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            
                            {{-- Info Tambahan Siswa --}}
                            @if(Auth::user()->peran == 'siswa' && Auth::user()->siswa)
                                <div class="mt-2 pt-2 border-t border-gray-200 text-xs text-gray-600 grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="block text-gray-400">NIS</span>
                                        <span class="font-bold">{{ Auth::user()->siswa->nis }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-gray-400">Kelas</span>
                                        <span class="font-bold">{{ Auth::user()->siswa->kelas }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ __('Edit Profil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    class="text-red-600 hover:bg-red-50 flex items-center"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>

{{-- SCRIPT PENGENDALI SIDEBAR --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const contentOverlay = document.createElement('div'); // Overlay hitam transparan (opsional)

        // Fungsi Toggle
        if(toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Mencegah klik tembus
                // Toggle class translate untuk memunculkan/menyembunyikan sidebar
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // Fitur Tambahan: Klik di luar sidebar untuk menutup (Mobile UX)
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 1024) { // Hanya di mode mobile
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });
    });
</script>