<x-app-layout>
    <x-slot name="title">Status Langganan</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Status Langganan</h2>
    </x-slot>

    <div class="max-w-2xl space-y-4">

        @if ($subscription && $subscription->isActive())
            {{-- Aktif --}}
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-green-100 text-xs font-medium uppercase tracking-wider">Paket Aktif</p>
                        <h2 class="text-2xl font-bold mt-0.5">{{ $subscription->billingPlan->nama }}</h2>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-white/20">
                    <div>
                        <p class="text-green-100 text-xs">Mulai</p>
                        <p class="text-white font-semibold text-sm">{{ $subscription->mulai_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-green-100 text-xs">Berakhir</p>
                        <p class="text-white font-semibold text-sm">{{ $subscription->berakhir_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-green-100 text-xs">Sisa hari</p>
                        <p class="text-white font-semibold text-sm">{{ (int) now()->diffInDays($subscription->berakhir_at) }} hari</p>
                    </div>
                </div>
            </div>

            {{-- Kuota --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Kuota Laporan Bulan Ini</h3>
                @if ($subscription->billingPlan->isUnlimited())
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-950 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Laporan Tidak Terbatas</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Buat laporan sebanyak yang Anda butuhkan</p>
                        </div>
                    </div>
                @else
                    @php
                        $used = $subscription->laporan_digunakan;
                        $max = $subscription->billingPlan->batas_laporan_per_bulan;
                        $pct = $max > 0 ? min(100, round($used / $max * 100)) : 0;
                    @endphp
                    <div class="flex items-end justify-between mb-2">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $used }}</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">dari {{ $max }} laporan</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2.5 mb-2">
                        <div class="h-2.5 rounded-full transition-all {{ $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-blue-500') }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Sisa {{ max(0, $max - $used) }} laporan bulan ini
                        @if ($subscription->laporan_reset_at)
                            · Reset pada awal bulan berikutnya
                        @endif
                    </p>
                @endif
            </div>

        @else
            {{-- Tidak aktif --}}
            <div class="bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800 rounded-2xl p-6 text-center">
                <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="text-base font-semibold text-red-800 dark:text-red-300 mb-1">Tidak Ada Langganan Aktif</h3>
                <p class="text-sm text-red-600 dark:text-red-400">Hubungi admin untuk mengaktifkan langganan Anda.</p>
            </div>
        @endif

        {{-- Upgrade / Paket tersedia --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Paket Tersedia</h3>
            <div class="space-y-3">
                @foreach ($plans as $plan)
                    <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-gray-800 hover:border-blue-200 dark:hover:border-blue-800 transition">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $plan->nama }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $plan->isUnlimited() ? 'Unlimited laporan' : $plan->batas_laporan_per_bulan . 'x laporan/bulan' }}
                                · {{ $plan->durasi_hari }} hari
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $plan->hargaFormatted() }}</span>
                            <a href="https://wa.me/6281654932383?text={{ urlencode('Halo, saya ingin berlangganan paket ' . $plan->nama . '. Nama: ' . auth()->user()->name . ', Email: ' . auth()->user()->email) }}"
                               target="_blank"
                               class="px-3 py-1.5 text-xs font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                                Pilih
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
