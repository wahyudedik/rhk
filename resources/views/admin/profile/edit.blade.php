<x-admin-layout>
    <x-slot name="title">Profil Saya</x-slot>

    <div class="max-w-2xl space-y-4">

        {{-- Kartu ringkasan --}}
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 text-white border border-gray-700">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-red-500/20 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="text-2xl font-bold text-red-400">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-lg font-bold text-white">{{ $user->name }}</h2>
                        <span class="px-2.5 py-0.5 text-xs font-medium bg-red-500/20 text-red-300 rounded-full border border-red-500/30">
                            Super Admin
                        </span>
                    </div>
                    <p class="text-gray-400 text-sm mt-0.5">{{ $user->email }}</p>
                    @if ($user->nip)
                        <p class="text-gray-500 text-xs mt-0.5">NIP. {{ $user->nip }}</p>
                    @endif
                    @if ($user->jabatan)
                        <p class="text-gray-400 text-xs mt-0.5">{{ $user->jabatan }}</p>
                    @endif
                </div>
            </div>

            @if ($user->kecamatan || $user->kabupaten || $user->provinsi)
                <div class="mt-4 pt-4 border-t border-gray-700 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400">
                    @if ($user->desa) <span>📍 {{ $user->desa }}</span> @endif
                    @if ($user->kecamatan) <span>Kec. {{ $user->kecamatan }}</span> @endif
                    @if ($user->kabupaten) <span>{{ $user->kabupaten }}</span> @endif
                    @if ($user->provinsi) <span>{{ $user->provinsi }}</span> @endif
                </div>
            @else
                <p class="mt-3 text-xs text-gray-500">Lengkapi data diri Anda di bawah.</p>
            @endif
        </div>

        {{-- Form informasi profil --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="mb-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Informasi Profil</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Perbarui data diri dan informasi kepegawaian.</p>
            </div>

            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                {{-- Identitas dasar --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Identitas Dasar</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" maxlength="30"
                                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Nomor Induk Pegawai">
                            @error('nip') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}"
                                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Contoh: Administrator Sistem">
                            @error('jabatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Wilayah --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Wilayah Tugas</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Desa / Kelurahan</label>
                            <input type="text" name="desa" value="{{ old('desa', $user->desa) }}"
                                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Nama desa/kelurahan">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $user->kecamatan) }}"
                                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Nama kecamatan">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Kabupaten / Kota</label>
                            <input type="text" name="kabupaten" value="{{ old('kabupaten', $user->kabupaten) }}"
                                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Nama kabupaten/kota">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', $user->provinsi) }}"
                                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Nama provinsi">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
                        Simpan Perubahan
                    </button>
                    @if (session('success') && ! session()->has('password_updated'))
                        <span x-data="{ show: true }" x-show="show" x-transition
                              x-init="setTimeout(() => show = false, 3000)"
                              class="flex items-center gap-1.5 text-sm text-green-600 dark:text-green-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Tersimpan
                        </span>
                    @endif
                </div>
            </form>
        </div>

        {{-- Form ubah password --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="mb-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Ubah Password</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Gunakan password yang kuat untuk menjaga keamanan akun admin.</p>
            </div>

            <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Password Saat Ini</label>
                    <input type="password" name="current_password"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="••••••••">
                    @if ($errors->updatePassword->get('current_password'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Password Baru</label>
                    <input type="password" name="password"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Min. 8 karakter">
                    @if ($errors->updatePassword->get('password'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('password') }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ulangi password baru">
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
                        Ubah Password
                    </button>
                    @if (session('success') && session()->has('password_updated'))
                        <span x-data="{ show: true }" x-show="show" x-transition
                              x-init="setTimeout(() => show = false, 3000)"
                              class="flex items-center gap-1.5 text-sm text-green-600 dark:text-green-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Password diperbarui
                        </span>
                    @endif
                </div>
            </form>
        </div>

        {{-- Info akun --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Info Akun</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Terdaftar sejak</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Terakhir diperbarui</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Role</p>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-full border border-red-200 dark:border-red-800">
                        Super Admin
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Status email</p>
                    @if ($user->email_verified_at)
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 dark:text-green-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terverifikasi
                        </span>
                    @else
                        <span class="text-xs text-amber-600 dark:text-amber-400">Belum diverifikasi</span>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
