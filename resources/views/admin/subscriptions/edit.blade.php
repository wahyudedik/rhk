<x-admin-layout>
    <x-slot name="title">Edit Langganan</x-slot>

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
            <span class="text-sm font-medium text-gray-900 dark:text-white">Edit Langganan</span>
        </div>

        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 text-sm mb-4">
            <p class="font-medium text-gray-900 dark:text-white">{{ $subscription->user->name }}</p>
            <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $subscription->user->email }}</p>
        </div>

        <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST"
              class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Paket Langganan <span class="text-red-500">*</span></label>
                <select name="billing_plan_id" required
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('billing_plan_id', $subscription->billing_plan_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->nama }} — {{ $plan->hargaFormatted() }}
                        </option>
                    @endforeach
                </select>
                @error('billing_plan_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="mulai_at" value="{{ old('mulai_at', $subscription->mulai_at->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('mulai_at') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Tanggal Berakhir <span class="text-red-500">*</span></label>
                    <input type="date" name="berakhir_at" value="{{ old('berakhir_at', $subscription->berakhir_at->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('berakhir_at') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Status <span class="text-red-500">*</span></label>
                <select name="status" required
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="active" {{ old('status', $subscription->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="expired" {{ old('status', $subscription->status) === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    <option value="cancelled" {{ old('status', $subscription->status) === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="2"
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none">{{ old('catatan', $subscription->catatan) }}</textarea>
                @error('catatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.subscriptions.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
