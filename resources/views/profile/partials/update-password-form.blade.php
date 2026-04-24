<section>
    <div class="mb-5">
        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Ubah Password</h2>
        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Gunakan password yang panjang dan acak agar akun Anda tetap aman.</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                Password Saat Ini
            </label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="••••••••">
            @if ($errors->updatePassword->get('current_password'))
                <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                Password Baru
            </label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="Min. 8 karakter">
            @if ($errors->updatePassword->get('password'))
                <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                Konfirmasi Password Baru
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="Ulangi password baru">
            @if ($errors->updatePassword->get('password_confirmation'))
                <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
                Ubah Password
            </button>

            @if (session('status') === 'password-updated')
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
</section>
