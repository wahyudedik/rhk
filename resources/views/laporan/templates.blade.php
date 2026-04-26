<x-app-layout>
    <x-slot name="title">Template Laporan</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Template Laporan</h2>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Template Laporan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                Template tersimpan otomatis setiap kali Anda membuat laporan baru.
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

    {{-- Info box --}}
    <div class="mb-5 flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800 rounded-2xl text-sm">
        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-blue-800 dark:text-blue-300">
            <p class="font-medium mb-0.5">Cara menggunakan template</p>
            <p class="text-xs text-blue-600 dark:text-blue-400">Saat membuat laporan baru, klik <strong>"Gunakan Template"</strong> untuk mengisi otomatis isi laporan dari template. Anda hanya perlu mengganti <strong>bulan, tahun, tanggal TTD, dan foto dokumentasi</strong>.</p>
        </div>
    </div>

    @if ($templates->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-16 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Belum ada template</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Template akan tersimpan otomatis saat Anda membuat laporan pertama.</p>
            <a href="{{ route('laporan.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Laporan Pertama
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($templates as $template)
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 flex flex-col gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-purple-50 dark:bg-purple-950 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 leading-snug">
                                {{ $template->template_name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">
                                {{ $template->rhk->urutan }}. {{ $template->rhk->nama }}
                            </p>
                        </div>
                    </div>

                    <div class="text-xs text-gray-400 dark:text-gray-500 space-y-1">
                        @php
                            $filledCount = collect(['latar_belakang','maksud_tujuan','ruang_lingkup','dasar','kegiatan_dilaksanakan','hasil_dicapai','simpulan','saran','penutup'])
                                ->filter(fn($f) => !empty($template->$f))->count();
                        @endphp
                        <p>{{ $filledCount }} dari 9 bagian terisi</p>
                        <p>Diperbarui {{ $template->updated_at->diffForHumans() }}</p>
                    </div>

                    <div class="flex items-center gap-2 pt-1 border-t border-gray-100 dark:border-gray-800">
                        <a href="{{ route('laporan.create', ['template_id' => $template->id]) }}"
                           class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Gunakan
                        </a>
                        <form action="{{ route('laporan.template.destroy', $template) }}" method="POST"
                              onsubmit="return confirm('Hapus template {{ addslashes($template->template_name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950 rounded-xl transition"
                                title="Hapus template">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
