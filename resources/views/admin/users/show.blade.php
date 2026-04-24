<x-admin-layout>
    <x-slot name="title">Detail Pengguna</x-slot>

    <div class="max-w-3xl space-y-4">

        {{-- Breadcrumb & actions --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Pengguna
                </a>
                <span class="text-gray-300 dark:text-gray-600">/</span>
                <span class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-xs">{{ $user->name }}</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="px-4 py-2 text-sm font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900 transition border border-amber-200 dark:border-amber-800">
                    Edit
                </a>
                @if (! $user->isSuperadmin())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                          onsubmit="return confirm('Hapus pengguna {{ addslashes($user->name) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-xl hover:bg-red-100 dark:hover:bg-red-900 transition border border-red-200 dark:border-red-800">
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Kartu profil --}}
        <div class="bg-gradient-to-br {{ $user->isSuperadmin() ? 'from-gray-800 to-gray-900' : 'from-blue-600 to-blue-700' }} rounded-2xl p-6 text-white">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="text-2xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <h1 class="text-xl font-bold text-white">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 text-xs font-medium bg-white/20 rounded-full">
                            {{ $user->role->label() }}
                        </span>
                    </div>
                    <p class="text-sm {{ $user->isSuperadmin() ? 'text-gray-300' : 'text-blue-100' }}">{{ $user->email }}</p>
                    @if ($user->nip)
                        <p class="text-sm {{ $user->isSuperadmin() ? 'text-gray-400' : 'text-blue-200' }} mt-0.5">NIP. {{ $user->nip }}</p>
                    @endif
                    @if ($user->jabatan)
                        <p class="text-sm {{ $user->isSuperadmin() ? 'text-gray-300' : 'text-blue-100' }} mt-0.5">{{ $user->jabatan }}</p>
                    @endif
                </div>
            </div>

            @if ($user->kecamatan || $user->kabupaten || $user->provinsi)
                <div class="mt-4 pt-4 border-t border-white/20 flex flex-wrap gap-x-4 gap-y-1 text-xs {{ $user->isSuperadmin() ? 'text-gray-400' : 'text-blue-100' }}">
                    @if ($user->desa)
                        <span>📍 {{ $user->desa }}</span>
                    @endif
                    @if ($user->kecamatan)
                        <span>Kec. {{ $user->kecamatan }}</span>
                    @endif
                    @if ($user->kabupaten)
                        <span>{{ $user->kabupaten }}</span>
                    @endif
                    @if ($user->provinsi)
                        <span>{{ $user->provinsi }}</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 text-center">
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_laporan'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total Laporan</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 text-center">
                <p class="text-3xl font-bold text-blue-600">{{ $stats['laporan_bulan_ini'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bulan Ini</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 text-center">
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['tahun_aktif']->count() }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tahun Aktif</p>
            </div>
        </div>

        {{-- Data kepegawaian --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Data Kepegawaian</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @php
                    $fields = [
                        ['label' => 'NIP', 'value' => $user->nip],
                        ['label' => 'Jabatan', 'value' => $user->jabatan],
                        ['label' => 'Desa / Kelurahan', 'value' => $user->desa],
                        ['label' => 'Kecamatan', 'value' => $user->kecamatan],
                        ['label' => 'Kabupaten / Kota', 'value' => $user->kabupaten],
                        ['label' => 'Provinsi', 'value' => $user->provinsi],
                    ];
                @endphp
                @foreach ($fields as $field)
                    <div>
                        <p class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-0.5">{{ $field['label'] }}</p>
                        <p class="text-sm text-gray-900 dark:text-white">
                            {{ $field['value'] ?: '—' }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-0.5">Terdaftar</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $user->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-0.5">Terakhir diperbarui</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Laporan terbaru --}}
        @if ($user->isPelanggan())
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Laporan</h3>
                    @if ($stats['total_laporan'] > 0)
                        <span class="text-xs text-gray-400">{{ $stats['total_laporan'] }} laporan total</span>
                    @endif
                </div>

                @if ($laporanTerbaru->isEmpty())
                    <div class="p-8 text-center">
                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada laporan.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($laporanTerbaru as $laporan)
                            <div class="px-5 py-4 flex items-start gap-4">
                                <span class="mt-0.5 inline-flex items-center px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-lg whitespace-nowrap shrink-0">
                                    {{ $laporan->bulan }} {{ $laporan->tahun }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white line-clamp-1">
                                        {{ $laporan->jenisRhk->nama }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">
                                        {{ $laporan->rhk->nama }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-400 whitespace-nowrap shrink-0">
                                    {{ $laporan->created_at->format('d M Y') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">
                        {{ $laporanTerbaru->links() }}
                    </div>
                @endif
            </div>
        @endif

    </div>
</x-admin-layout>
