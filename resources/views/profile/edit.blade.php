<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    {{-- ============================================================== --}}
    {{-- NOTIFIKASI SWEETALERT (ANTI ERROR VS CODE) --}}
    {{-- ============================================================== --}}
    
    <!-- 1. Simpan pesan Session di dalam DIV tersembunyi sebagai atribut -->
    <div id="flash-data" 
         data-status="{{ session('status') }}" 
         data-success="{{ session('success') }}" 
         data-error="{{ session('error') }}">
    </div>

    <!-- 2. Library SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 3. Script JS Murni -->
    <script>
        const flashData = document.getElementById('flash-data');
        const status = flashData.dataset.status;
        const successMsg = flashData.dataset.success;
        const errorMsg = flashData.dataset.error;

        // Cek Status Bawaan Breeze
        if (status === 'profile-updated') {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Informasi profil berhasil diperbarui.',
                icon: 'success',
                confirmButtonColor: '#10B981',
                confirmButtonText: 'Oke'
            });
        } else if (status === 'password-updated') {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Password berhasil diubah.',
                icon: 'success',
                confirmButtonColor: '#10B981',
                confirmButtonText: 'Oke'
            });
        }
        
        // Cek Pesan Sukses Custom
        else if (successMsg) {
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Update Profil --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-200 border-t-4 border-t-green-500">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-200 border-t-4 border-t-blue-500">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete User --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-200 border-t-4 border-t-red-500">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>