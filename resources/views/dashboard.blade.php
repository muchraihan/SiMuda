<x-app-layout>
    {{-- Slot untuk judul halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard <span class="text-gray-500 font-normal text-lg">
                | Selamat Datang {{ Auth::user()->peran === 'pustakawan' ? 'Pustakawan' : 'Siswa' }}
            </span>
        </h2>
    </x-slot>

    {{-- Konten Utama Dashboard --}}
    <div class="py-0">
        <div class="max-w-full mx-auto sm:px-0 lg:px-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Grid untuk menampung kartu-kartu statistik --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                        {{-- ========================================== --}}
                        {{-- KARTU 1: DATA BUKU (Tampil untuk SEMUA) --}}
                        {{-- ========================================== --}}
                        <div class="block p-6 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md transition-transform transform hover:-translate-y-1">
                            <div class="flex justify-between items-start h-24">
                                <div class="flex flex-col">
                                    <h3 class="text-xl font-bold uppercase">Data Buku</h3>
                                    <p class="mt-1 text-5xl font-black">
                                        {{ number_format($jumlahBuku) }}
                                    </p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-75" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 16c1.255 0 2.443-.29 3.5-.804V4.804zM14.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 0114.5 16c1.255 0 2.443-.29 3.5-.804V4.804A7.968 7.968 0 0014.5 4z" />
                                </svg>
                            </div>
                            
                            {{-- Link Detail --}}
                            <div class="mt-4 border-t border-green-500 pt-2 text-sm font-semibold">
                                @if(auth()->user()->peran === 'siswa')
                                    <a href="{{ route('katalog.index') }}" class="text-white hover:text-green-200 flex items-center">
                                        Lihat Katalog &rarr;
                                    </a>
                                @else
                                    <a href="{{ route('pustakawan.buku.index') }}" class="text-white hover:text-green-200 flex items-center">
                                        Kelola Buku &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        {{-- ========================================== --}}
                        {{-- KARTU KHUSUS SISWA (RIWAYAT TRANSAKSI) --}}
                        {{-- ========================================== --}}
                        @if (Auth::user()->peran === 'siswa')
                            <a href="{{ route('siswa.riwayatpeminjaman.index') }}" class="block p-6 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-md transition-transform transform hover:-translate-y-1">
                                <div class="flex justify-between items-start h-24">
                                    <div class="flex flex-col">
                                        <h3 class="text-xl font-bold uppercase">Riwayat Saya</h3>
                                        <p class="mt-1 text-5xl font-black">
                                            {{ number_format($riwayatSaya) }}
                                        </p>
                                    </div>
                                    {{-- Ikon Jam/History --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-75" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="mt-4 border-t border-purple-500 pt-2 text-sm font-semibold">
                                    Lihat Riwayat &rarr;
                                </div>
                            </a>
                        @endif


                        {{-- ========================================== --}}
                        {{-- KARTU KHUSUS PUSTAKAWAN --}}
                        {{-- ========================================== --}}
                        @if (Auth::user()->peran === 'pustakawan')

                            {{-- KARTU 2: DATA SISWA --}}
                            <a href="{{ route('pustakawan.siswa.index') }}" class="block p-6 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition-transform transform hover:-translate-y-1">
                                <div class="flex justify-between items-start h-24">
                                    <div class="flex flex-col">
                                        <h3 class="text-xl font-bold uppercase">Data Siswa</h3>
                                        <p class="mt-1 text-5xl font-black">
                                            {{ number_format($jumlahSiswa) }}
                                        </p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-75" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                </div>
                                <div class="mt-4 border-t border-blue-500 pt-2 text-sm font-semibold">
                                    Kelola Siswa &rarr;
                                </div>
                            </a>

                            {{-- KARTU 3: SEDANG DIPINJAM --}}
                            <a href="{{ route('pustakawan.peminjaman') }}" class="block p-6 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow-md transition-transform transform hover:-translate-y-1">
                                <div class="flex justify-between items-start h-24">
                                    <div class="flex flex-col">
                                        <h3 class="text-xl font-bold uppercase">Dipinjam</h3>
                                        <p class="mt-1 text-5xl font-black">
                                            {{ number_format($jumlahPeminjaman) }}
                                        </p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-75" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="mt-4 border-t border-yellow-400 pt-2 text-sm font-semibold">
                                    Lihat Detail &rarr;
                                </div>
                            </a>
                            
                            {{-- KARTU 4: TERLAMBAT --}}
                            <a href="{{ route('pustakawan.peminjaman') }}" class="block p-6 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-md transition-transform transform hover:-translate-y-1">
                                <div class="flex justify-between items-start h-24">
                                    <div class="flex flex-col">
                                        <h3 class="text-xl font-bold uppercase">Terlambat</h3>
                                        <p class="mt-1 text-5xl font-black">
                                            {{ number_format($jumlahTerlambat) }}
                                        </p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-75" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="mt-4 border-t border-red-500 pt-2 text-sm font-semibold">
                                    Lihat Detail &rarr;
                                </div>
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>