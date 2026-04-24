
<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Dashboard</h2>
    </x-slot>

    {{-- Notifikasi Trial --}}
    @if ($isTrialPlan)
        @php $pctTrial = $batasLaporan > 0 ? round(($batasLaporan - ($sisaLaporan ?? 0)) / $batasLaporan * 100) : 0; @endphp
        <div class="mb-5 p-4 rounded-2xl border flex items-start gap-4
            {{ ($sisaLaporan ?? 0) <= 1 ? 'bg-red-50 dark:bg-red-950 border-red-200 dark:border-red-800' : 'bg-amber-50 dark:bg-amber-950 border-amber-200 dark:border-amber-800' }}">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                {{ ($sisaLaporan ?? 0) <= 1 ? 'bg-red-100 dark:bg-red-900' : 'bg-amber-100 dark:bg-amber-900' }}">
                <svg class="w-5 h-5 {{ ($sisaLaporan ?? 0) <= 1 ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold {{ ($sisaLaporan ?? 0) <= 1 ? 'text-red-800 dark:text-red-300' : 'text-amber-800 dark:text-amber-300' }}">
                    @if (($sisaLaporan ?? 0) === 0)
                        Kuota trial habis! Upgrade sekarang untuk terus membuat laporan.
                    @elseif (($sisaLaporan ?? 0) <= 1)
                        Sisa {{ $sisaLaporan }} laporan trial. Segera upgrade!
                    @else
                        Anda menggunakan paket Trial — sisa {{ $sisaLaporan }} dari {{ $batasLaporan }} laporan bulan ini.
                    @endif
                </p>
                <p class="text-xs {{ ($sisaLaporan ?? 0) <= 1 ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }} mt-0.5">
                    Trial berakhir {{ $sub?->berakhir_at->format('d M Y') }} · {{ $sisaHari }} hari lagi
                </p>
                <div class="mt-2 flex items-center gap-3">
                    <div class="flex-1 bg-white/50 dark:bg-black/20 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full {{ $pctTrial >= 80 ? 'bg-red-500' : 'bg-amber-500' }}" style="width: {{ $pctTrial }}%"></div>
                    </div>
                    <a href="{{ route('subscription.status') }}"
                       class="shrink-0 px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        Upgrade Sekarang
                    </a>
                </div>
            </div>
        </div>
    @elseif ($sub && $sisaHari <= 7)
        <div class="mb-5 p-4 rounded-2xl border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-950 flex items-center gap-4">
            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm text-orange-800 dark:text-orange-300 flex-1">
                Langganan <strong>{{ $sub->billingPlan->nama }}</strong> berakhir dalam <strong>{{ $sisaHari }} hari</strong> ({{ $sub->berakhir_at->format('d M Y') }}).
            </p>
            <a href="{{ route('subscription.status') }}" class="shrink-0 px-3 py-1.5 text-xs font-semibold text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition">Perpanjang</a>
        </div>
    @endif

    {{-- Greeting --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">
            Selamat datang, {{ $user->name }} 👋
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
            {{ now()->translatedFormat('l, d F Y') }}
            @if ($sub)
                · Paket <span class="font-medium text-blue-600 dark:text-blue-400">{{ $sub->billingPlan->nama }}</span>
            @endif
        </p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Laporan --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Laporan</p>
                <div class="w-8 h-8 bg-blue-50 dark:bg-blue-950 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalLaporan }}</p>
            <p class="text-xs text-gray-400 mt-1">Semua waktu</p>
        </div>

        {{-- Bulan Ini --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Bulan Ini</p>
                <div class="w-8 h-8 bg-green-50 dark:bg-green-950 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $laporanBulanIni }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>

        {{-- Tahun Ini --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tahun Ini</p>
                <div class="w-8 h-8 bg-purple-50 dark:bg-purple-950 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $laporanTahunIni }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->year }}</p>
        </div>

        {{-- Kuota --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kuota Bulan Ini</p>
                <div class="w-8 h-8 bg-amber-50 dark:bg-amber-950 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            @if ($sub && $sub->billingPlan->isUnlimited())
                <p class="text-3xl font-bold text-purple-600">∞</p>
                <p class="text-xs text-gray-400 mt-1">Unlimited</p>
            @elseif ($sub)
                <p class="text-3xl font-bold {{ ($sisaLaporan ?? 0) === 0 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                    {{ $sisaLaporan ?? 0 }}
                </p>
                <p class="text-xs text-gray-400 mt-1">dari {{ $batasLaporan }} laporan</p>
                @if ($batasLaporan > 0)
                    @php $pct = round(($batasLaporan - ($sisaLaporan ?? 0)) / $batasLaporan * 100); @endphp
                    <div class="mt-2 w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full {{ $pct >= 80 ? 'bg-red-500' : ($pct >= 60 ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ $pct }}%"></div>
                    </div>
                @endif
            @else
                <p class="text-3xl font-bold text-red-600">0</p>
                <p class="text-xs text-red-400 mt-1">Tidak aktif</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Chart Laporan per Bulan --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Laporan per Bulan ({{ now()->year }})</h3>
            </div>
            <div class="relative h-48">
                <canvas id="chart-laporan-bulan"></canvas>
            </div>
        </div>

        {{-- Langganan Info --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Status Langganan</h3>
            @if ($sub && $sub->isActive())
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-950 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $sub->billingPlan->nama }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400">Aktif</p>
                    </div>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Mulai</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $sub->mulai_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Berakhir</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $sub->berakhir_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Sisa hari</span>
                        <span class="font-medium {{ $sisaHari <= 7 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ $sisaHari }} hari</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Laporan digunakan</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $sub->laporan_digunakan }}{{ $batasLaporan ? '/'.$batasLaporan : '' }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('subscription.status') }}"
                   class="mt-4 block text-center py-2 text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition">
                    Lihat Detail
                </a>
            @else
                <div class="text-center py-4">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Tidak ada langganan aktif</p>
                    <a href="{{ route('subscription.status') }}" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">Berlangganan</a>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Laporan Terbaru --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Laporan Terbaru</h3>
                <a href="{{ route('laporan.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
            </div>
            @if ($laporanTerbaru->isEmpty())
                <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada laporan.</div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($laporanTerbaru as $laporan)
                        <a href="{{ route('laporan.show', $laporan) }}" class="flex items-start gap-3 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <span class="mt-0.5 shrink-0 px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-lg whitespace-nowrap">
                                {{ $laporan->bulan }} {{ $laporan->tahun }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white line-clamp-1">{{ $laporan->jenisRhk->nama }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">{{ $laporan->rhk->nama }}</p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap shrink-0">{{ $laporan->created_at->diffForHumans() }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Laporan per RHK --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Top RHK</h3>
            @if ($laporanPerRhk->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Belum ada data.</p>
            @else
                <div class="space-y-3">
                    @foreach ($laporanPerRhk as $item)
                        @php $maxTotal = $laporanPerRhk->first()->total; $pct = $maxTotal > 0 ? round($item->total / $maxTotal * 100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs text-gray-700 dark:text-gray-300 line-clamp-1 flex-1 mr-2">{{ Str::limit($item->nama, 45) }}</p>
                                <span class="text-xs font-semibold text-gray-900 dark:text-white shrink-0">{{ $item->total }}</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full bg-blue-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="mt-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Aksi Cepat</h3>
        <div class="flex flex-wrap gap-3">
            @if ($sub && $sub->bisaBuatLaporan())
                <a href="{{ route('laporan.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Laporan Baru
                </a>
            @else
                <span class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-xl cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Laporan (Kuota Habis)
                </span>
            @endif
            <a href="{{ route('laporan.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                History Laporan
            </a>
            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
            </a>
            <a href="{{ route('subscription.status') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Status Langganan
            </a>
        </div>
    </div>

    @push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('chart-laporan-bulan');
        if (!ctx) return;

        var labels = {!! json_encode($bulanList, JSON_HEX_TAG) !!};
        var data = {!! json_encode($chartData, JSON_HEX_TAG) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels.map(function(l) { return l.substring(0, 3); }),
                datasets: [{
                    label: 'Laporan',
                    data: data,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: function(items) { return labels[items[0].dataIndex]; },
                            label: function(item) { return item.raw + ' laporan'; }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });
    </script>
    @endpush
</x-app-layout>
