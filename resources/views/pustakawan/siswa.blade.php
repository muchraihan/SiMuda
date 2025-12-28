<x-app-layout>
    {{-- Slot untuk judul halaman yang akan muncul di header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Siswa') }}
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

        // Function untuk konfirmasi hapus siswa
        function confirmDeleteSiswa(id, nama) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus data siswa "${nama}"? Akun loginnya juga akan terhapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-siswa-${id}`).submit();
                }
            });
        }
    </script>
    {{-- ============================================================== --}}

    {{-- Konten Utama Halaman --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Daftar Data Siswa</h3>

                    {{-- Bagian atas: Tombol Tambah dan Form Pencarian --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                        {{-- Tombol Tambah Siswa (Opsional jika mau tambah manual) --}}
                        {{-- <a href="#" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>Tambah Siswa</span>
                        </a> --}}
                        
                        {{-- Placeholder Kosong agar Search ada di Kanan --}}
                        <div></div> 
                        
                        {{-- Form Pencarian --}}
                        <form action="{{ route('pustakawan.siswa.index') }}" method="GET" class="w-full sm:w-auto flex items-center">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS, atau kelas..."
                                class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm mr-2">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                                Cari
                            </button>
                        </form>
                    </div>

                    {{-- Tabel Data Siswa --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full bg-white">
                            <thead class="bg-green-600 text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">No</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">NIS</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Nama Siswa</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Kelas</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">No. WhatsApp</th>
                                    <th class="py-3 px-4 text-center text-sm font-semibold uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse($siswa as $index => $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    {{-- Nomor Urut (Memperhitungkan Pagination) --}}
                                    <td class="py-3 px-4">
                                        {{ $index + $siswa->firstItem() }}
                                    </td>
                                    <td class="py-3 px-4 font-mono">{{ $item->nis }}</td>
                                    <td class="py-3 px-4 font-bold">{{ $item->user->name ?? 'User Terhapus' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-gray-300">
                                            {{ $item->kelas }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-green-600">
                                        <a href="https://wa.me/{{ $item->nomor_whatsapp }}" target="_blank" class="hover:underline flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                            {{ $item->nomor_whatsapp }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center items-center space-x-3">
                                            
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('pustakawan.siswa.edit', $item->id_siswa) }}" 
                                            class="text-blue-500 hover:text-blue-700 transition transform hover:scale-110" 
                                            title="Edit Siswa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                            {{-- Tombol Hapus (Dengan Form & Confirm) --}}
                                            <form id="delete-form-siswa-{{ $item->id_siswa }}" action="{{ route('pustakawan.siswa.destroy', $item->id_siswa) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                
                                                <button type="button" 
                                                        onclick="confirmDeleteSiswa({{ $item->id_siswa }}, '{{ $item->user->name }}')"
                                                        class="text-red-500 hover:text-red-700 transition transform hover:scale-110 pt-1" 
                                                        title="Hapus Siswa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-gray-500 italic">
                                        Data siswa tidak ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Bagian Paginasi (Navigasi Halaman) --}}
                    <div class="mt-6">
                        {{ $siswa->appends(['search' => request('search')])->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>