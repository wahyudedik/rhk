<x-app-layout>
    <x-slot name="title">Galeri Foto GPS</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Galeri Foto GPS</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Galeri Foto GPS</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Total: {{ $photos->flatten()->count() }} foto
                </p>
            </div>
            <a href="{{ route('gps-photo.index') }}" class="px-4 py-2 text-sm font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition border border-blue-200 dark:border-blue-800">
                ➕ Buat Foto Baru
            </a>
        </div>

        @if ($photos->isEmpty())
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Belum ada foto GPS. Mulai buat foto GPS sekarang!</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($photos as $dateLabel => $photoGroup)
                    <div>
                        {{-- Date Header --}}
                        <div class="sticky top-0 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 px-4 py-3 rounded-lg mb-4 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $dateLabel }}</h3>
                        </div>

                        {{-- Photos Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($photoGroup as $photo)
                                <div class="group relative bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-lg transition">
                                    {{-- Image --}}
                                    <div class="relative h-48 bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                        <img src="{{ Storage::url('gps-photos/' . $photo->filename) }}" 
                                             alt="{{ $photo->original_filename }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition">
                                        
                                        {{-- Overlay --}}
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                            <a href="{{ Storage::url('gps-photos/' . $photo->filename) }}" 
                                               download
                                               class="p-2 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                                <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                            </a>
                                            <button onclick="deletePhoto({{ $photo->id }})"
                                                    class="p-2 bg-red-500 hover:bg-red-600 rounded-lg transition">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Info --}}
                                    <div class="p-3 border-t border-gray-200 dark:border-gray-800">
                                        <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                            {{ $photo->original_filename }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            📍 {{ $photo->latitude }}, {{ $photo->longitude }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            🕐 {{ $photo->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function deletePhoto(photoId) {
            if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
                return;
            }

            fetch(`/gps-photo/${photoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Foto berhasil dihapus');
                    location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Gagal menghapus foto');
            });
        }
    </script>
    @endpush
</x-app-layout>
