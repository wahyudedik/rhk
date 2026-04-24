<x-admin-layout>
    <x-slot name="title">Paket Billing</x-slot>

    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Paket Billing</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola paket langganan yang tersedia</p>
        </div>
        <a href="{{ route('admin.billing.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Paket
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paket</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Harga</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Durasi</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Batas Laporan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($plans as $plan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                                    {{ $plan->is_trial ? 'bg-gray-100 dark:bg-gray-800' : ($plan->urutan === 3 ? 'bg-purple-100 dark:bg-purple-950' : ($plan->urutan === 2 ? 'bg-blue-100 dark:bg-blue-950' : 'bg-green-100 dark:bg-green-950')) }}">
                                    <svg class="w-4 h-4 {{ $plan->is_trial ? 'text-gray-500' : ($plan->urutan === 3 ? 'text-purple-600' : ($plan->urutan === 2 ? 'text-blue-600' : 'text-green-600')) }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $plan->nama }}</p>
                                    <p class="text-xs text-gray-400">{{ $plan->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $plan->hargaFormatted() }}</span>
                            <span class="text-xs text-gray-400">/bulan</span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 hidden md:table-cell">
                            {{ $plan->durasi_hari }} hari
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell">
                            @if ($plan->isUnlimited())
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-950 rounded-full">Unlimited</span>
                            @else
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $plan->batas_laporan_per_bulan }}x/bulan</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-1">
                                @if ($plan->is_trial)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950 rounded-full w-fit">Trial</span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full w-fit
                                    {{ $plan->is_active ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950' : 'text-gray-500 bg-gray-100 dark:bg-gray-800' }}">
                                    {{ $plan->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.billing.edit', $plan) }}"
                                   class="px-3 py-1.5 text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900 transition">
                                    Edit
                                </a>
                                @if (! $plan->is_trial)
                                    <form action="{{ route('admin.billing.destroy', $plan) }}" method="POST"
                                          onsubmit="return confirm('Hapus paket {{ addslashes($plan->nama) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950 rounded-lg hover:bg-red-100 dark:hover:bg-red-900 transition">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $plans->links() }}
        </div>
    </div>
</x-admin-layout>
