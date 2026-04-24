<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laporan ASN') }} — Sistem Laporan SKP PKH</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    {{-- Navbar --}}
    <header class="fixed top-0 inset-x-0 z-50 bg-white/80 dark:bg-gray-950/80 backdrop-blur border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-8 h-8 rounded-lg object-cover">
                <span class="font-bold text-gray-900 dark:text-white">{{ config('app.name', 'Laporan ASN') }}</span>
            </div>
            <nav class="flex items-center gap-3">
                @auth
                    @if (auth()->user()->isSuperadmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('laporan.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            Laporan Saya
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            Daftar
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section class="pt-32 pb-20 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-full border border-blue-200 dark:border-blue-800 mb-6">
                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                Kementerian Sosial Republik Indonesia
            </span>
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white leading-tight mb-6">
                Sistem Laporan<br>
                <span class="text-blue-600">SKP & RHK</span> PKH
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-10">
                Platform digital untuk pendamping sosial PKH dalam menyusun, menyimpan, dan mengelola laporan Sasaran Kinerja Pegawai secara terstruktur dan akurat.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @auth
                    @if (auth()->user()->isSuperadmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                            Buka Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('laporan.index') }}" class="px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                            Buka Laporan Saya
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                        Mulai Sekarang
                    </a>
                    <a href="#harga" class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        Lihat Harga
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-12 px-4 sm:px-6 border-y border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto grid grid-cols-2 sm:grid-cols-4 gap-8 text-center">
            @foreach ([['9', 'RHK Tersedia'], ['17', 'Jenis RHK'], ['PKH', 'Program'], ['ASN', 'Pengguna']] as [$val, $label])
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ $val }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Fitur --}}
    <section id="fitur" class="py-20 px-4 sm:px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Fitur Utama</h2>
                <p class="text-gray-500 dark:text-gray-400">Semua yang dibutuhkan pendamping sosial PKH dalam satu platform</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $features = [
                        ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Laporan Terstruktur', 'desc' => 'Buat laporan SKP dengan format baku sesuai ketentuan Kemensos RI.'],
                        ['icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'title' => '9 RHK Lengkap', 'desc' => 'Semua Rencana Hasil Kerja PKH tersedia dengan jenis kegiatan masing-masing.'],
                        ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Data Aman & Privat', 'desc' => 'Setiap laporan hanya bisa diakses oleh pemiliknya. Data terlindungi penuh.'],
                        ['icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12', 'title' => 'Upload Dokumen', 'desc' => 'Lampirkan dokumen pendukung dalam format PDF, Word, atau gambar.'],
                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Manajemen Admin', 'desc' => 'Superadmin dapat mengelola pengguna, RHK, dan memantau seluruh laporan.'],
                        ['icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'title' => 'Responsif Mobile', 'desc' => 'Tampilan optimal di semua perangkat — desktop, tablet, maupun smartphone.'],
                    ];
                @endphp
                @foreach ($features as $f)
                    <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 hover:border-blue-200 dark:hover:border-blue-800 transition group">
                        <div class="w-10 h-10 bg-blue-50 dark:bg-blue-950 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-100 dark:group-hover:bg-blue-900 transition">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $f['title'] }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section id="harga" class="py-20 px-4 sm:px-6 bg-gray-50 dark:bg-gray-900 border-y border-gray-100 dark:border-gray-800">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Harga & Paket</h2>
                <p class="text-gray-500 dark:text-gray-400">Mulai gratis dengan trial 30 hari, lanjutkan dengan paket yang sesuai kebutuhan</p>
            </div>

            @if ($plans->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                    @foreach ($plans as $plan)
                        @php
                            $isPopular = $plan->urutan === 2;
                            $colors = match($plan->urutan) {
                                1 => ['border' => 'border-green-200 dark:border-green-800', 'btn' => 'bg-green-600 hover:bg-green-700 text-white', 'badge' => ''],
                                2 => ['border' => 'border-blue-500', 'btn' => 'bg-blue-600 hover:bg-blue-700 text-white', 'badge' => ''],
                                3 => ['border' => 'border-purple-200 dark:border-purple-800', 'btn' => 'bg-purple-600 hover:bg-purple-700 text-white', 'badge' => ''],
                                default => ['border' => 'border-gray-200 dark:border-gray-700', 'btn' => 'bg-gray-600 hover:bg-gray-700 text-white', 'badge' => ''],
                            };
                        @endphp
                        <div class="relative bg-white dark:bg-gray-900 rounded-2xl border-2 {{ $colors['border'] }} p-6 flex flex-col {{ $isPopular ? 'shadow-xl shadow-blue-500/10' : '' }}">
                            @if ($isPopular)
                                <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 text-xs font-bold text-white bg-blue-600 rounded-full shadow">⭐ Paling Populer</span>
                            @endif

                            <div class="mb-5">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $plan->nama }}</h3>
                                <div class="flex items-end gap-1">
                                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $plan->hargaFormatted() }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 mb-1">/bulan</span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $plan->isUnlimited() ? 'Laporan tidak terbatas' : $plan->batas_laporan_per_bulan . 'x laporan per bulan' }}
                                </p>
                            </div>

                            <ul class="space-y-2 mb-6 flex-1">
                                @foreach ($plan->fitur ?? [] as $fitur)
                                    <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $fitur }}
                                    </li>
                                @endforeach
                            </ul>

                            <a href="https://wa.me/6281654932383?text={{ urlencode('Halo, saya ingin berlangganan paket ' . $plan->nama . ' (' . $plan->hargaFormatted() . '/bulan).') }}"
                               target="_blank"
                               class="w-full text-center py-3 text-sm font-semibold rounded-xl transition {{ $colors['btn'] }}">
                                Mulai dengan {{ $plan->nama }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Daftar sekarang dan dapatkan <strong class="text-gray-700 dark:text-gray-300">trial gratis 30 hari</strong> dengan 5 laporan.
                    Setelah itu, pilih paket yang sesuai.
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Pembayaran via transfer bank. Konfirmasi ke
                    <a href="https://wa.me/6281654932383" target="_blank" class="text-green-600 hover:underline font-medium">WhatsApp Admin</a>.
                </p>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-4 sm:px-6 bg-blue-600">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Mulai Membuat Laporan?</h2>
            <p class="text-blue-100 mb-8">Masuk ke akun Anda dan mulai susun laporan SKP dengan mudah dan cepat.</p>
            @guest
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 text-sm font-semibold text-blue-600 bg-white rounded-xl hover:bg-blue-50 transition shadow-lg">
                    Masuk Sekarang
                </a>
            @endguest
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 px-4 sm:px-6 border-t border-gray-100 dark:border-gray-800">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-6 h-6 rounded object-cover">
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ config('app.name') }} - Develop by Noteds Technology</span>
            </div>
            <p>© {{ date('Y') }} Direktorat Perlindungan Sosial Non Kebencanaan — Kemensos RI</p>
        </div>
    </footer>

</body>
</html>
