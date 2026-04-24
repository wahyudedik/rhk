<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laporan ASN') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    <div class="min-h-screen flex">

        {{-- Left panel — branding --}}
        <div class="hidden lg:flex lg:w-1/2 bg-blue-600 flex-col justify-between p-12 relative overflow-hidden">
            {{-- Background decoration --}}
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500 rounded-full opacity-40"></div>
                <div class="absolute -bottom-32 -left-16 w-80 h-80 bg-blue-700 rounded-full opacity-50"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-500 rounded-full opacity-20"></div>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-12">
                    <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-10 h-10 rounded-xl object-cover">
                    <span class="text-white font-bold text-lg">{{ config('app.name') }}</span>
                </div>

                <h2 class="text-3xl font-bold text-white leading-snug mb-4">
                    Sistem Laporan<br>SKP & RHK PKH
                </h2>
                <p class="text-blue-100 text-sm leading-relaxed max-w-sm">
                    Platform digital untuk pendamping sosial PKH dalam menyusun laporan Sasaran Kinerja Pegawai secara terstruktur.
                </p>
            </div>

            <div class="relative z-10 space-y-3">
                @foreach (['Laporan terstruktur sesuai ketentuan Kemensos', 'Data aman & hanya bisa diakses pemilik', '9 RHK dengan 17 jenis kegiatan lengkap'] as $item)
                    <div class="flex items-center gap-3 text-sm text-blue-100">
                        <div class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        {{ $item }}
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right panel — form --}}
        <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 lg:px-16">
            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-8 h-8 rounded-lg object-cover">
                <span class="font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
            </div>

            <div class="w-full max-w-sm">
                {{ $slot }}
            </div>

            <p class="mt-8 text-xs text-gray-400 text-center">
                © {{ date('Y') }} Kementerian Sosial RI
            </p>
        </div>

    </div>

</body>
</html>
