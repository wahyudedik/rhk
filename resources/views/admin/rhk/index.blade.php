<x-admin-layout>
    <x-slot name="title">Manajemen RHK</x-slot>

    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">RHK</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Rencana Hasil Kerja PKH</p>
        </div>
        <a href="{{ route('admin.rhk.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah RHK
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama RHK</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28 hidden sm:table-cell">Jenis</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($rhks as $rhk)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                        <td class="px-5 py-4">
                            <span class="w-7 h-7 bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400 text-xs font-bold rounded-lg flex items-center justify-center">
                                {{ $rhk->urutan }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-800 dark:text-gray-200 leading-snug">{{ $rhk->nama }}</td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            <a href="{{ route('admin.rhk.jenis-rhk.index', $rhk) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition">
                                {{ $rhk->jenis_rhks_count }} jenis
                            </a>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.rhk.edit', $rhk) }}"
                                   class="px-3 py-1.5 text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.rhk.destroy', $rhk) }}" method="POST"
                                      onsubmit="return confirm('Hapus RHK ini beserta semua jenis RHK-nya?')">
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
            {{ $rhks->links() }}
        </div>
    </div>
</x-admin-layout>
