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

                    {{-- Kelas (Gunakan SELECT agar seragam) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kelas Saat Ini</label>
                        {{-- Opsi input manual jika ingin input text biasa: --}}
                        {{-- <input type="text" name="kelas" value="{{ old('kelas') }}" required ... > --}}
                        
                        {{-- Saran saya: Gunakan Select agar rapi --}}
                        <select name="kelas" required
                            class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                            <option value="">-- Pilih Kelas --</option>
                            <option value="VII" {{ old('kelas') == 'VII' ? 'selected' : '' }}>VII</option>
                            <option value="VIII" {{ old('kelas') == 'VIII' ? 'selected' : '' }}>VIII</option>
                            <option value="IX" {{ old('kelas') == 'IX' ? 'selected' : '' }}>IX</option>
                        </select>
                    </div>

                    {{-- No WA --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor WhatsApp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm font-bold">+62</span>
                            </div>
                            <input type="number" name="nomor_whatsapp" value="{{ old('nomor_whatsapp') }}" required
                                class="pl-12 shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full"
                                placeholder="8123456789">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Masukkan angka saja, contoh: 81234567890 (Tanpa angka 0 di depan)</p>
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
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 shadow-md transform hover:-translate-y-0.5">
                            Simpan Data & Lanjut Pinjam
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>