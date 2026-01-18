<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Siswa') }}
        </h2>
    </x-slot>

    {{-- Notifikasi --}}
    <div id="flash-data" data-success="{{ session('success') }}" data-error="{{ session('error') }}"></div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const flashData = document.getElementById('flash-data');
        if (flashData.dataset.success) Swal.fire('Berhasil!', flashData.dataset.success, 'success');
        if (flashData.dataset.error) Swal.fire('Gagal!', flashData.dataset.error, 'error');
    </script>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            {{-- Tombol Kembali --}}
            <a href="{{ route('pustakawan.siswa.index') }}" class="flex items-center text-gray-600 hover:text-green-600 mb-4 font-semibold">
                &larr; Kembali ke Daftar
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-t-4 border-green-500">
                <h3 class="text-lg font-bold mb-6 text-gray-700">Form Edit Siswa</h3>

                <form action="{{ route('pustakawan.siswa.update', $siswa->id_siswa) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Menampilkan Error Validasi --}}
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

                    {{-- Data Akun (Tabel User) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $siswa->user->name) }}" required
                            class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $siswa->user->email) }}" required
                            class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full">
                    </div>

                    <hr class="my-6 border-gray-200">

                    {{-- Data Sekolah (Tabel Siswa) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        {{-- NIS --}}
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">NIS</label>
                            <input type="number" name="nis" value="{{ old('nis', $siswa->nis) }}" required
                                class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full">
                        </div>
                        
                        {{-- Kelas (DROPDOWN VII, VIII, IX) --}}
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                            <select name="kelas" required
                                class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full">
                                <option value="">-- Pilih Kelas --</option>
                                
                                {{-- Opsi Kelas VII --}}
                                <option value="VII" {{ old('kelas', $siswa->kelas) == 'VII' ? 'selected' : '' }}>Kelas VII</option>
                                
                                {{-- Opsi Kelas VIII --}}
                                <option value="VIII" {{ old('kelas', $siswa->kelas) == 'VIII' ? 'selected' : '' }}>Kelas VIII</option>
                                
                                {{-- Opsi Kelas IX --}}
                                <option value="IX" {{ old('kelas', $siswa->kelas) == 'IX' ? 'selected' : '' }}>Kelas IX</option>

                                {{-- Opsi Tambahan jika data lama tidak sesuai standar --}}
                                @if(!in_array($siswa->kelas, ['VII', 'VIII', 'IX']) && $siswa->kelas)
                                    <option value="{{ $siswa->kelas }}" selected>{{ $siswa->kelas }} (Lama)</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- No WA (HANYA ANGKA) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor WhatsApp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </div>
                            
                            {{-- INPUT DIBATASI ANGKA SAJA --}}
                            <input type="text" 
                                   name="nomor_whatsapp" 
                                   value="{{ old('nomor_whatsapp', $siswa->nomor_whatsapp) }}" 
                                   required
                                   inputmode="numeric" 
                                   pattern="[0-9]*"
                                   maxlength="14"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="pl-10 w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm"
                                   placeholder="Contoh: 08123456789">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Hanya angka (tanpa spasi atau simbol).</p>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                        <textarea name="alamat" rows="3" required
                            class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full">{{ old('alamat', $siswa->alamat) }}</textarea>
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>