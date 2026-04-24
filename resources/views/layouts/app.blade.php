<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'Laporan ASN') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

<div class="min-h-screen flex" x-data="{ sidebarOpen: false }">

    {{-- Sidebar Overlay (mobile) --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden"
         style="display:none"></div>

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:z-auto overflow-hidden"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo --}}
        <div class="h-16 flex items-center gap-3 px-5 border-b border-gray-200 dark:border-gray-800 shrink-0">
            <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-8 h-8 rounded-lg object-cover shrink-0">
            <div class="min-w-0">
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ config('app.name') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Laporan SKP PKH</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Menu</p>

            <a href="{{ route('pelanggan.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('pelanggan.dashboard') ? 'bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('laporan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('laporan.index') || (request()->routeIs('laporan.*') && !request()->routeIs('laporan.create')) ? 'bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                History Laporan
            </a>

            <a href="{{ route('laporan.create') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('laporan.create') ? 'bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Laporan
            </a>

            <a href="{{ route('subscription.status') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('subscription.*') ? 'bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Langganan
            </a>

            <div class="pt-4">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Akun</p>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                          {{ request()->routeIs('profile.*') ? 'bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>
            </div>
        </nav>

        {{-- Kuota Langganan --}}
        @php $sub = Auth::user()->activeSubscription(); @endphp
        @if ($sub)
            <div class="mx-3 mb-3 p-3 rounded-xl border
                {{ $sub->billingPlan->is_trial ? 'bg-amber-50 dark:bg-amber-950/50 border-amber-200 dark:border-amber-800' : 'bg-blue-50 dark:bg-blue-950/50 border-blue-200 dark:border-blue-800' }}">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold {{ $sub->billingPlan->is_trial ? 'text-amber-700 dark:text-amber-400' : 'text-blue-700 dark:text-blue-400' }}">
                        {{ $sub->billingPlan->is_trial ? '⏳ Trial' : '✅ ' . $sub->billingPlan->nama }}
                    </span>
                    <a href="{{ route('subscription.status') }}" class="text-xs text-gray-500 dark:text-gray-400 hover:underline">Detail</a>
                </div>
                @if ($sub->billingPlan->isUnlimited())
                    <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">Laporan tidak terbatas</p>
                @else
                    @php
                        $sisa = $sub->sisaLaporan() ?? 0;
                        $batas = $sub->billingPlan->batas_laporan_per_bulan;
                        $digunakan = $sub->laporan_digunakan;
                        $pct = $batas > 0 ? min(100, round($digunakan / $batas * 100)) : 0;
                    @endphp
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ $digunakan }}/{{ $batas }} laporan</span>
                        <span class="text-xs font-semibold {{ $sisa === 0 ? 'text-red-600 dark:text-red-400' : ($sisa <= 1 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-700 dark:text-gray-300') }}">
                            sisa {{ $sisa }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full transition-all {{ $pct >= 80 ? 'bg-red-500' : ($pct >= 60 ? 'bg-amber-500' : 'bg-green-500') }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                    @if ($sisa === 0)
                        <a href="{{ route('subscription.status') }}"
                           class="mt-2 block text-center py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            Upgrade Sekarang
                        </a>
                    @endif
                @endif
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">
                    Berakhir {{ $sub->berakhir_at->format('d M Y') }}
                </p>
            </div>
        @else
            <div class="mx-3 mb-3 p-3 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/50">
                <p class="text-xs font-semibold text-red-700 dark:text-red-400 mb-1">⚠️ Tidak ada langganan</p>
                <a href="{{ route('subscription.status') }}" class="text-xs text-blue-600 hover:underline">Aktifkan sekarang</a>
            </div>
        @endif

        {{-- User info + logout --}}
        <div class="px-3 py-4 border-t border-gray-200 dark:border-gray-800 shrink-0">
            <div class="flex items-center gap-3 px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-800">
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center shrink-0">
                    <span class="text-xs font-bold text-blue-700 dark:text-blue-300">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-red-600 dark:hover:text-red-400 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col min-w-0 lg:ml-0">

        {{-- Top bar (mobile) --}}
        <header class="h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-4 lg:px-6 shrink-0">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex items-center gap-2 lg:hidden">
                <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-6 h-6 rounded object-cover">
                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
            </div>

            {{-- Page title on desktop --}}
            <div class="hidden lg:block">
                @isset($header)
                    {{ $header }}
                @endisset
            </div>

            <div class="flex items-center gap-2">
                <span class="hidden sm:inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950 rounded-full border border-green-200 dark:border-green-800">
                    Pelanggan
                </span>
                <a href="{{ route('profile.edit') }}" class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                    <span class="text-xs font-bold text-blue-700 dark:text-blue-300">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                </a>
            </div>
        </header>

        {{-- Page heading (mobile) --}}
        @isset($header)
            <div class="lg:hidden px-4 py-4 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">
                {{ $header }}
            </div>
        @endisset

        {{-- Content --}}
        <main class="flex-1 p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>

</div>

@stack('scripts')
</body>
</html>
