@php
    // Helper untuk menampilkan status dengan warna Tailwind CSS
    // Kita taruh disini biar tidak mengotori file Controller dengan HTML
    function getStatusBadge($status) {
        switch ($status) {
            case 'dipinjam':
                return '<span class="px-3 py-1 text-xs font-bold leading-tight text-blue-700 bg-blue-100 rounded-full">Dipinjam</span>';
            case 'dikembalikan':
                return '<span class="px-3 py-1 text-xs font-bold leading-tight text-green-700 bg-green-100 rounded-full">Dikembalikan</span>';
            case 'terlambat':
                return '<span class="px-3 py-1 text-xs font-bold leading-tight text-red-700 bg-red-100 rounded-full">Terlambat</span>';
            case 'diajukan':
                return '<span class="px-3 py-1 text-xs font-bold leading-tight text-yellow-700 bg-yellow-100 rounded-full">Menunggu Konfirmasi</span>';
            case 'ditolak':
                return '<span class="px-3 py-1 text-xs font-bold leading-tight text-red-700 bg-red-100 rounded-full">Ditolak</span>';
            default:
                return '<span class="px-3 py-1 text-xs font-bold leading-tight text-gray-700 bg-gray-100 rounded-full">' . ucfirst($status) . '</span>';
        }
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Peminjaman <span class="text-gray-500 font-normal text-lg">| Transaksi Saya</span>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Transaksi</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batas Tgl Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Dikembalikan</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                
                                @forelse ($histories as $index => $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    
                                    {{-- Judul Buku (Ambil dari relasi) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->buku->judul }}
                                    </td>
                                    
                                    {{-- Tanggal Pinjam --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($item->tgl_pinjam)
                                            {{ \Carbon\Carbon::parse($item->tgl_pinjam)->translatedFormat('d M Y') }}
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>

                                    {{-- Batas Kembali --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-semibold">
                                        @if($item->tgl_kembali_maksimal)
                                            {{ \Carbon\Carbon::parse($item->tgl_kembali_maksimal)->translatedFormat('d M Y') }}
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Tanggal Dikembalikan --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($item->tgl_pengembalian_aktual)
                                            {{ \Carbon\Carbon::parse($item->tgl_pengembalian_aktual)->translatedFormat('d M Y') }}
                                        @else
                                            <span class="text-gray-400 italic text-xs">Belum Kembali</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Status Badge (Panggil fungsi PHP di atas) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        {!! getStatusBadge($item->status) !!}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm italic">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            <p>Anda belum pernah melakukan peminjaman buku.</p>
                                            <a href="{{ route('katalog.index') }}" class="mt-2 text-green-600 hover:text-green-800 font-bold text-sm">Pinjam Buku Sekarang &rarr;</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>