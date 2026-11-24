<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Catat Transaksi Laundry Baru') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Masukkan data pelanggan, pilih jenis layanan, dan timbang berat cucian.</p>
            </div>
            <a href="{{ route('transaksi.index') }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xs border border-gray-200">
                <form method="POST" action="{{ route('transaksi.store') }}" class="space-y-6" id="transaksiForm">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Nama Pelanggan -->
                        <div>
                            <x-input-label for="nama_pelanggan" :value="__('Nama Pelanggan')" />
                            <x-text-input id="nama_pelanggan" name="nama_pelanggan" type="text" class="mt-1 block w-full" :value="old('nama_pelanggan')" required autofocus placeholder="Contoh: Budi Santoso" />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_pelanggan')" />
                        </div>

                        <!-- No HP / WhatsApp -->
                        <div>
                            <x-input-label for="no_hp" :value="__('No. HP / WhatsApp')" />
                            <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp')" required placeholder="Contoh: 081234567890" />
                            <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
                        </div>
                    </div>

                    <!-- Jenis Layanan (Dropdown) -->
                    <div>
                        <x-input-label for="layanan_id" :value="__('Pilih Jenis Layanan Cuci')" />
                        <select id="layanan_id" name="layanan_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-xs focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="" data-harga="0" data-durasi="">-- Silakan Pilih Layanan --</option>
                            @foreach ($daftarLayanan as $layanan)
                                <option value="{{ $layanan->id }}" data-harga="{{ (float) $layanan->harga_per_kg }}" data-durasi="{{ $layanan->durasi }}" {{ old('layanan_id') == $layanan->id ? 'selected' : '' }}>
                                    {{ $layanan->nama_layanan }} — {{ $layanan->harga_rupiah }}/kg (Estimasi: {{ $layanan->durasi }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('layanan_id')" />
                        <p id="layananInfo" class="text-xs text-blue-600 mt-1 italic"></p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Berat (kg) -->
                        <div>
                            <x-input-label for="berat" :value="__('Berat Cucian (kg)')" />
                            <div class="relative mt-1">
                                <x-text-input id="berat" name="berat" type="number" step="0.01" min="0.01" max="9999" class="block w-full pr-12" :value="old('berat', '1')" required placeholder="Contoh: 3.5" />
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 text-sm font-semibold pointer-events-none">kg</span>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('berat')" />
                            <p class="text-xs text-gray-500 mt-1">Mendukung bilangan desimal, misal 2.5 atau 3.7 kg.</p>
                        </div>

                        <!-- Live Calculated Total Harga Preview -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Estimasi Total Biaya</label>
                            <div class="mt-1 p-2.5 bg-blue-50 border border-blue-200 rounded-md flex items-center justify-between">
                                <span class="text-xs text-blue-900 font-medium">Total:</span>
                                <span id="totalHargaDisplay" class="text-lg font-bold text-blue-700">Rp 0</span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">Dihitung otomatis: tarif per kg × berat timbangan.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Status Pengerjaan -->
                        <div>
                            <x-input-label for="status" :value="__('Status Pengerjaan')" />
                            <select id="status" name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-xs focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="Masuk" {{ old('status', 'Masuk') === 'Masuk' ? 'selected' : '' }}>Masuk (Menunggu Antrian)</option>
                                <option value="Proses" {{ old('status') === 'Proses' ? 'selected' : '' }}>Proses (Sedang Dicuci / Disetrika)</option>
                                <option value="Selesai" {{ old('status') === 'Selesai' ? 'selected' : '' }}>Selesai (Siap Diambil)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <!-- Status Pembayaran -->
                        <div>
                            <x-input-label for="status_pembayaran" :value="__('Status Pembayaran')" />
                            <select id="status_pembayaran" name="status_pembayaran" required class="mt-1 block w-full rounded-md border-gray-300 shadow-xs focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="Belum Lunas" {{ old('status_pembayaran', 'Belum Lunas') === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas (Bayar saat ambil)</option>
                                <option value="Lunas" {{ old('status_pembayaran') === 'Lunas' ? 'selected' : '' }}>Lunas (Sudah dibayar di muka)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status_pembayaran')" />
                        </div>
                    </div>

                    <!-- Catatan Khusus -->
                    <div>
                        <x-input-label for="catatan" :value="__('Catatan Khusus (Opsional)')" />
                        <textarea id="catatan" name="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-xs focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Contoh: Baju putih dipisah, jangan pakai pemutih klorin, pewangi mawar, dll.">{{ old('catatan') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('transaksi.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Simpan & Cetak Struk
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateLiveTotal() {
            const layananSelect = document.getElementById('layanan_id');
            const beratInput = document.getElementById('berat');
            const totalDisplay = document.getElementById('totalHargaDisplay');
            const layananInfo = document.getElementById('layananInfo');

            const selectedOption = layananSelect.options[layananSelect.selectedIndex];
            const hargaPerKg = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
            const durasi = selectedOption.getAttribute('data-durasi') || '';
            const berat = parseFloat(beratInput.value) || 0;

            if (durasi) {
                layananInfo.textContent = `Estimasi pengerjaan: ${durasi}`;
            } else {
                layananInfo.textContent = '';
            }

            const total = Math.round(hargaPerKg * berat);
            totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.getElementById('layanan_id').addEventListener('change', updateLiveTotal);
        document.getElementById('berat').addEventListener('input', updateLiveTotal);

        // Run once on initial load
        document.addEventListener('DOMContentLoaded', updateLiveTotal);
    </script>
</x-app-layout>
