<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Ubah Jenis Layanan') }} : {{ $layanan->nama_layanan }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui tarif per kilogram, nama layanan, durasi atau deskripsi.</p>
            </div>
            <a href="{{ route('layanan.index') }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors w-fit">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xs border border-gray-200">
                <form method="POST" action="{{ route('layanan.update', $layanan) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Layanan -->
                    <div>
                        <x-input-label for="nama_layanan" :value="__('Nama Jenis Layanan')" />
                        <x-text-input id="nama_layanan" name="nama_layanan" type="text" class="mt-1 block w-full" :value="old('nama_layanan', $layanan->nama_layanan)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_layanan')" />
                    </div>

                    <!-- Harga per Kg -->
                    <div>
                        <x-input-label for="harga_per_kg" :value="__('Tarif per Kilogram (Rp)')" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm font-semibold">Rp</span>
                            <x-text-input id="harga_per_kg" name="harga_per_kg" type="number" step="any" min="0" class="pl-10 block w-full" :value="old('harga_per_kg', (float) $layanan->harga_per_kg)" required />
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('harga_per_kg')" />
                    </div>

                    <!-- Durasi -->
                    <div>
                        <x-input-label for="durasi" :value="__('Estimasi Durasi Pengerjaan')" />
                        <x-text-input id="durasi" name="durasi" type="text" class="mt-1 block w-full" :value="old('durasi', $layanan->durasi)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('durasi')" />
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <x-input-label for="deskripsi" :value="__('Deskripsi Layanan')" />
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-xs focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('deskripsi')" />
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('layanan.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
