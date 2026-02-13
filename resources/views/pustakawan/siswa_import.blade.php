<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Import Data Siswa
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
            
            <a href="{{ route('pustakawan.siswa.index') }}" class="flex items-center text-gray-600 hover:text-green-600 mb-4 font-semibold">
                &larr; Kembali ke Daftar
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-t-4 border-green-500">
                <h3 class="text-lg font-bold mb-2 text-gray-700">Upload File Excel</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Pastikan format Excel sesuai. Password akun siswa otomatis menggunakan <b>NIS</b>.
                </p>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <p class="text-sm text-yellow-700 font-bold">Aturan File Excel:</p>
                    <ul class="list-disc list-inside text-xs text-yellow-700 mt-1">
                        <li>Baris pertama harus Judul Kolom (Header).</li>
                        <li>Nama Kolom: <b>nama, email, nis, kelas, nomor_wa, alamat</b> (Huruf kecil semua).</li>
                        <li>Pastikan Email dan NIS belum pernah terdaftar.</li>
                    </ul>
                </div>

                <form action="{{ route('pustakawan.siswa.import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih File (.xlsx / .xls)</label>
                        <input type="file" name="file" required
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                            Import Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>