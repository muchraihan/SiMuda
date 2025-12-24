{{-- STYLE TAMBAHAN KHUSUS SIDEBAR --}}
<style>
    /* Sembunyikan scrollbar untuk Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    /* Sembunyikan scrollbar untuk IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

<aside 
    id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex flex-col flex-shrink-0 min-h-screen
           bg-[#1e293b] text-white transform transition-all duration-300 ease-in-out
           w-64 lg:w-72 -translate-x-full lg:translate-x-0 shadow-2xl border-r border-gray-800/50">

    {{-- 1. HEADER LOGO & TOGGLE --}}
    <div class="flex items-center justify-between h-20 px-6 bg-gradient-to-r from-green-700 to-green-800 shadow-lg relative z-20 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:opacity-90 transition-opacity">
            <svg class="w-8 h-8 text-white drop-shadow-md" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 16c1.255 0 2.443-.29 3.5-.804V4.804zM14.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 0114.5 16c1.255 0 2.443-.29 3.5-.804V4.804A7.968 7.968 0 0014.5 4z" />
            </svg>
            <div>
                <h1 class="text-2xl font-black tracking-tight text-white leading-none">SiMuda</h1>
                <span class="text-[10px] text-green-100 font-medium uppercase tracking-widest opacity-80">Library</span>
            </div>
        </a>

        <button id="btn-close-sidebar" class="hidden lg:flex items-center justify-center w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white transition duration-200 backdrop-blur-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
    </div>

    @auth
        {{-- 2. INFO PENGGUNA --}}
        <div class="px-6 py-3 bg-[#0f172a] border-b border-gray-800/60 relative overflow-hidden shrink-0">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-green-500 rounded-full opacity-5 blur-2xl"></div>

            <div class="relative z-10">
                <div class="mb-4">
                    <h2 class="text-lg font-bold text-white tracking-wide truncate">{{ Auth::user()->name }}</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-900/30 text-green-400 border border-green-800/50">
                        {{ Auth::user()->peran }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-gray-300 bg-gray-800 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200 border border-gray-700/50 group">
                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-green-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin keluar?')"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-gray-300 bg-gray-800 hover:bg-red-900/20 hover:text-red-400 hover:border-red-900/30 rounded-lg transition-all duration-200 border border-gray-700/50 group">
                            <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 3. MENU NAVIGASI (Dengan Class no-scrollbar) --}}
        {{-- Hapus 'scrollbar-thin' dan ganti dengan 'no-scrollbar' --}}
        <nav class="flex-grow p-4 space-y-1.5 overflow-y-auto no-scrollbar">

            @if (Auth::user()->peran === 'pustakawan')
                <a href="{{ route('pustakawan.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('pustakawan.dashboard') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('pustakawan.dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V2z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('pustakawan.buku.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('pustakawan.buku.*') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('pustakawan.buku.*') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Manajemen Buku
                </a>
                <a href="{{ route('pustakawan.siswa.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('pustakawan.siswa.*') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('pustakawan.siswa.*') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Data Siswa
                </a>
                <a href="{{ route('pustakawan.peminjaman') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('pustakawan.peminjaman.*') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('pustakawan.peminjaman.*') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Peminjaman
                </a>
                <a href="{{ route('pustakawan.denda.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('pustakawan.denda.*') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('pustakawan.denda.*') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Denda & Sanksi
                </a>
                <a href="{{ route('pustakawan.laporan') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('pustakawan.laporan*') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('pustakawan.laporan*') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan
                </a>
            @endif

            @if (Auth::user()->peran === 'siswa')
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V2z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('katalog.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('katalog.*') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('katalog.*') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Katalog Buku
                </a>
                <a href="{{ route('siswa.riwayatpeminjaman.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('peminjaman.riwayat') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('peminjaman.riwayat') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat Saya
                </a>
                <a href="{{ route('siswa.profil.edit') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('siswa.profil.edit') ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-green-400' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('siswa.profil.edit') ? 'text-white' : 'text-gray-500 group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Data Diri Saya
                </a>
            @endif

        </nav>
    @endauth
</aside>