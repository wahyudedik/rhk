<x-app-layout>
    <x-slot name="title">Detail Laporan</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Detail Laporan</h2>
    </x-slot>

    @push('styles')
    <style>
        .laporan-content p { margin-bottom: 0.5rem; }
        .laporan-content ul, .laporan-content ol { padding-left: 1.5rem; margin-bottom: 0.5rem; }
        .laporan-content ul { list-style-type: disc; }
        .laporan-content ol { list-style-type: decimal; }
        .laporan-content li { margin-bottom: 0.25rem; }
        .laporan-content strong { font-weight: 600; }
        .laporan-content em { font-style: italic; }
        .laporan-content u { text-decoration: underline; }
        .laporan-content .ql-indent-1 { padding-left: 2rem; }
        .laporan-content .ql-indent-2 { padding-left: 4rem; }
    </style>
    @endpush

    @if (session('success'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-3xl space-y-4">

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('laporan.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('laporan.edit', $laporan) }}"
                   class="px-4 py-2 text-sm font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900 transition">
                    Edit
                </a>
                <a href="{{ route('laporan.download.pdf', $laporan) }}"
                   class="px-4 py-2 text-sm font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-xl hover:bg-red-100 dark:hover:bg-red-900 transition">
                    ↓ PDF
                </a>
                <a href="{{ route('laporan.download.docx', $laporan) }}"
                   class="px-4 py-2 text-sm font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition">
                    ↓ Word
                </a>
                <form action="{{ route('laporan.destroy', $laporan) }}" method="POST"
                      onsubmit="return confirm('Hapus laporan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-xl hover:bg-red-100 dark:hover:bg-red-900 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- Header card --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-950 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-full">
                            {{ $laporan->bulan }} {{ $laporan->tahun }}
                        </span>
                    </div>
                    <h1 class="text-lg font-bold text-gray-900 dark:text-white leading-snug">{{ $laporan->jenisRhk->nama }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $laporan->rhk->nama }}</p>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-800 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Dibuat oleh</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $laporan->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Tanggal dibuat</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $laporan->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Isi laporan --}}
        @php
            $sections = [
                'latar_belakang' => 'A. Latar Belakang / Umum',
                'maksud_tujuan' => 'B. Maksud dan Tujuan',
                'ruang_lingkup' => 'C. Ruang Lingkup',
                'dasar' => 'D. Dasar Hukum',
                'kegiatan_dilaksanakan' => 'E. Kegiatan yang Dilaksanakan',
                'hasil_dicapai' => 'F. Hasil yang Dicapai',
                'simpulan' => 'G. Simpulan',
                'saran' => 'H. Saran',
                'penutup' => 'I. Penutup',
            ];
            $filledSections = array_filter($sections, fn($_, $key) => !empty($laporan->$key), ARRAY_FILTER_USE_BOTH);
        @endphp

        @if (!empty($filledSections))
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($filledSections as $field => $label)
                    <div class="p-5">
                        <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">{{ $label }}</h3>
                        <div class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed prose prose-sm dark:prose-invert max-w-none laporan-content">{!! $laporan->$field !!}</div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Dokumen --}}
        @if ($laporan->file_dokumen)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Dokumen Pendukung</h3>
                <a href="{{ Storage::url($laporan->file_dokumen) }}" target="_blank"
                   class="inline-flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition border border-blue-200 dark:border-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Unduh Dokumen
                </a>
            </div>
        @endif

        {{-- Tanda Tangan --}}
        @if ($laporan->ttd_nama || $laporan->ttd_gambar)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Tanda Tangan</h3>
                <div class="flex justify-end">
                    <div class="text-sm text-gray-800 dark:text-gray-200 text-right">
                        @if ($laporan->ttd_kota || $laporan->ttd_tanggal)
                            <p>Dibuat di {{ $laporan->ttd_kota }},</p>
                            <p>Pada Tanggal {{ $laporan->ttd_tanggal?->translatedFormat('d F Y') }}</p>
                        @endif
                        @if ($laporan->ttd_jabatan)
                            <p class="mt-1">{{ $laporan->ttd_jabatan }}</p>
                        @endif
                        @if ($laporan->ttd_gambar)
                            <div class="my-3 flex justify-end">
                                <img src="{{ Storage::url($laporan->ttd_gambar) }}" alt="Tanda Tangan" class="h-20 object-contain">
                            </div>
                        @else
                            <div class="h-16"></div>
                        @endif
                        @if ($laporan->ttd_nama)
                            <p class="font-semibold underline">{{ $laporan->ttd_nama }}</p>
                        @endif
                        @if ($laporan->ttd_nip)
                            <p>NIP. {{ $laporan->ttd_nip }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Dokumentasi Foto --}}
        @if ($laporan->foto_dokumentasi && count($laporan->foto_dokumentasi) > 0)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">
                    Dokumentasi Foto ({{ count($laporan->foto_dokumentasi) }} foto)
                </h3>
                @if ($laporan->keterangan_dokumentasi)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 italic">{{ $laporan->keterangan_dokumentasi }}</p>
                @endif
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach ($laporan->foto_dokumentasi as $foto)
                        <a href="{{ Storage::url($foto) }}" target="_blank" class="block group">
                            <img src="{{ Storage::url($foto) }}" alt="Dokumentasi"
                                 class="w-full h-36 object-cover rounded-xl border border-gray-200 dark:border-gray-700 group-hover:opacity-90 transition">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
