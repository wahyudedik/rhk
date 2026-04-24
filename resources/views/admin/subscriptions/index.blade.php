<x-admin-layout>
    <x-slot name="title">Manajemen Langganan</x-slot>

    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Langganan Pengguna</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola status langganan setiap pengguna</p>
        </div>
        <a href="{{ route('admin.subscriptions.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Langganan
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pengguna</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Paket</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Periode</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Penggunaan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($subscriptions as $sub)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-950 rounded-full flex items-center justify-center shrink-0">
                                    <span class="text-xs font-bold text-blue-600 dark:text-blue-400">
                                        {{ strtoupper(substr($sub->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.users.show', $sub->user) }}"
                                       class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600 transition truncate block">
                                        {{ $sub->user->name }}
                                    </a>
                                    <p class="text-xs text-gray-400 truncate">{{ $sub->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $sub->billingPlan->nama }}</span>
                            @if ($sub->billingPlan->is_trial)
                                <span class="ml-1 text-xs text-amber-600 dark:text-amber-400">(Trial)</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400 hidden md:table-cell">
                            <div>{{ $sub->mulai_at->format('d M Y') }}</div>
                            <div>s/d {{ $sub->berakhir_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell">
                            @if ($sub->billingPlan->isUnlimited())
                                <span class="text-xs text-purple-600 dark:text-purple-400">Unlimited</span>
                            @else
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $sub->laporan_digunakan }} / {{ $sub->billingPlan->batas_laporan_per_bulan }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $isActive = $sub->status === 'active' && $sub->berakhir_at->isFuture();
                                $isExpired = $sub->berakhir_at->isPast() || $sub->status === 'expired';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full
                                {{ $isActive ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800' : ($isExpired ? 'text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800' : 'text-gray-500 bg-gray-100 dark:bg-gray-800') }}">
                                {{ $isActive ? 'Aktif' : ($isExpired ? 'Kadaluarsa' : 'Dibatalkan') }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.subscriptions.edit', $sub) }}"
                                   class="px-3 py-1.5 text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $sub) }}" method="POST"
                                      onsubmit="return confirm('Hapus langganan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-lg hover:bg-red-100 dark:hover:bg-red-900 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $subscriptions->links() }}
        </div>
    </div>
</x-admin-layout>
