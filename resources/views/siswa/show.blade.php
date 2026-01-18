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
        // A. Logika Notifikasi Sukses/Gagal (Dari Controller)
        const flashData = document.getElementById('flash-data');
        const successMsg = flashData.dataset.success;
        const errorMsg = flashData.dataset.error;

        if (successMsg) {
            Swal.fire({ title: 'Berhasil!', text: successMsg, icon: 'success', confirmButtonColor: '#10B981', confirmButtonText: 'Oke' });
        }
        if (errorMsg) {
            Swal.fire({ title: 'Gagal!', text: errorMsg, icon: 'error', confirmButtonColor: '#EF4444', confirmButtonText: 'Tutup' });
        }

        // B. Logika Konfirmasi Pinjam (SweetAlert)
        function confirmPinjam(formId, judulBuku) {
            Swal.fire({
                title: 'Konfirmasi Peminjaman',
                text: "Apakah Anda yakin ingin meminjam buku \"" + judulBuku + "\"?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6', // Warna Biru
                cancelButtonColor: '#d33',     // Warna Merah
                confirmButtonText: 'Ya, Pinjam!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form secara manual jika user klik Ya
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    <!-- ========================================== -->

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <div class="mb-6">
                <a href="{{ route('katalog.index') }}" class="inline-flex items-center text-gray-600 hover:text-green-600 transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Katalog
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-6 sm:p-10">
                    <div class="flex flex-col md:flex-row gap-10">
                        
                        {{-- KOLOM KIRI: Gambar Sampul --}}
                        <div class="w-full md:w-1/3 flex-shrink-0">
                            <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200 relative group">
                                @if ($book->url_sampul)
                                    <img src="{{ asset('storage/' . $book->url_sampul) }}" 
                                         alt="{{ $book->judul }}" 
                                         class="w-full h-auto object-cover">
                                @else
                                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center text-gray-400">
                                        <span class="text-sm font-semibold">No Image</span>
                                    </div>
                                @endif

                                {{-- Badge Status Stok --}}
                                <div class="absolute top-4 right-4">
                                    @if($book->jumlah_stok > 0)
                                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-md">
                                            Stok: {{ $book->jumlah_stok }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-md">
                                            Habis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: Informasi Detail --}}
                        <div class="w-full md:w-2/3 flex flex-col">
                            
                            {{-- Judul & Penulis --}}
                            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2 leading-tight">
                                {{ $book->judul }}
                            </h1>
                            <p class="text-lg text-gray-600 mb-6 font-medium">
                                Penulis: <span class="text-green-600">{{ $book->penulis ?? '-' }}</span>
                            </p>

                            {{-- Grid Informasi Singkat --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8 bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Penerbit</span>
                                    <span class="font-semibold text-gray-800">{{ $book->penerbit ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Tahun</span>
                                    <span class="font-semibold text-gray-800">{{ $book->tahun_terbit ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide">ISBN</span>
                                    <span class="font-semibold text-gray-800">{{ $book->isbn ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Rak</span>
                                    <span class="font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                        {{ $book->rak ?? '?' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide">kategori</span>
                                    <span class="font-bold text-gray-800 bg-blue-50 px-2 py-0.5 rounded">
                                        {{ $book->kategori ?? '?' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Deskripsi (DENGAN SCROLL) --}}
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 border-b pb-2">Deskripsi Buku</h3>
                                {{-- 
                                    UPDATE: 
                                    - max-h-60: Tinggi maksimal sekitar 240px
                                    - overflow-y-auto: Scroll vertikal jika teks panjang
                                    - p-4 bg-gray-50: Padding dan background agar terlihat seperti kotak teks
                                --}}
                                <div class="text-gray-700 leading-relaxed text-justify space-y-4 max-h-60 overflow-y-auto p-4 bg-gray-50 rounded-lg border border-gray-200 custom-scrollbar">
                                    {!! nl2br(e($book->deskripsi ?? 'Tidak ada deskripsi.')) !!}
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="mt-auto pt-6 border-t border-gray-100 flex items-center justify-end">
                                @if($book->jumlah_stok > 0)
                                    {{-- FORM PINJAM --}}
                                    <form action="{{ route('peminjaman.ajukan', $book->id_buku) }}" method="POST" id="form-pinjam-detail">
                                        @csrf
                                        <button type="button" 
                                            onclick="confirmPinjam('form-pinjam-detail', '{{ $book->judul }}')"
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Ajukan Peminjaman
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="bg-gray-300 text-gray-500 font-bold py-3 px-8 rounded-lg cursor-not-allowed flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
</x-app-layout>