<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Buku Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                {{-- Preview Image Script --}}
                <script>
                    function previewImage() {
                        const image = document.querySelector('#url_sampul');
                        const imgPreview = document.querySelector('#img-preview');
                        const placeholder = document.querySelector('#placeholder-text');

                        if (image.files && image.files[0]) {
                            const oFReader = new FileReader();
                            oFReader.readAsDataURL(image.files[0]);

                            oFReader.onload = function(oFREvent) {
                                imgPreview.src = oFREvent.target.result;
                                imgPreview.classList.remove('hidden');
                                imgPreview.classList.add('block');
                                if(placeholder) placeholder.style.display = 'none';
                            }
                        }
                    }
                </script>

                {{-- Pesan Error Validasi --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form Tambah Buku --}}
                <form action="{{ route('pustakawan.buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    {{-- Judul Buku --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Judul Buku <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul buku"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>

                    {{-- [TAMBAHAN BARU] Kategori Buku --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Kategori Buku <span class="text-red-500">*</span></label>
                        <select name="kategori" required 
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Buku Pelajaran" {{ old('kategori') == 'Buku Pelajaran' ? 'selected' : '' }}>Buku Pelajaran</option>
                            <option value="Novel" {{ old('kategori') == 'Novel' ? 'selected' : '' }}>Novel</option>
                            <option value="Komik" {{ old('kategori') == 'Komik' ? 'selected' : '' }}>Komik</option>
                            <option value="Ensiklopedia" {{ old('kategori') == 'Ensiklopedia' ? 'selected' : '' }}>Ensiklopedia</option>
                            <option value="Biografi" {{ old('kategori') == 'Biografi' ? 'selected' : '' }}>Biografi</option>
                            <option value="Agama" {{ old('kategori') == 'Agama' ? 'selected' : '' }}>Agama</option>
                            <option value="Majalah" {{ old('kategori') == 'Majalah' ? 'selected' : '' }}>Majalah</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    {{-- Penulis --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Penulis</label>
                        <input type="text" name="penulis" value="{{ old('penulis') }}" placeholder="Masukkan nama penulis"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    {{-- Penerbit --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Penerbit</label>
                        <input type="text" name="penerbit" value="{{ old('penerbit') }}" placeholder="Masukkan nama penerbit"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    {{-- Tahun & Stok --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" placeholder="contoh: 2020"
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1">Jumlah Stok <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_stok" value="{{ old('jumlah_stok') }}" placeholder="contoh: 10"
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                    </div>

                    {{-- ISBN --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}" placeholder="Masukkan nomor ISBN"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Masukkan deskripsi singkat buku"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- Rak --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Rak</label>
                        <input type="text" name="rak" value="{{ old('rak') }}" placeholder="Masukkan lokasi rak buku"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    {{-- Upload Sampul --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Upload Sampul Buku</label>
                        
                        <!-- Area Preview Gambar -->
                        <div class="mb-3">
                            <img id="img-preview" 
                                class="hidden w-32 h-48 object-cover rounded-md border-2 border-gray-300 shadow-md" 
                                alt="Preview Sampul">
                                
                            <p id="placeholder-text" class="text-xs text-gray-500 italic mt-1">*Preview akan muncul setelah memilih file.</p>
                        </div>

                        <!-- Input File -->
                        <input type="file" 
                            name="url_sampul" 
                            id="url_sampul"
                            accept="image/*" 
                            onchange="previewImage()"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition">
                        
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Maks: 2MB.</p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                        <a href="{{ route('pustakawan.buku.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-md">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>