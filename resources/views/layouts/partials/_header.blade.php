<nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- BAGIAN KIRI: LOGO & TOGGLE --}}
            <div class="flex items-center w-full justify-between">
                
                <div class="flex items-center">
                    {{-- 1. Tombol Toggle Mobile (Muncul di HP) --}}
                    <button id="sidebar-toggle-mobile-nav" 
                            class="lg:hidden mr-3 p-2 rounded-md text-gray-500 hover:text-green-600 hover:bg-green-50 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- 2. TOMBOL BUKA SIDEBAR (Desktop Only) --}}
                    {{-- Default: Hidden. JS akan menampilkannya saat sidebar ditutup --}}
                    <button id="btn-open-sidebar" 
                            class="hidden mr-4 text-gray-500 hover:text-green-700 transition duration-200 transform hover:scale-110"
                            title="Buka Sidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- 3. Logo --}}
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center text-gray-800 text-lg font-semibold">
                            <img src="{{ asset('storage/smuhduta.png') }}" 
                                 alt="Logo" 
                                 class="block h-9 w-auto mr-3">
                            <span class="hidden md:block">Perpustakaan SMP Muhammadiyah 2</span>
                            <span class="block md:hidden">SiMuda</span>
                        </a>
                    </div>
                </div>

                {{-- BAGIAN KANAN --}}
                
            </div>
        </div>
    </div>
</nav>