<x-admin-layout>
    <x-slot name="title">Edit Jenis RHK</x-slot>

    <div class="max-w-lg">
        <div class="flex items-center gap-2 mb-5">
            <a href="{{ route('admin.rhk.jenis-rhk.index', $jenisRhk->rhk) }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Edit Jenis RHK</span>
        </div>

        <div class="mb-4 p-3.5 bg-blue-50 dark:bg-blue-950 rounded-xl border border-blue-200 dark:border-blue-800 text-sm text-blue-800 dark:text-blue-300">
            <span class="font-medium">RHK:</span> {{ Str::limit($jenisRhk->rhk->nama, 80) }}
        </div>

        <form action="{{ route('admin.jenis-rhk.update', $jenisRhk) }}" method="POST"
              class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                <input type="number" name="urutan" value="{{ old('urutan', $jenisRhk->urutan) }}" min="1" required
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                @error('urutan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Nama Jenis RHK <span class="text-red-500">*</span></label>
                <textarea name="nama" rows="3" required
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none">{{ old('nama', $jenisRhk->nama) }}</textarea>
                @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.rhk.jenis-rhk.index', $jenisRhk->rhk) }}"
                   class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">Batal</a>
                <button type="submit"
                    class="px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">Perbarui</button>
            </div>
        </form>
    </div>
</x-admin-layout>
