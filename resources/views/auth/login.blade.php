<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Selamat datang</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        Lupa password?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember"
                class="w-4 h-4 rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500 dark:bg-gray-900">
            <label for="remember_me" class="text-sm text-gray-600 dark:text-gray-400">Ingat saya</label>
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            Masuk
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">Daftar</a>
            </p>
        @endif
    </form>
</x-guest-layout>
