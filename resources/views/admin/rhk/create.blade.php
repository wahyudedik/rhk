<x-admin-layout>
    <x-slot name="title">Tambah RHK</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-2 mb-5">
            <a href="{{ route('admin.rhk.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah RHK</span>
        </div>

        <form action="{{ route('admin.rhk.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- RHK --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Data RHK</h3>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                    <input type="number" name="urutan" value="{{ old('urutan') }}" min="1" required
                        class="w-32 px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('urutan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Nama RHK <span class="text-red-500">*</span></label>
                    <textarea name="nama" rows="3" required
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                        placeholder="Nama RHK...">{{ old('nama') }}</textarea>
                    @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Jenis RHK --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6"
                 x-data="jenisRhkManager({{ json_encode(old('jenis', [])) }})">

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Jenis RHK</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tambahkan satu atau lebih jenis kegiatan untuk RHK ini</p>
                    </div>
                    <button type="button" @click="addRow()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition border border-blue-200 dark:border-blue-800">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Jenis
                    </button>
                </div>

                {{-- Empty state --}}
                <div x-show="rows.length === 0" class="py-8 text-center border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                    <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Belum ada jenis RHK. Klik "Tambah Jenis" untuk mulai.</p>
                </div>

                {{-- Rows --}}
                <div class="space-y-3" x-show="rows.length > 0">
                    <template x-for="(row, index) in rows" :key="row.key">
                        <div class="flex gap-3 items-start p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                            {{-- Urutan --}}
                            <div class="w-20 shrink-0">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Urutan</label>
                                <input type="number" :name="`jenis[${index}][urutan]`" x-model="row.urutan" min="1" required
                                    class="w-full px-2.5 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            {{-- Nama --}}
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Jenis RHK <span class="text-red-500">*</span></label>
                                <textarea :name="`jenis[${index}][nama]`" x-model="row.nama" rows="2" required
                                    class="w-full px-2.5 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                                    placeholder="Nama jenis kegiatan..."></textarea>
                            </div>
                            {{-- Hapus --}}
                            <button type="button" @click="removeRow(index)"
                                class="mt-5 p-1.5 text-gray-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950 rounded-lg transition shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                @error('jenis') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                @error('jenis.*.nama') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pb-4">
                <a href="{{ route('admin.rhk.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
                    Simpan RHK
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function jenisRhkManager(initial) {
            return {
                rows: initial.length
                    ? initial.map((r, i) => ({ key: i, urutan: r.urutan ?? i + 1, nama: r.nama ?? '' }))
                    : [],
                nextKey: initial.length,

                addRow() {
                    this.rows.push({ key: this.nextKey++, urutan: this.rows.length + 1, nama: '' });
                },

                removeRow(index) {
                    this.rows.splice(index, 1);
                    // Re-number urutan
                    this.rows.forEach((r, i) => { r.urutan = i + 1; });
                }
            }
        }
    </script>
    @endpush
</x-admin-layout>
