<x-app-layout>
    <x-slot name="title">History Laporan</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">History Laporan</h2>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-amber-50 dark:bg-amber-950 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ session('warning') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">History Laporan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                Total <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $totalLaporan }}</span> laporan tersimpan
            </p>
        </div>
        <a href="{{ route('laporan.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Laporan
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('laporan.index') }}"
          class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 mb-4">
        <div class="flex flex-wrap gap-3 items-end">
            {{-- Bulan --}}
            <div class="flex-1 min-w-32">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Bulan</label>
                <select name="bulan"
                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                        <option value="{{ $bulan }}" {{ request('bulan') === $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun --}}
            <div class="flex-1 min-w-28">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tahun</label>
                <select name="tahun"
                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>

            {{-- RHK --}}
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">RHK</label>
                <select name="rhk_id"
                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Semua RHK</option>
                    @foreach ($rhkList as $rhk)
                        <option value="{{ $rhk->id }}" {{ request('rhk_id') == $rhk->id ? 'selected' : '' }}>
                            {{ $rhk->urutan }}. {{ Str::limit($rhk->nama, 50) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">
                    Filter
                </button>
                @if (request()->hasAny(['bulan', 'tahun', 'rhk_id']))
                    <a href="{{ route('laporan.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if ($laporans->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-16 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            @if (request()->hasAny(['bulan', 'tahun', 'rhk_id']))
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Tidak ada laporan ditemukan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Coba ubah filter pencarian Anda.</p>
                <a href="{{ route('laporan.index') }}" class="text-sm text-blue-600 hover:underline">Hapus filter</a>
            @else
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Belum ada laporan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Mulai buat laporan SKP pertama Anda sekarang.</p>
                <a href="{{ route('laporan.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Laporan Pertama
                </a>
            @endif
        </div>
    @else

        {{-- Desktop table --}}
        <div class="hidden md:block bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-10">No</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periode</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis RHK / Kegiatan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($laporans as $laporan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition group">
                            <td class="px-5 py-4 text-sm text-gray-400">
                                {{ $laporans->firstItem() + $loop->index }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-lg whitespace-nowrap">
                                    {{ $laporan->bulan }} {{ $laporan->tahun }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">{{ $laporan->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-5 py-4 max-w-sm">
                                <p class="text-sm font-medium text-gray-900 dark:text-white line-clamp-1 leading-snug">
                                    {{ $laporan->jenisRhk->nama }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">
                                    {{ $laporan->rhk->urutan }}. {{ $laporan->rhk->nama }}
                                </p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('laporan.show', $laporan) }}"
                                       class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950 rounded-lg transition"
                                       title="Lihat detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    @if (!$laporan->file_pdf && !$laporan->file_docx)
                                    <a href="{{ route('laporan.edit', $laporan) }}"
                                       class="p-1.5 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950 rounded-lg transition"
                                       title="Edit laporan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @endif
                                    @if ($laporan->file_dokumen)
                                        <a href="{{ Storage::url($laporan->file_dokumen) }}" target="_blank"
                                           class="p-1.5 text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-950 rounded-lg transition"
                                           title="Download dokumen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    @endif
                                    {{-- Download PDF --}}
                                    <a href="{{ route('laporan.download.pdf', $laporan) }}"
                                       class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950 rounded-lg transition"
                                       title="Download PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </a>
                                    {{-- Download DOCX --}}
                                    <a href="{{ route('laporan.download.docx', $laporan) }}"
                                       class="p-1.5 text-gray-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-950 rounded-lg transition"
                                       title="Download Word (DOCX)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('laporan.destroy', $laporan) }}" method="POST"
                                          onsubmit="return confirm('Hapus laporan {{ addslashes($laporan->jenisRhk->nama) }} ({{ $laporan->bulan }} {{ $laporan->tahun }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950 rounded-lg transition"
                                            title="Hapus laporan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Menampilkan {{ $laporans->firstItem() }}–{{ $laporans->lastItem() }} dari {{ $laporans->total() }} laporan
                </p>
                {{ $laporans->links() }}
            </div>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden space-y-3">
            @foreach ($laporans as $laporan)
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
                    {{-- Header card --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-lg mb-2">
                                {{ $laporan->bulan }} {{ $laporan->tahun }}
                            </span>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 leading-snug">
                                {{ $laporan->jenisRhk->nama }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">
                                {{ $laporan->rhk->urutan }}. {{ $laporan->rhk->nama }}
                            </p>
                        </div>
                    </div>

                    {{-- Meta --}}
                    <div class="flex items-center gap-3 mb-3 text-xs text-gray-400">
                        <span>Dibuat {{ $laporan->created_at->format('d M Y') }}</span>
                        @if ($laporan->file_dokumen)
                            <span class="flex items-center gap-1 text-green-600 dark:text-green-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Ada dokumen
                            </span>
                        @endif
                    </div>

                    {{-- Aksi --}}
                    <div class="grid grid-cols-4 gap-2">
                        <a href="{{ route('laporan.show', $laporan) }}"
                           class="flex flex-col items-center gap-1 py-2.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat
                        </a>
                        @if (!$laporan->file_pdf && !$laporan->file_docx)
                        <a href="{{ route('laporan.edit', $laporan) }}"
                           class="flex flex-col items-center gap-1 py-2.5 text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950 rounded-xl hover:bg-amber-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        @endif
                        <a href="{{ route('laporan.download.pdf', $laporan) }}"
                           class="flex flex-col items-center gap-1 py-2.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-xl hover:bg-red-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            PDF
                        </a>
                        <a href="{{ route('laporan.download.docx', $laporan) }}"
                           class="flex flex-col items-center gap-1 py-2.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Word
                        </a>
                    </div>
                    <div class="mt-2">
                        <form action="{{ route('laporan.destroy', $laporan) }}" method="POST"
                              onsubmit="return confirm('Hapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-1.5 py-2 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-xl hover:bg-red-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Laporan
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="pt-2 flex items-center justify-between">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $laporans->firstItem() }}–{{ $laporans->lastItem() }} dari {{ $laporans->total() }}
                </p>
                {{ $laporans->links() }}
            </div>
        </div>

    @endif
</x-app-layout>
