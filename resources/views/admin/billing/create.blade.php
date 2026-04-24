<x-admin-layout>
    <x-slot name="title">Tambah Paket</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-2 mb-5">
            <a href="{{ route('admin.billing.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah Paket</span>
        </div>

        <form action="{{ route('admin.billing.store') }}" method="POST"
              class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Nama Paket <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Contoh: Professional">
                    @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="professional">
                    @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" value="{{ old('harga', 0) }}" min="0" required
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('harga') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Durasi (hari) <span class="text-red-500">*</span></label>
                    <input type="number" name="durasi_hari" value="{{ old('durasi_hari', 30) }}" min="1" required
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('durasi_hari') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 1) }}" min="0"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('urutan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                    Batas Laporan per Bulan
                    <span class="text-gray-400 font-normal">(kosongkan = unlimited)</span>
                </label>
                <input type="number" name="batas_laporan_per_bulan" value="{{ old('batas_laporan_per_bulan') }}" min="1"
                    class="w-40 px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Contoh: 20">
                @error('batas_laporan_per_bulan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                    Fitur Paket
                    <span class="text-gray-400 font-normal">(satu fitur per baris)</span>
                </label>
                <textarea name="fitur" rows="5"
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                    placeholder="Akses penuh selama 30 hari&#10;Maksimal 20 laporan per bulan&#10;Semua jenis RHK tersedia">{{ old('fitur') }}</textarea>
                @error('fitur') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_trial" value="1" {{ old('is_trial') ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Paket Trial</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Aktif (tampil di landing page)</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.billing.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">
                    Simpan Paket
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
