<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Diri Saya') }}
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-t-4 border-blue-500">
                
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Biodata Kesiswaan</h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">Siswa Aktif</span>
                    </div>

                    <form action="{{ route('siswa.profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Info Akun (Read Only) --}}
                            <div class="col-span-1 md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Informasi Akun (Tidak dapat diubah disini)</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-1">Nama Lengkap</label>
                                        <input type="text" value="{{ Auth::user()->name }}" disabled 
                                            class="w-full bg-gray-200 text-gray-500 border-gray-300 rounded-md cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-1">Email</label>
                                        <input type="text" value="{{ Auth::user()->email }}" disabled 
                                            class="w-full bg-gray-200 text-gray-500 border-gray-300 rounded-md cursor-not-allowed">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">*Untuk mengubah Nama/Email, silakan ke menu Edit Profil Akun.</p>
                            </div>

                            {{-- NIS (Read Only) --}}
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">NIS (Nomor Induk Siswa)</label>
                                <input type="text" value="{{ $siswa->nis }}" disabled
                                    class="w-full bg-gray-100 text-gray-500 border-gray-300 rounded-md cursor-not-allowed"
                                    title="Hubungi Pustakawan jika NIS salah">
                                <p class="text-xs text-red-400 mt-1">*NIS tidak dapat diubah sendiri.</p>
                            </div>

                            {{-- Kelas (Editable) --}}
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kelas Saat Ini</label>
                                <select name="kelas" required
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                    {{-- Tampilkan kelas saat ini sebagai default --}}
                                    <option value="{{ $siswa->kelas }}" selected>{{ $siswa->kelas }} (Saat Ini)</option>
                                    <option disabled>──────────</option>
                                    
                                    <option value="VII">VII</option>
                                    <option value="VIII">VIII</option>
                                    <option value="IX">IX</option>
                                </select>
                            </div>

                            {{-- No WA (Editable) --}}
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor WhatsApp</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    </div>
                                    <input type="number" name="nomor_whatsapp" value="{{ old('nomor_whatsapp', $siswa->nomor_whatsapp) }}" required
                                        class="pl-10 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                        placeholder="Contoh: 08123456789">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pastikan nomor aktif agar dapat dihubungi pustakawan.</p>
                            </div>

                            {{-- Alamat (Editable) --}}
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap</label>
                                <textarea name="alamat" rows="3" required
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">{{ old('alamat', $siswa->alamat) }}</textarea>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>