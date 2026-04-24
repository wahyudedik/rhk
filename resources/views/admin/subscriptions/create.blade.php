<x-admin-layout>
    <x-slot name="title">Tambah Langganan</x-slot>

    <div class="max-w-lg">
        <div class="flex items-center gap-2 mb-5">
            <a href="{{ route('admin.subscriptions.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah Langganan</span>
        </div>

        <div class="p-4 bg-blue-50 dark:bg-blue-950 rounded-xl border border-blue-200 dark:border-blue-800 text-sm text-blue-800 dark:text-blue-300 mb-4">
            Menambahkan langganan baru akan <strong>menonaktifkan</strong> langganan aktif sebelumnya untuk pengguna yang dipilih.
        </div>

        <form action="{{ route('admin.subscriptions.store') }}" method="POST"
              class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Pengguna <span class="text-red-500">*</span></label>
                <select name="user_id" required
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">-- Pilih Pengguna --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Paket Langganan <span class="text-red-500">*</span></label>
                <select name="billing_plan_id" required
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">-- Pilih Paket --</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('billing_plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->nama }} — {{ $plan->hargaFormatted() }}/{{ $plan->durasi_hari }} hari
                            @if ($plan->batas_laporan_per_bulan) ({{ $plan->batas_laporan_per_bulan }}x/bln) @else (Unlimited) @endif
                        </option>
                    @endforeach
                </select>
                @error('billing_plan_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="mulai_at" value="{{ old('mulai_at', now()->format('Y-m-d')) }}" required
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                @error('mulai_at') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="2"
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                    placeholder="Catatan opsional...">{{ old('catatan') }}</textarea>
                @error('catatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.subscriptions.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">
                    Aktifkan Langganan
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
