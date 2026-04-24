<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-950 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Verifikasi Email</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Kami telah mengirimkan tautan verifikasi ke email Anda. Silakan cek kotak masuk dan klik tautan tersebut.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-400">
            Tautan verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                class="w-full py-2.5 px-4 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full py-2.5 px-4 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
