<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Siswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            {{-- Tombol Kembali --}}
            <a href="{{ route('pustakawan.siswa.index') }}" class="flex items-center text-gray-600 hover:text-green-600 mb-4 font-semibold">
                &larr; Kembali ke Daftar
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-t-4 border-green-500">
                <h3 class="text-lg font-bold mb-6 text-gray-700">Edit Informasi Siswa</h3>

                <form action="{{ route('pustakawan.siswa.update', $siswa->id_siswa) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">NIS</label>
                            <input type="number" name="nis" value="{{ old('nis', $siswa->nis) }}" required
                                class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                            <input type="text" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" required
                                class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full"
                                placeholder="Contoh: XII RPL 1">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor WhatsApp</label>
                        <input type="number" name="nomor_whatsapp" value="{{ old('nomor_whatsapp', $siswa->nomor_whatsapp) }}" required
                            class="shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md w-full">
                    </div>

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