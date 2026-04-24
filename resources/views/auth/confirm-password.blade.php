<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Konfirmasi Password</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Ini adalah area aman. Konfirmasi password Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            Konfirmasi
        </button>
    </form>
</x-guest-layout>
