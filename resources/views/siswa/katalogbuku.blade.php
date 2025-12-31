<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Katalog Buku
        </h2>
        <span class="text-gray-500 font-normal text-lg">| Peminjaman buku selama 7 Hari</span>
    </x-slot>

    <!-- 1. Simpan pesan Session di dalam DIV tersembunyi sebagai atribut -->
    <div id="flash-data" 
         data-success="{{ session('success') }}" 
         data-error="{{ session('error') }}">
    </div>

    <!-- 2. Library SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 3. Script JS Murni -->
    <script>
        const flashData = document.getElementById('flash-data');
        const successMsg = flashData.dataset.success;
        const errorMsg = flashData.dataset.error;

        // Logika SweetAlert Notifikasi
        if (successMsg) {
            Swal.fire({
                title: 'Berhasil!',
                text: successMsg,
                icon: 'success',
                confirmButtonColor: '#10B981',
                confirmButtonText: 'Oke'
            });
        }

        if (errorMsg) {
            Swal.fire({
                title: 'Gagal!',
                text: errorMsg,
                icon: 'error',
                confirmButtonColor: '#EF4444',
                confirmButtonText: 'Tutup'
            });
        }

        // Logika Konfirmasi Pinjam
        function confirmPinjam(formId, judulBuku) {
            Swal.fire({
                title: 'Konfirmasi Peminjaman',
                text: "Apakah Anda yakin ingin meminjam buku \"" + judulBuku + "\"?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Pinjam!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ========================================== --}}
            {{-- FORM PENCARIAN DENGAN TOMBOL X (CLEAR) --}}
            {{-- ========================================== --}}
            <div class="mb-8 p-6 bg-white rounded-xl shadow-lg border border-gray-100">
                <form action="{{ route('katalog.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
                    
                    {{-- Wrapper Input (Relative agar tombol X bisa absolute) --}}
                    <div class="relative w-full sm:w-1/2">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari berdasarkan judul, penulis, atau tahun..."
                               class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg shadow-sm py-2 pl-4 pr-10"> {{-- pr-10 agar teks tidak tertutup tombol X --}}
                        
                        {{-- Tombol X (Hanya muncul jika ada pencarian) --}}
                        @if(request('search'))
                            <a href="{{ route('katalog.index') }}" 
                               class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500 transition duration-200"
                               title="Hapus pencarian">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                            class="w-full sm:w-auto bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-150 font-semibold shadow-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>
                </form>
            </div>
            {{-- ========================================== --}}


            {{-- Grid Kartu Buku --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @forelse ($books as $book)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:scale-[1.02] border border-gray-100">
                        <!-- Link ke Detail -->
                        <a href="{{ route('katalog.show', $book->id_buku) }}" class="block">
                            @if ($book->url_sampul)
                                <img class="w-full h-48 sm:h-64 object-cover"
                                     src="{{ asset('storage/' . $book->url_sampul) }}"
                                     alt="Cover Buku: {{ $book->judul }}">
                            @else
                                <img class="w-full h-48 sm:h-64 object-cover"
                                     src="{{ asset('images/default-cover.jpg') }}"
                                     alt="Default Cover">
                            @endif
                        </a>

                        {{-- Detail Buku --}}
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="text-md sm:text-lg font-bold text-gray-900 mb-1 leading-tight line-clamp-2" title="{{ $book->judul }}">
                                {{ $book->judul }}
                            </h3>

                            <p class="text-xs text-gray-500 mb-2 italic">
                                {{ $book->penulis ?? 'Penulis tidak diketahui' }}
                            </p>

                            <p class="text-sm text-gray-700 line-clamp-3 mb-3">
                                {{ $book->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}
                            </p>

                            <div class="mt-auto"> 
                                <div class="d-flex justify-content-between align-items-center flex flex-col gap-2">
                                    
                                    <span class="text-gray-600 text-xs font-bold self-start">
                                        Stok: {{ $book->jumlah_stok }}
                                    </span>

                                    @if($book->jumlah_stok > 0)
                                        <form action="{{ route('peminjaman.ajukan', $book->id_buku) }}" method="POST" class="w-full" id="form-pinjam-{{ $book->id_buku }}">
                                            @csrf
                                            <button type="button" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded shadow" 
                                                onclick="confirmPinjam('form-pinjam-{{ $book->id_buku }}', '{{ $book->judul }}')">
                                                Pinjam Buku
                                            </button>
                                        </form>
                                    @else
                                        <button class="w-full bg-gray-400 text-white text-sm font-bold py-2 px-4 rounded cursor-not-allowed" disabled>
                                            Stok Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>    
                @empty
                    <div class="col-span-full mt-10 p-6 text-center bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg shadow-sm">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-yellow-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-lg font-semibold">Buku tidak ditemukan.</p>
                            <p class="text-sm mt-1">Coba kata kunci lain atau <a href="{{ route('katalog.index') }}" class="underline font-bold hover:text-yellow-900">tampilkan semua buku</a>.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Bagian Paginasi (Navigasi Halaman) --}}
            @if ($books->total() > 0)
                <div class="mt-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <span class="text-xs sm:text-sm text-gray-600 order-2 sm:order-1">
                        Menampilkan <span class="font-semibold text-gray-800">{{ $books->firstItem() }}</span>
                        sampai <span class="font-semibold text-gray-800">{{ $books->lastItem() }}</span>
                        dari <span class="font-semibold text-gray-800">{{ $books->total() }}</span> hasil
                    </span>

                    {{-- Navigasi Halaman dan Pilihan Per Page --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 order-1 sm:order-2">
                        <form action="{{ route('katalog.index') }}" method="GET" class="flex items-center space-x-2">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <label for="per_page" class="text-sm text-gray-600 whitespace-nowrap">Tampilkan:</label>
                            <select name="per_page" id="per_page" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-1 text-sm w-20 bg-white shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                <option value="10" {{ (request('per_page') ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ (request('per_page') ?? 10) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ (request('per_page') ?? 10) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ (request('per_page') ?? 10) == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>

                        @if ($books->hasPages())
                        <div class="flex items-center space-x-1 bg-gray-100 px-2 py-1 rounded-lg shadow-inner overflow-x-auto">
                            @if ($books->onFirstPage())
                                <span class="px-2 sm:px-3 py-1 text-gray-400 cursor-not-allowed">&laquo;</span>
                            @else
                                <a href="{{ $books->previousPageUrl() }}"
                                class="px-2 sm:px-3 py-1 text-gray-700 hover:bg-green-500 hover:text-white rounded-md transition">&laquo;</a>
                            @endif

                            @php
                                $start = max(1, $books->currentPage() - 2);
                                $end = min($books->lastPage(), $books->currentPage() + 2);
                            @endphp
                            @foreach ($books->getUrlRange($start, $end) as $page => $url)
                                @if ($page == $books->currentPage())
                                    <span class="px-2 sm:px-3 py-1 bg-green-600 text-white font-semibold rounded-md">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-2 sm:px-3 py-1 text-gray-700 hover:bg-green-500 hover:text-white rounded-md transition">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($books->hasMorePages())
                                <a href="{{ $books->nextPageUrl() }}"
                                class="px-2 sm:px-3 py-1 text-gray-700 hover:bg-green-500 hover:text-white rounded-md transition">&raquo;</a>
                            @else
                                <span class="px-2 sm:px-3 py-1 text-gray-400 cursor-not-allowed">&raquo;</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>