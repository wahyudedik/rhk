<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}Admin {{ config('app.name', 'Laporan ASN') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 dark:bg-gray-950 flex flex-col transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:z-auto overflow-hidden"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo --}}
        <div class="h-16 flex items-center gap-3 px-5 border-b border-gray-700/50 shrink-0">
            <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-8 h-8 rounded-lg object-cover shrink-0">
            <div class="min-w-0">
                <p class="text-sm font-bold text-white truncate">Admin Panel</p>
                <p class="text-xs text-gray-400">{{ config('app.name') }}</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <div class="pt-4">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Manajemen</p>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                          {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Pengguna
                </a>

                <a href="{{ route('admin.rhk.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                          {{ request()->routeIs('admin.rhk.*') || request()->routeIs('admin.jenis-rhk.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    RHK & Jenis RHK
                </a>
            </div>

            <div class="pt-4">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Billing</p>

                <a href="{{ route('admin.billing.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                          {{ request()->routeIs('admin.billing.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Paket Billing
                </a>

                <a href="{{ route('admin.subscriptions.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                          {{ request()->routeIs('admin.subscriptions.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Langganan User
                </a>
            </div>
        </nav>

        {{-- User info --}}
        <div class="px-3 py-4 border-t border-gray-700/50 shrink-0">
            <a href="{{ route('admin.profile.edit') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-800 transition group {{ request()->routeIs('admin.profile.*') ? 'bg-gray-800' : '' }}">
                <div class="w-8 h-8 bg-red-500/20 rounded-full flex items-center justify-center shrink-0">
                    <span class="text-xs font-bold text-red-400">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate group-hover:text-gray-300 transition">Edit Profil</p>
                </div>
                <svg class="w-3.5 h-3.5 text-gray-500 group-hover:text-gray-300 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-400 hover:bg-gray-800 hover:text-red-400 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top bar --}}
        <header class="h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-4 lg:px-6 shrink-0">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                @isset($title)
                    <h1 class="text-base font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
                @endisset
            </div>
            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-full border border-red-200 dark:border-red-800">
                Super Admin
            </span>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 lg:p-6">
            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 rounded-xl text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 flex items-center gap-3 p-4 bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 rounded-xl text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
            {{ $slot }}
        </main>
    </div>

</div>

@stack('scripts')
</body>
</html>
