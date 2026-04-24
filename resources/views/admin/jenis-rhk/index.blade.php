<x-admin-layout>
    <x-slot name="title">Jenis RHK</x-slot>

    <div class="flex items-center justify-between mb-5">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.rhk.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">RHK</a>
                <span class="text-gray-300 dark:text-gray-600">/</span>
                <span class="text-sm text-gray-900 dark:text-white font-medium">Jenis RHK</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Jenis RHK</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 max-w-lg line-clamp-1">{{ $rhk->nama }}</p>
        </div>
        <a href="{{ route('admin.rhk.jenis-rhk.create', $rhk) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if ($jenisRhks->isEmpty())
            <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada jenis RHK.</div>
        @else
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Jenis RHK</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($jenisRhks as $jenis)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-5 py-4">
                                <span class="w-7 h-7 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-bold rounded-lg flex items-center justify-center">
                                    {{ $jenis->urutan }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-800 dark:text-gray-200 leading-snug">{{ $jenis->nama }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.jenis-rhk.edit', $jenis) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.jenis-rhk.destroy', $jenis) }}" method="POST"
                                          onsubmit="return confirm('Hapus jenis RHK ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-lg hover:bg-red-100 dark:hover:bg-red-900 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $jenisRhks->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
