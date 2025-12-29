<x-app-layout>
    {{-- Slot untuk judul halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Peminjaman') }}
        </h2>
    </x-slot>

    {{-- ============================================================== --}}
    {{-- NOTIFIKASI SWEETALERT (ANTI ERROR VS CODE) --}}
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

        if (successMsg) {
            Swal.fire({
                title: 'Berhasil!',
                text: successMsg,
                icon: 'success',
                confirmButtonColor: '#10B981', // Hijau Tailwind
                confirmButtonText: 'Oke'
            });
        }

        if (errorMsg) {
            Swal.fire({
                title: 'Gagal!',
                text: errorMsg,
                icon: 'error',
                confirmButtonColor: '#EF4444', // Merah Tailwind
                confirmButtonText: 'Tutup'
            });
        }
    </script>
    {{-- ============================================================== --}}


    {{-- Konten Utama Halaman --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- BAGIAN 1: TABEL APPROVAL (PERMINTAAN MASUK) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-400">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">🔔 Permintaan Peminjaman Baru</h3>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            Perlu Persetujuan: {{ $permintaan->count() }}
                        </span>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full bg-white">
                            <thead class="bg-yellow-500 text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Siswa</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Buku</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Tgl Request</th>
                                    <th class="py-3 px-4 text-center text-sm font-semibold uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse($permintaan as $req)
                                <tr class="border-b border-gray-200 hover:bg-yellow-50 transition">
                                    <td class="py-3 px-4">
                                        <div class="font-bold">{{ $req->siswa->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $req->siswa->kelas }} | NIS: {{ $req->siswa->nis }}</div>
                                    </td>
                                    <td class="py-3 px-4">{{ $req->buku->judul }}</td>
                                    <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($req->tgl_pengajuan)->format('d M Y') }}</td>
                                    <td class="py-3 px-4 text-center">
                                        {{-- Tombol Buka Modal --}}
                                        <button onclick="openModal('modal-{{ $req->id_peminjaman }}')" 
                                                class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-2 px-3 rounded shadow hover:shadow-md transition">
                                            🔍 Cek Detail
                                        </button>
                                    </td>
                                </tr>

                                {{-- MODAL POPUP --}}
                                <div id="modal-{{ $req->id_peminjaman }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        
                                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Konfirmasi Peminjaman</h3>
                                                <div class="mt-4 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <h4 class="font-bold text-gray-700 border-b pb-1 mb-2">Data Siswa</h4>
                                                        <p class="text-sm">Nama: {{ $req->siswa->user->name }}</p>
                                                        <p class="text-sm">Kelas: {{ $req->siswa->kelas }}</p>
                                                        <p class="text-sm">WA: {{ $req->siswa->nomor_whatsapp }}</p>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-gray-700 border-b pb-1 mb-2">Data Buku</h4>
                                                        <p class="text-sm font-bold">{{ $req->buku->judul }}</p>
                                                        <p class="text-sm">Stok Sisa: {{ $req->buku->jumlah_stok }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                {{-- Tombol Terima --}}
                                                <form action="{{ route('peminjaman.acc', $req->id_peminjaman) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                                        ✅ Setujui
                                                    </button>
                                                </form>

                                                {{-- Tombol Tolak --}}
                                                <form action="{{ route('peminjaman.tolak', $req->id_peminjaman) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                        ✖ Tolak
                                                    </button>
                                                </form>
                                                
                                                {{-- Tombol Batal --}}
                                                <button type="button" onclick="closeModal('modal-{{ $req->id_peminjaman }}')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-4 px-4 text-center text-gray-500 italic">
                                        Tidak ada permintaan peminjaman baru.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            {{-- BAGIAN 2: TABEL MONITORING (SEDANG DIPINJAM) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Daftar Buku Dipinjam & Riwayat</h3>
                    <p class="text-gray-600 mb-6">Daftar buku yang sedang dibawa siswa. Buku yang sudah dikembalikan tidak muncul disini (masuk history).</p>

                    {{-- Form Pencarian --}}
                    <div class="flex justify-end items-center mb-4">
                        <form action="{{ route('pustakawan.peminjaman') }}" method="GET" class="w-full sm:w-auto">
                            <div class="flex items-center space-x-2 relative">
                                
                                {{-- Input Search --}}
                                <input type="text" 
                                       name="search" 
                                       placeholder="Cari buku atau siswa..."
                                       value="{{ request('search') }}" 
                                       class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pr-10">
                                
                                {{-- Tombol X (Clear) --}}
                                {{-- Hanya muncul jika ada request 'search' di URL --}}
                                @if(request('search'))
                                    <a href="{{ route('pustakawan.peminjaman') }}" 
                                       class="absolute inset-y-0 right-[5.5rem] flex items-center pr-3 text-gray-400 hover:text-gray-600 font-bold text-xl transition"
                                       title="Hapus pencarian">
                                        &times;
                                    </a>
                                @endif

                                {{-- Tombol Cari --}}
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Tabel Data Pengembalian --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full bg-white">
                            <thead class="bg-green-600 text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">No</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Judul Buku</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Nama Peminjam</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Tgl Kembali Maks</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Status</th>
                                    <th class="py-3 px-4 text-center text-sm font-semibold uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse($sedangDipinjam as $index => $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 font-medium">{{ $item->buku->judul }}</td>
                                    <td class="py-3 px-4">
                                        {{ $item->siswa->user->name }}
                                        <div class="text-xs text-gray-400">{{ $item->siswa->kelas }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        {{ \Carbon\Carbon::parse($item->tgl_kembali_maksimal)->format('d-m-Y') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if(now() > $item->tgl_kembali_maksimal)
                                            <span class="bg-red-200 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">
                                                Terlambat {{ now()->diffInDays($item->tgl_kembali_maksimal) }} Hari
                                            </span>
                                        @else
                                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">
                                                Dipinjam
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        {{-- FORM PENGEMBALIAN BUKU (SUDAH AKTIF) --}}
                                        <form id="form-kembali-{{ $item->id_peminjaman }}" action="{{ route('peminjaman.kembalikan', $item->id_peminjaman) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <button type="button" 
                                                class="btn-kembali bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-3 rounded text-sm flex items-center mx-auto space-x-1 transition shadow-md"
                                                data-judul="{{ $item->buku->judul }}"
                                                data-form="form-kembali-{{ $item->id_peminjaman }}">
                                                
                                                <!-- Ikon Refresh/Undo -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                <span>Kembali</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-gray-500">Tidak ada buku yang sedang dipinjam saat ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script Sederhana untuk Modal --}}
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Script untuk konfirmasi pengembalian buku dengan SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            const btnKembalis = document.querySelectorAll('.btn-kembali');
            
            btnKembalis.forEach(btn => {
                btn.addEventListener('click', function() {
                    const judulBuku = this.getAttribute('data-judul');
                    const formId = this.getAttribute('data-form');
                    const form = document.getElementById(formId);
                    
                    Swal.fire({
                        title: 'Konfirmasi Pengembalian',
                        text: `Apakah buku "${judulBuku}" benar-benar sudah dikembalikan oleh siswa?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Kembalikan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>