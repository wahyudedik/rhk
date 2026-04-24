<x-app-layout>
    <x-slot name="title">Profil Saya</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Profil Saya</h2>
    </x-slot>

    <div class="max-w-2xl space-y-4">

        {{-- Kartu ringkasan profil --}}
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="text-2xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-bold text-white truncate">{{ $user->name }}</h2>
                    @if ($user->nip)
                        <p class="text-blue-100 text-sm">NIP. {{ $user->nip }}</p>
                    @endif
                    @if ($user->jabatan)
                        <p class="text-blue-200 text-xs mt-0.5">{{ $user->jabatan }}</p>
                    @endif
                </div>
                <span class="px-2.5 py-1 text-xs font-medium bg-white/20 rounded-full shrink-0">
                    {{ $user->role->label() }}
                </span>
            </div>

            @if ($user->kecamatan || $user->kabupaten || $user->provinsi)
                <div class="mt-4 pt-4 border-t border-white/20 flex flex-wrap gap-x-4 gap-y-1 text-xs text-blue-100">
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
            @else
                <p class="mt-3 text-xs text-blue-200">Lengkapi data wilayah tugas Anda di bawah.</p>
            @endif
        </div>

        {{-- Form informasi profil --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Form ubah password --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            @include('profile.partials.update-password-form')
        </div>

        {{-- Hapus akun --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-red-100 dark:border-red-900/50 p-6">
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</x-app-layout>
