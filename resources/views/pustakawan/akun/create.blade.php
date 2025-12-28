<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pustakawan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-blue-500">
                <div class="p-8 text-gray-900">
                    
                    <h3 class="text-lg font-bold mb-6 text-gray-700">Formulir Pendaftaran Admin</h3>

                    <form action="{{ route('pustakawan.akun.store') }}" method="POST">
                        @csrf

                        {{-- Nama --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-4" x-data="{ showPassword: false }">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                            <div class="relative">
                                <input type="password" 
                                       name="password" 
                                       :type="showPassword ? 'text' : 'password'" 
                                       required
                                       class="w-full pr-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                       placeholder="Minimal 8 karakter">
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none transition">
                                    <!-- Ikon Mata (Muncul saat password tersembunyi) -->
                                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Ikon Mata Dicoret (Muncul saat password terlihat) -->
                                    <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-6" x-data="{ showConfirmPassword: false }">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" 
                                       name="password_confirmation" 
                                       :type="showConfirmPassword ? 'text' : 'password'" 
                                       required
                                       class="w-full pr-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                       placeholder="Ulangi password">
                                <button type="button" 
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none transition">
                                    <!-- Ikon Mata (Muncul saat password tersembunyi) -->
                                    <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Ikon Mata Dicoret (Muncul saat password terlihat) -->
                                    <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('pustakawan.akun.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-bold transition">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold shadow transition">
                                Simpan Akun
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>