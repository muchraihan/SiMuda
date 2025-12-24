<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Denda') }}
        </h2>
    </x-slot>

    {{-- NOTIFIKASI SWEETALERT --}}
    <div id="flash-data" data-success="{{ session('success') }}" data-error="{{ session('error') }}"></div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const flashData = document.getElementById('flash-data');
        if (flashData.dataset.success) Swal.fire('Berhasil!', flashData.dataset.success, 'success');
        if (flashData.dataset.error) Swal.fire('Gagal!', flashData.dataset.error, 'error');
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-2xl font-bold text-gray-800">Daftar Denda Keterlambatan</h3>
                        
                        {{-- Form Pencarian --}}
                        <form action="{{ route('pustakawan.denda.index') }}" method="GET" class="w-full sm:w-auto">
                            <div class="flex items-center space-x-2 relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Siswa atau Buku..."
                                    class="w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm pr-10">
                                
                                @if(request('search'))
                                    <a href="{{ route('pustakawan.denda.index') }}" 
                                       class="absolute inset-y-0 right-[5.5rem] flex items-center pr-3 text-gray-400 hover:text-red-500 font-bold text-xl transition">&times;</a>
                                @endif

                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Tabel Data Denda --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full bg-white">
                            <thead class="bg-red-600 text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">No</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Nama Siswa</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Judul Buku</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Tgl Dikembalikan</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Total Denda</th>
                                    <th class="py-3 px-4 text-center text-sm font-semibold uppercase">Status</th>
                                    <th class="py-3 px-4 text-center text-sm font-semibold uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse($dataDenda as $index => $item)
                                <tr class="border-b border-gray-200 hover:bg-red-50 transition">
                                    <td class="py-3 px-4">{{ $index + $dataDenda->firstItem() }}</td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="font-bold">{{ $item->peminjaman->siswa->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->peminjaman->siswa->kelas }}</div>
                                    </td>
                                    
                                    <td class="py-3 px-4">{{ $item->peminjaman->buku->judul }}</td>
                                    
                                    <td class="py-3 px-4 text-sm">
                                        {{ \Carbon\Carbon::parse($item->peminjaman->tgl_pengembalian_aktual)->format('d M Y') }}
                                    </td>
                                    
                                    <td class="py-3 px-4 font-bold text-red-600">
                                        Rp {{ number_format($item->jumlah_denda, 0, ',', '.') }}
                                    </td>
                                    
                                    <td class="py-3 px-4 text-center">
                                        @if($item->status_pembayaran == 'lunas')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full border border-green-200">
                                                Lunas
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full border border-red-200">
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="py-3 px-4 text-center">
                                        @if($item->status_pembayaran == 'belum_bayar')
                                            <form action="{{ route('pustakawan.denda.lunas', $item->id_denda) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1.5 px-3 rounded shadow transition"
                                                        onclick="return confirm('Konfirmasi: Denda sebesar Rp {{ number_format($item->jumlah_denda) }} sudah dibayar lunas?')">
                                                    Bayar
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-500 italic">
                                        Tidak ada data denda saat ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    <div class="mt-6">
                        {{ $dataDenda->appends(['search' => request('search')])->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>