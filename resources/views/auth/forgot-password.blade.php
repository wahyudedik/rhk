<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lupa password?</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Masukkan email Anda dan kami akan mengirimkan tautan untuk mereset password.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            Kirim Tautan Reset
        </button>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">← Kembali ke halaman masuk</a>
        </p>
    </form>
</x-guest-layout>
