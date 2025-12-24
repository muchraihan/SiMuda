<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lengkapi Data Diri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-t-4 border-green-500">
                
                <h3 class="text-lg font-bold mb-4 text-gray-700">Halo, {{ Auth::user()->name }}!</h3>
                <p class="mb-6 text-gray-600 text-sm">
                    Sebelum meminjam buku, mohon lengkapi data kesiswaan Anda terlebih dahulu. Data ini hanya perlu diisi <b>satu kali</b> saja.
                </p>

                <!-- Menampilkan Error Validasi -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Ada kesalahan!</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('siswa.store') }}" method="POST">
                    @csrf

                    {{-- NIS --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Induk Siswa (NIS)</label>
                        <input type="text" name="nis" value="{{ old('nis') }}" required
                            class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full"
                            placeholder="Contoh: 12345678">
                    </div>

                    {{-- Kelas --}}
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kelas Saat Ini</label>
                        <input type="text" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" required
                            class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                            placeholder="Contoh: XII RPL 1">
                    </div>

                    {{-- No WA --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor WhatsApp</label>
                        <input type="number" name="nomor_whatsapp" value="{{ old('nomor_whatsapp') }}" required
                            class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full"
                            placeholder="Contoh: 08123456789">
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" required
                            class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full"
                            placeholder="Masukkan alamat tempat tinggal...">{{ old('alamat') }}</textarea>
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                            Simpan Data & Lanjut Pinjam
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>