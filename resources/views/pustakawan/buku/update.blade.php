<x-app-layout>
    <div class="p-6">
        <div class="bg-white shadow-lg rounded-xl p-6 max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-700 mb-6 border-b pb-2">
                Edit Buku
            </h2>

            {{-- ============================================================== --}}
            {{-- NOTIFIKASI SWEETALERT --}}
            {{-- ============================================================== --}}
            
            <!-- 1. Simpan pesan Session di dalam DIV tersembunyi sebagai atribut -->
            <div id="flash-data" 
                 data-success="{{ session('success') }}" 
                 data-error="{{ session('error') }}">
            </div>

            <!-- 2. Library SweetAlert -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <!-- 3. Script JS Murni -->
            <script>
                const flashData = document.getElementById('flash-data');
                const successMsg = flashData.dataset.success;
                const errorMsg = flashData.dataset.error;

                // Cek Pesan Sukses
                if (successMsg) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: successMsg,
                        icon: 'success',
                        confirmButtonColor: '#10B981',
                        confirmButtonText: 'Oke'
                    });
                }

                // Cek Pesan Error
                if (errorMsg) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonColor: '#EF4444',
                        confirmButtonText: 'Tutup'
                    });
                }
            </script>
            {{-- ============================================================== --}}

            {{-- Form Update Buku --}}
            <form action="{{ route('pustakawan.buku.update', $buku->id_buku) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Judul Buku --}}
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Judul Buku</label>
                    <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" placeholder="Masukkan judul buku"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Penulis --}}
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}" placeholder="Masukkan nama penulis"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Penerbit --}}
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Penerbit</label>
                    <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" placeholder="Masukkan nama penerbit"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Tahun & Stok --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" placeholder="contoh: 2020"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Jumlah Stok</label>
                        <input type="number" name="jumlah_stok" value="{{ old('jumlah_stok', $buku->jumlah_stok) }}" placeholder="contoh: 10"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                {{-- ISBN --}}
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $buku->isbn) }}" placeholder="Masukkan nomor ISBN"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Masukkan deskripsi singkat buku"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                </div>

                {{-- Rak --}}
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Rak</label>
                    <input type="text" name="rak" value="{{ old('rak', $buku->rak) }}" placeholder="Masukkan lokasi rak buku"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Upload Sampul --}}
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Upload Sampul Buku</label>
                    @if($buku->url_sampul)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $buku->url_sampul) }}" alt="Sampul Buku" class="h-32 rounded-md shadow-sm">
                        </div>
                    @endif
                    <input type="file" name="url_sampul"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                    <a href="{{ route('pustakawan.buku.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-md">
                    Batal
                    </a>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
