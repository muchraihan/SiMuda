<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Buku') }}
        </h2>
    </x-slot>

    <!-- ========================================== -->
    <!-- 1. SCRIPT SWEETALERT (Notifikasi & Konfirmasi) -->
    <!-- ========================================== -->
    <div id="flash-data" 
         data-success="{{ session('success') }}" 
         data-error="{{ session('error') }}">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const flashData = document.getElementById('flash-data');
        const successMsg = flashData.dataset.success;
        const errorMsg = flashData.dataset.error;

        if (successMsg) Swal.fire({ title: 'Berhasil!', text: successMsg, icon: 'success', confirmButtonColor: '#10B981', confirmButtonText: 'Oke' });
        if (errorMsg) Swal.fire({ title: 'Gagal!', text: errorMsg, icon: 'error', confirmButtonColor: '#EF4444', confirmButtonText: 'Tutup' });

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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <div class="mb-6">
                <a href="{{ route('katalog.index') }}" class="inline-flex items-center text-gray-600 hover:text-green-600 transition font-medium group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transition-transform group-hover:-translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Katalog
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-6 sm:p-10">
                    <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                        
                        {{-- KOLOM KIRI: Gambar Sampul --}}
                        <div class="w-full lg:w-1/3 flex-shrink-0">
                            <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200 relative group bg-gray-100 aspect-[2/3]">
                                @if ($book->url_sampul)
                                    <img src="{{ asset('storage/' . $book->url_sampul) }}" 
                                         alt="{{ $book->judul }}" 
                                         class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-semibold">No Image</span>
                                    </div>
                                @endif

                                {{-- Badge Status Stok (Pojok Kanan Atas Gambar) --}}
                                <div class="absolute top-4 right-4">
                                    @if($book->jumlah_stok > 0)
                                        <span class="px-3 py-1 bg-green-500/90 backdrop-blur-sm text-white text-xs font-bold rounded-full shadow-md border border-green-400">
                                            Stok: {{ $book->jumlah_stok }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-500/90 backdrop-blur-sm text-white text-xs font-bold rounded-full shadow-md border border-red-400">
                                            Habis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: Informasi Detail --}}
                        <div class="w-full lg:w-2/3 flex flex-col">
                            
                            {{-- Header Buku --}}
                            <div class="border-b border-gray-100 pb-6 mb-6">
                                {{-- Badge Kategori --}}
                                <span class="inline-block mb-3 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 rounded-lg border border-blue-100">
                                    {{ $book->kategori ?? 'Umum' }}
                                </span>

                                <h1 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight mb-2">
                                    {{ $book->judul }}
                                </h1>
                                <p class="text-lg text-gray-500 font-medium flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Penulis: <span class="text-gray-800 ml-1">{{ $book->penulis ?? '-' }}</span>
                                </p>
                            </div>

                            {{-- Grid Informasi Singkat (Rapi & Tidak Tumpang Tindih) --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <span class="block text-[10px] text-gray-500 uppercase tracking-wide font-bold mb-1">Penerbit</span>
                                    <span class="font-semibold text-gray-900 text-sm block truncate" title="{{ $book->penerbit }}">{{ $book->penerbit ?? '-' }}</span>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <span class="block text-[10px] text-gray-500 uppercase tracking-wide font-bold mb-1">Tahun</span>
                                    <span class="font-semibold text-gray-900 text-sm block">{{ $book->tahun_terbit ?? '-' }}</span>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <span class="block text-[10px] text-gray-500 uppercase tracking-wide font-bold mb-1">ISBN</span>
                                    <span class="font-semibold text-gray-900 text-sm block truncate" title="{{ $book->isbn }}">{{ $book->isbn ?? '-' }}</span>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <span class="block text-[10px] text-gray-500 uppercase tracking-wide font-bold mb-1">Lokasi Rak</span>
                                    <span class="inline-block bg-white px-2 py-0.5 rounded border border-gray-200 text-sm font-bold text-gray-800">
                                        {{ $book->rak ?? '?' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Deskripsi (Dengan Scroll) --}}
                            <div class="mb-8 flex-grow">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                    Sinopsis / Deskripsi
                                </h3>
                                <div class="text-gray-700 leading-relaxed text-justify text-sm space-y-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar bg-white p-1">
                                    @if($book->deskripsi)
                                        {!! nl2br(e($book->deskripsi)) !!}
                                    @else
                                        <p class="text-gray-400 italic">Belum ada deskripsi untuk buku ini.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Tombol Aksi (Fixed di Bawah pada Mobile, Normal di Desktop) --}}
                            <div class="mt-auto pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">

                                @if($book->jumlah_stok > 0)
                                    <form action="{{ route('peminjaman.ajukan', $book->id_buku) }}" method="POST" id="form-pinjam-detail" class="w-full sm:w-auto">
                                        @csrf
                                        <button type="button" 
                                            onclick="confirmPinjam('form-pinjam-detail', '{{ $book->judul }}')"
                                            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-green-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Ajukan Peminjaman
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full sm:w-auto bg-gray-100 text-gray-400 font-bold py-3 px-8 rounded-xl cursor-not-allowed flex items-center justify-center gap-2 border border-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        Stok Habis
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Style Tambahan untuk Scrollbar Cantik --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</x-app-layout>