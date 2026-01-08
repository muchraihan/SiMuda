<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Rekapitulasi Peminjaman Bulanan</h3>

                    {{-- FORM FILTER & TOMBOL CETAK --}}
                    <form action="{{ route('pustakawan.laporan') }}" method="GET" class="mb-6 flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                        
                        {{-- Filter Bulan --}}
                        <div class="w-full sm:w-auto">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" class="w-full sm:w-auto rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Filter Tahun --}}
                        <div class="w-full sm:w-auto">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" class="w-full sm:w-auto rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @for($i = date('Y'); $i >= date('Y')-5; $i--)
                                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Tombol Filter --}}
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            Tampilkan
                        </button>

                        {{-- Tombol Cetak PDF (Kanan) --}}
                        <a href="{{ route('pustakawan.laporan.cetak', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
                           class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow flex items-center justify-center sm:ml-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Download PDF
                        </a>
                    </form>

                    {{-- TABEL PRATINJAU --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full bg-white text-xs sm:text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 sm:py-3 px-2 sm:px-4 text-left font-semibold">No</th>
                                    <th class="py-2 sm:py-3 px-2 sm:px-4 text-left font-semibold">Nama Siswa</th>
                                    <th class="py-2 sm:py-3 px-2 sm:px-4 text-left font-semibold">Kelas</th>
                                    <th class="py-2 sm:py-3 px-2 sm:px-4 text-left font-semibold">Judul Buku</th>
                                    <th class="py-2 sm:py-3 px-2 sm:px-4 text-left font-semibold">Tgl Pinjam</th>
                                    <th class="py-2 sm:py-3 px-2 sm:px-4 text-left font-semibold">Tgl Kembali</th>
                                    <th class="py-2 sm:py-3 px-2 sm:px-4 text-center font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($laporan as $index => $item)
                                <tr>
                                    <td class="py-1 sm:py-2 px-2 sm:px-4">{{ $index + $laporan->firstItem() }}</td>
                                    <td class="py-1 sm:py-2 px-2 sm:px-4">{{ $item->siswa->user->name }}</td>
                                    <td class="py-1 sm:py-2 px-2 sm:px-4">{{ $item->siswa->kelas }}</td>
                                    <td class="py-1 sm:py-2 px-2 sm:px-4">{{ $item->buku->judul }}</td>
                                    <td class="py-1 sm:py-2 px-2 sm:px-4">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                                    <td class="py-1 sm:py-2 px-2 sm:px-4">
                                        @if($item->tgl_pengembalian_aktual)
                                            {{ \Carbon\Carbon::parse($item->tgl_pengembalian_aktual)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-1 sm:py-2 px-2 sm:px-4 text-center">
                                        <span class="px-1 sm:px-2 py-1 rounded text-xs font-bold 
                                            {{ $item->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : 
                                              ($item->status == 'dipinjam' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500 italic">Tidak ada data peminjaman pada periode ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Bagian Paginasi (Navigasi Halaman) --}}
                    <div class="mt-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <span class="text-sm text-gray-600 order-2 sm:order-1">
                            Menampilkan <span class="font-semibold text-gray-800">{{ $laporan->firstItem() }}</span>
                            sampai <span class="font-semibold text-gray-800">{{ $laporan->lastItem() }}</span>
                            dari <span class="font-semibold text-gray-800">{{ $laporan->total() }}</span> hasil
                        </span>

                        {{-- Navigasi Halaman dan Pilihan Per Page --}}
                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 order-1 sm:order-2">
                            <form action="{{ route('pustakawan.laporan') }}" method="GET" class="flex items-center space-x-2">
                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <label for="per_page" class="text-sm text-gray-600 whitespace-nowrap">Tampilkan:</label>
                                <select name="per_page" id="per_page" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-1 text-sm w-20 bg-white shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                    <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ ($perPage ?? 10) == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>

                            <div class="flex items-center space-x-1 bg-gray-100 px-2 py-1 rounded-lg shadow-inner">
                                @if ($laporan->onFirstPage())
                                    <span class="px-3 py-1 text-gray-400 cursor-not-allowed">&laquo;</span>
                                @else
                                    <a href="{{ $laporan->previousPageUrl() }}"
                                    class="px-3 py-1 text-gray-700 hover:bg-green-500 hover:text-white rounded-md transition">&laquo;</a>
                                @endif

                                @foreach ($laporan->getUrlRange(1, $laporan->lastPage()) as $page => $url)
                                    @if ($page == $laporan->currentPage())
                                        <span class="px-3 py-1 bg-green-600 text-white font-semibold rounded-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-1 text-gray-700 hover:bg-green-500 hover:text-white rounded-md transition">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if ($laporan->hasMorePages())
                                    <a href="{{ $laporan->nextPageUrl() }}"
                                    class="px-3 py-1 text-gray-700 hover:bg-green-500 hover:text-white rounded-md transition">&raquo;</a>
                                @else
                                    <span class="px-3 py-1 text-gray-400 cursor-not-allowed">&raquo;</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>