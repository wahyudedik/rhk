<section>
    <div class="mb-5">
        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Hapus Akun</h2>
        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
            Setelah akun dihapus, semua data akan dihapus permanen. Pastikan Anda sudah menyimpan data penting sebelum melanjutkan.
        </p>
    </div>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2.5 text-sm font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800 rounded-xl hover:bg-red-100 dark:hover:bg-red-900 transition">
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                Hapus akun secara permanen?
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
                Semua laporan dan data Anda akan dihapus dan tidak dapat dipulihkan. Masukkan password untuk konfirmasi.
            </p>

            <div class="mb-5">
                <label for="delete_password" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Password</label>
                <input id="delete_password" name="password" type="password"
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    placeholder="Masukkan password Anda">
                @if ($errors->userDeletion->get('password'))
                    <p class="mt-1 text-xs text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
