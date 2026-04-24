<x-app-layout>
    <x-slot name="title">Langganan Tidak Aktif</x-slot>

    <div class="max-w-3xl mx-auto py-8">

        {{-- Alert --}}
        <div class="bg-amber-50 dark:bg-amber-950 border border-amber-200 dark:border-amber-800 rounded-2xl p-5 mb-6 flex items-start gap-4">
            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                    @if ($subscription)
                        Kuota laporan bulan ini sudah habis
                    @else
                        Langganan Anda tidak aktif
                    @endif
                </h3>
                <p class="text-sm text-amber-700 dark:text-amber-400 mt-0.5">
                    @if ($subscription)
                        Anda telah menggunakan semua kuota laporan bulan ini. Upgrade paket untuk melanjutkan atau tunggu bulan berikutnya.
                    @else
                        Anda belum memiliki langganan aktif. Pilih paket di bawah dan hubungi admin untuk aktivasi.
                    @endif
                </p>
            </div>
        </div>

        {{-- Paket --}}
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Pilih Paket Langganan</h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            @foreach ($plans as $plan)
                @php
                    $isPopular = $plan->urutan === 2;
                    $colors = match($plan->urutan) {
                        1 => ['ring' => 'ring-green-500', 'btn' => 'bg-green-600 hover:bg-green-700', 'badge' => 'bg-green-100 text-green-700'],
                        2 => ['ring' => 'ring-blue-500', 'btn' => 'bg-blue-600 hover:bg-blue-700', 'badge' => 'bg-blue-100 text-blue-700'],
                        3 => ['ring' => 'ring-purple-500', 'btn' => 'bg-purple-600 hover:bg-purple-700', 'badge' => 'bg-purple-100 text-purple-700'],
                        default => ['ring' => 'ring-gray-300', 'btn' => 'bg-gray-600 hover:bg-gray-700', 'badge' => 'bg-gray-100 text-gray-700'],
                    };
                @endphp
                <div class="relative bg-white dark:bg-gray-900 rounded-2xl border-2 {{ $isPopular ? $colors['ring'] : 'border-gray-200 dark:border-gray-800' }} p-5 flex flex-col">
                    @if ($isPopular)
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 text-xs font-bold text-white bg-blue-600 rounded-full">Populer</span>
                    @endif
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ $plan->nama }}</h3>
                    <div class="mb-3">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $plan->hargaFormatted() }}</span>
                        <span class="text-xs text-gray-500">/bulan</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        {{ $plan->isUnlimited() ? 'Laporan tidak terbatas' : $plan->batas_laporan_per_bulan . 'x laporan/bulan' }}
                    </p>
                    <ul class="space-y-1.5 mb-5 flex-1">
                        @foreach ($plan->fitur ?? [] as $fitur)
                            <li class="flex items-start gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <svg class="w-3.5 h-3.5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $fitur }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="https://wa.me/6281654932383?text={{ urlencode('Halo, saya ingin berlangganan paket ' . $plan->nama . ' (' . $plan->hargaFormatted() . '/bulan). Nama: ' . auth()->user()->name . ', Email: ' . auth()->user()->email) }}"
                       target="_blank"
                       class="w-full text-center py-2.5 text-sm font-semibold text-white {{ $colors['btn'] }} rounded-xl transition">
                        Pilih Paket Ini
                    </a>
                </div>
            @endforeach
        </div>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
            Pembayaran dikonfirmasi oleh admin. Setelah transfer, hubungi
            <a href="https://wa.me/6281654932383" target="_blank" class="text-green-600 hover:underline font-medium">WhatsApp Admin</a>
            dengan bukti pembayaran.
        </p>
    </div>
</x-app-layout>
