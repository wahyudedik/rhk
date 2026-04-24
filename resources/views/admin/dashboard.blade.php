<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Selamat datang, {{ Auth::user()->name }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Ringkasan aktivitas aplikasi</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $cards = [
                ['label' => 'Total Pelanggan', 'value' => $stats['total_pelanggan'], 'color' => 'blue', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Total Laporan', 'value' => $stats['total_laporan'], 'color' => 'green', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['label' => 'Total RHK', 'value' => $stats['total_rhk'], 'color' => 'purple', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label' => 'Laporan Bulan Ini', 'value' => $stats['laporan_bulan_ini'], 'color' => 'amber', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                    <div class="w-8 h-8 bg-{{ $card['color'] }}-50 dark:bg-{{ $card['color'] }}-950 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Recent laporan --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Laporan Terbaru</h2>
        </div>
        @if ($laporanTerbaru->isEmpty())
            <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada laporan.</div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($laporanTerbaru as $laporan)
                    <div class="px-5 py-4 flex items-center gap-4">
                        <div class="w-9 h-9 bg-blue-50 dark:bg-blue-950 rounded-xl flex items-center justify-center shrink-0">
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400">
                                {{ strtoupper(substr($laporan->user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $laporan->jenisRhk->nama }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $laporan->user->name }} · {{ $laporan->bulan }} {{ $laporan->tahun }}</p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap">{{ $laporan->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
