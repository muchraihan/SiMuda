<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Akun Pustakawan') }}
        </h2>
    </x-slot>

    {{-- Notifikasi SweetAlert (Sama seperti halaman lain) --}}
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
                        <h3 class="text-2xl font-bold text-gray-800">Daftar Admin Pustakawan</h3>
                        
                        {{-- Tombol Tambah --}}
                        <a href="{{ route('pustakawan.akun.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Pustakawan
                        </a>
                    </div>

                    {{-- Tabel --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left">No</th>
                                    <th class="py-3 px-4 text-left">Nama Lengkap</th>
                                    <th class="py-3 px-4 text-left">Email</th>
                                    <th class="py-3 px-4 text-left">Terdaftar Sejak</th>
                                    <th class="py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse($pustakawan as $index => $admin)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 font-bold">{{ $admin->name }}</td>
                                    <td class="py-3 px-4">{{ $admin->email }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-500">
                                        {{ $admin->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        {{-- Jangan izinkan hapus diri sendiri --}}
                                        @if(auth()->id() !== $admin->id_user)
                                            <form action="{{ route('pustakawan.akun.destroy', $admin->id_user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus admin ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm bg-red-50 px-3 py-1 rounded border border-red-200 hover:bg-red-100 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400 italic">(Akun Anda)</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">Belum ada pustakawan lain.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pustakawan->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>