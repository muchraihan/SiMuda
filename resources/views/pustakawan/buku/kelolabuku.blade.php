<x-app-layout>
    {{-- Slot untuk judul halaman yang akan muncul di header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Buku') }}
        </h2>
    </x-slot>

    {{-- ============================================================== --}}
    {{-- NOTIFIKASI SWEETALERT --}}
    {{-- ============================================================== --}}
    
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

        // Cek Pesan Sukses
        if (successMsg) {
            Swal.fire({
                title: 'Berhasil!',
                text: successMsg,
                icon: 'success',
                confirmButtonColor: '#10B981',
                confirmButtonText: 'Oke'
            });
        }

        // Cek Pesan Error
        if (errorMsg) {
            Swal.fire({
                title: 'Gagal!',
                text: errorMsg,
                icon: 'error',
                confirmButtonColor: '#EF4444',
                confirmButtonText: 'Tutup'
            });
        }

        // Function untuk konfirmasi hapus buku
        function confirmDelete(id, judul) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus buku "${judul}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
    {{-- ============================================================== --}}

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Daftar Buku</h3>

            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                <a href="{{ route('pustakawan.buku.create') }}" 
                   class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" 
                              d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" 
                              clip-rule="evenodd" />
                    </svg>
                    <span>Tambah Buku</span>
                </a>
                
                <form action="{{ route('pustakawan.buku.index') }}" method="GET">
                    <div class="flex items-center space-x-2 relative">
                        <input type="text" 
                            name="search" 
                            placeholder="Cari Judul, Penulis, atau Penerbit..."
                            value="{{ $search ?? '' }}" 
                            class="border p-2 rounded flex-grow pr-10"> 
                        
                        @if (!empty($search))
                            <a href="{{ route('pustakawan.buku.index') }}" 
                            class="absolute inset-y-0 right-16 flex items-center pr-3 text-gray-500 hover:text-gray-700 font-bold">
                                &times;
                            </a>
                        @endif
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded">Cari</button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase">No</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Judul Buku</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Penulis</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Penerbit</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase">kategori</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Stok</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($buku as $index => $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $buku->firstItem() + $index }}</td>
                                <td class="py-3 px-4">{{ $item->judul }}</td>
                                <td class="py-3 px-4">{{ $item->penulis }}</td>
                                <td class="py-3 px-4">{{ $item->penerbit }}</td>
                                <td class="py-3 px-4">{{ $item->kategori }}</td>
                                <td class="py-3 px-4">{{ $item->jumlah_stok }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex justify-center space-x-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('pustakawan.buku.edit', $item->id_buku) }}" 
                                           class="text-blue-500 hover:text-blue-700 p-1" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" 
                                                      d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" 
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form id="delete-form-{{ $item->id_buku }}" action="{{ route('pustakawan.buku.destroy', $item->id_buku) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDelete({{ $item->id_buku }}, '{{ $item->judul }}')"
                                                    class="text-red-500 hover:text-red-700 p-1" 
                                                    title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" 
                                                          d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" 
                                                          clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Bagian Paginasi (Navigasi Halaman) --}}
            <div class="mt-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <span class="text-sm text-gray-600 order-2 sm:order-1">
                    Menampilkan <span class="font-semibold text-gray-800">{{ $buku->firstItem() }}</span>
                    sampai <span class="font-semibold text-gray-800">{{ $buku->lastItem() }}</span>
                    dari <span class="font-semibold text-gray-800">{{ $buku->total() }}</span> hasil
                </span>

                {{-- Navigasi Halaman dan Pilihan Per Page --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 order-1 sm:order-2">
                    <form action="{{ route('pustakawan.buku.index') }}" method="GET" class="flex items-center space-x-2">
                        <input type="hidden" name="search" value="{{ $search ?? '' }}">
                        <label for="per_page" class="text-sm text-gray-600 whitespace-nowrap">Tampilkan:</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-1 text-sm w-20 bg-white shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500">
                            <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ ($perPage ?? 10) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>

                    <div class="flex items-center space-x-1 bg-gray-100 px-2 py-1 rounded-lg shadow-inner">
                        @if ($buku->onFirstPage())
                            <span class="px-3 py-1 text-gray-400 cursor-not-allowed">&laquo;</span>
                        @else
                            <a href="{{ $buku->previousPageUrl() }}"
                            class="px-3 py-1 text-gray-700 hover:bg-green-500 hover:text-white rounded-md transition">&laquo;</a>
                        @endif

                        @foreach ($buku->getUrlRange(1, $buku->lastPage()) as $page => $url)
                            @if ($page == $buku->currentPage())
                                <span class="px-3 py-1 bg-green-600 text-white font-semibold rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 text-gray-700 hover:bg-green-500 hover:text-white rounded-md transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($buku->hasMorePages())
                            <a href="{{ $buku->nextPageUrl() }}"
                            class="px-3 py-1 text-gray-700 hover:bg-green-500 hover:text-white rounded-md transition">&raquo;</a>
                        @else
                            <span class="px-3 py-1 text-gray-400 cursor-not-allowed">&raquo;</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
