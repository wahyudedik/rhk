<x-admin-layout>
    <x-slot name="title">Edit Pengguna</x-slot>

    <div class="max-w-lg">
        <div class="flex items-center gap-2 mb-5">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Edit Pengguna</span>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST"
              class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
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
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Password Baru</label>
                <input type="password" name="password"
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Kosongkan jika tidak ingin mengubah">
                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select name="role" required
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                           {{ $user->isSuperadmin() && $user->id === auth()->id() ? 'opacity-60 cursor-not-allowed' : '' }}"
                    {{ $user->isSuperadmin() && $user->id === auth()->id() ? 'disabled' : '' }}>
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}" {{ old('role', $user->role->value) === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
                @if ($user->isSuperadmin() && $user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role->value }}">
                    <p class="mt-1 text-xs text-gray-400">Role akun Anda sendiri tidak dapat diubah.</p>
                @endif
                @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
