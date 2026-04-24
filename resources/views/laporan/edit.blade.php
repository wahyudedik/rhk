<x-app-layout>
    <x-slot name="title">Edit Laporan</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Edit Laporan</h2>
    </x-slot>

    <div class="max-w-3xl">
        <div class="flex items-center gap-2 mb-5">
            <a href="{{ route('laporan.show', $laporan) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Edit Laporan</span>
        </div>

        <form id="form-laporan" action="{{ route('laporan.update', $laporan) }}" method="POST" enctype="multipart/form-data" class="space-y-4"
              data-user-ttd="{{ $user->tanda_tangan ? Storage::url($user->tanda_tangan) : '' }}"
              data-laporan-ttd="{{ $laporan->ttd_gambar ? Storage::url($laporan->ttd_gambar) : '' }}">
            @csrf
            @method('PATCH')

            {{-- 1. RHK & Periode --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">1. RHK & Periode</h3>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Bulan <span class="text-red-500">*</span></label>
                        <select name="bulan" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                                <option value="{{ $bulan }}" {{ old('bulan', $laporan->bulan) === $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                            @endforeach
                        </select>
                        @error('bulan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Tahun <span class="text-red-500">*</span></label>
                        <input type="number" name="tahun" value="{{ old('tahun', $laporan->tahun) }}" min="2020" max="2099" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        @error('tahun') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">RHK <span class="text-red-500">*</span></label>
                    <select name="rhk_id" id="rhk-select" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Pilih RHK</option>
                        @foreach ($rhks as $rhk)
                            <option value="{{ $rhk->id }}" {{ old('rhk_id', $laporan->rhk_id) == $rhk->id ? 'selected' : '' }}>{{ $rhk->urutan }}. {{ $rhk->nama }}</option>
                        @endforeach
                    </select>
                    @error('rhk_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div id="jenis-rhk-wrapper">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Jenis RHK <span class="text-red-500">*</span></label>
                    <select name="jenis_rhk_id" id="jenis-rhk-select"
                        data-selected="{{ old('jenis_rhk_id', $laporan->jenis_rhk_id) }}"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Pilih jenis RHK</option>
                        @foreach($rhks->firstWhere('id', old('rhk_id', $laporan->rhk_id))?->jenisRhks ?? [] as $jenis)
                            <option value="{{ $jenis->id }}" {{ old('jenis_rhk_id', $laporan->jenis_rhk_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @error('jenis_rhk_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- 2. Header Instansi --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">2. Header Instansi</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Opsional. Jika dikosongkan, menggunakan header default Kemensos RI.</p>
                <div class="space-y-3">
                    @foreach([
                        ['header_instansi_1', 'Baris 1 (Nama Kementerian)', 'KEMENTERIAN SOSIAL REPUBLIK INDONESIA'],
                        ['header_instansi_2', 'Baris 2 (Direktorat Jenderal)', 'DIREKTORAT JENDERAL PERLINDUNGAN DAN JAMINAN SOSIAL'],
                        ['header_instansi_3', 'Baris 3 (Direktorat)', 'DIREKTORAT PERLINDUNGAN SOSIAL NON KEBENCANAAN'],
                        ['header_instansi_4', 'Baris 4 (Alamat)', 'Jl. Salemba Raya No. 28 Jakarta Pusat Tlp (021)22804288'],
                    ] as [$name, $label, $placeholder])
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $label }}</label>
                            <input type="text" name="{{ $name }}" value="{{ old($name, $laporan->$name) }}"
                                class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="{{ $placeholder }}">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. Isi Laporan --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">3. Isi Laporan</h3>
                <div class="space-y-4">
                    @php
                        $fields = [
                            'latar_belakang' => 'A. Latar Belakang / Umum',
                            'maksud_tujuan' => 'B. Maksud dan Tujuan',
                            'ruang_lingkup' => 'C. Ruang Lingkup',
                            'dasar' => 'D. Dasar Hukum',
                            'kegiatan_dilaksanakan' => 'E. Kegiatan yang Dilaksanakan',
                            'hasil_dicapai' => 'F. Hasil yang Dicapai',
                            'simpulan' => 'G. Simpulan',
                            'saran' => 'H. Saran',
                            'penutup' => 'I. Penutup',
                        ];
                    @endphp
                    @foreach ($fields as $name => $label)
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">{{ $label }}</label>
                            <div id="editor-{{ $name }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-300 dark:border-gray-700" style="min-height:120px;"></div>
                            <textarea name="{{ $name }}" id="hidden-{{ $name }}" class="hidden">{{ old($name, $laporan->$name) }}</textarea>
                            @error($name) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 4. Tanda Tangan --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">4. Tanda Tangan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Data diisi dari laporan sebelumnya.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Dibuat di (Kota)</label>
                        <input type="text" name="ttd_kota" value="{{ old('ttd_kota', $laporan->ttd_kota) }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Tanggal</label>
                        <input type="date" name="ttd_tanggal" value="{{ old('ttd_tanggal', $laporan->ttd_tanggal?->format('Y-m-d')) }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Jabatan</label>
                        <input type="text" name="ttd_jabatan" value="{{ old('ttd_jabatan', $laporan->ttd_jabatan) }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="ttd_nama" value="{{ old('ttd_nama', $laporan->ttd_nama) }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">NIP</label>
                        <input type="text" name="ttd_nip" value="{{ old('ttd_nip', $laporan->ttd_nip) }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>

                @if ($laporan->ttd_gambar)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-950 rounded-xl border border-blue-200 dark:border-blue-800">
                        <img src="{{ Storage::url($laporan->ttd_gambar) }}" class="h-12 object-contain">
                        <p class="text-xs text-blue-700 dark:text-blue-400">TTD tersimpan. Gambar/upload baru untuk mengganti.</p>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Perbarui Tanda Tangan</label>
                    <div class="flex gap-2 mb-3">
                        <button type="button" id="btn-draw" onclick="window.setTtdMode('draw')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition bg-blue-600 text-white">✏️ Gambar TTD</button>
                        <button type="button" id="btn-upload" onclick="window.setTtdMode('upload')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">📁 Upload PNG</button>
                    </div>
                    <div id="ttd-draw-area">
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl overflow-hidden bg-white">
                            <canvas id="ttd-canvas" width="500" height="150" class="w-full cursor-crosshair touch-none"></canvas>
                        </div>
                        <div class="flex gap-2 mt-2">
                            <button type="button" onclick="window.clearTtdCanvas()" class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 dark:bg-red-950 rounded-lg hover:bg-red-100 transition">Hapus</button>
                            <span class="text-xs text-gray-400 self-center">Gambar tanda tangan di atas</span>
                        </div>
                        <input type="hidden" name="ttd_gambar_canvas" id="ttd-canvas-data">
                    </div>
                    <div id="ttd-upload-area" class="hidden">
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl cursor-pointer hover:border-blue-400 transition bg-gray-50 dark:bg-gray-800/50">
                            <svg class="w-5 h-5 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-xs text-gray-500">Upload gambar TTD (PNG/JPG, maks 2MB)</span>
                            <input type="file" name="ttd_gambar" accept=".jpg,.jpeg,.png" class="hidden" onchange="window.previewTtdUpload(this)">
                        </label>
                        <div id="ttd-upload-preview" class="mt-2 hidden">
                            <img id="ttd-upload-img" class="h-20 object-contain border border-gray-200 dark:border-gray-700 rounded-lg p-1">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Dokumentasi Foto --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">5. Dokumentasi Foto</h3>
                    <span class="text-xs text-gray-400" id="foto-counter">0/10 foto</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Maks 10 foto total. Setiap batch maks 5 foto (JPG/PNG, maks 5MB/foto).</p>

                {{-- Foto existing --}}
                <input type="hidden" name="foto_dokumentasi_existing" id="foto-existing-input" value="{{ json_encode($laporan->foto_dokumentasi ?? []) }}">
                @if ($laporan->foto_dokumentasi && count($laporan->foto_dokumentasi) > 0)
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Foto tersimpan:</p>
                        <div id="existing-fotos-grid" class="flex flex-wrap gap-2">
                            @foreach ($laporan->foto_dokumentasi as $idx => $foto)
                                <div class="relative group" id="existing-foto-{{ $idx }}">
                                    <img src="{{ Storage::url($foto) }}" class="w-20 h-20 object-cover rounded-xl border border-gray-200 dark:border-gray-700">
                                    <button type="button" onclick="window.removeExistingFoto({{ $idx }})"
                                        class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition">✕</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div id="foto-inputs-container" class="space-y-3"></div>
                <button type="button" id="btn-add-foto" onclick="window.addFotoBatch()"
                    class="mt-3 inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition border border-blue-200 dark:border-blue-800">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Batch Foto
                </button>

                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Keterangan Dokumentasi</label>
                    <input type="text" name="keterangan_dokumentasi" value="{{ old('keterangan_dokumentasi', $laporan->keterangan_dokumentasi) }}"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Contoh: Pendampingan KPM PKH di Bank BNI">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pb-4">
                <a href="{{ route('laporan.show', $laporan) }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</a>
                <button type="button" onclick="window.openPreview()" class="px-5 py-2.5 text-sm font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition">👁 Preview</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">Perbarui Laporan</button>
            </div>
        </form>
    </div>

    {{-- Preview Modal --}}
    <div id="preview-modal" class="fixed inset-0 z-50 hidden bg-gray-900/80 overflow-y-auto">
        <div class="min-h-screen flex items-start justify-center py-8 px-4">
            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-900">Preview Laporan</h2>
                    <button type="button" onclick="window.closePreview()" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">✕ Tutup</button>
                </div>
                <div id="preview-content" class="p-8 text-sm text-gray-900 font-serif leading-relaxed"></div>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar { border-radius: 0.75rem 0.75rem 0 0 !important; border-color: rgb(209 213 219) !important; background: #f9fafb; }
        .ql-container { border-radius: 0 0 0.75rem 0.75rem !important; border-color: rgb(209 213 219) !important; font-size: 0.875rem; }
        .ql-editor { min-height: 120px; }
        .dark .ql-toolbar { border-color: rgb(55 65 81) !important; background: rgb(31 41 55); }
        .dark .ql-container { border-color: rgb(55 65 81) !important; background: rgb(31 41 55); color: #f3f4f6; }
        .dark .ql-editor.ql-blank::before { color: rgb(107 114 128); }
        .dark .ql-stroke { stroke: #9ca3af !important; }
        .dark .ql-fill { fill: #9ca3af !important; }
        .dark .ql-picker { color: #9ca3af !important; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    @vite('resources/js/laporan/create.js')
    <script>
    window.LAPORAN_RHKS = {!! json_encode($rhks, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
    </script>
    <script>
    // Existing foto management untuk edit
    var existingFotoData = {!! json_encode($laporan->foto_dokumentasi ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
    var existingFotos = existingFotoData.slice(); // copy

    window.removeExistingFoto = function(idx) {
        existingFotos.splice(idx, 1);
        var el = document.getElementById('existing-foto-' + idx);
        if (el) el.remove();
        // Re-index remaining
        document.getElementById('foto-existing-input').value = JSON.stringify(existingFotos);
        updateEditFotoCounter();
    };

    function updateEditFotoCounter() {
        var newCount = 0;
        document.querySelectorAll('#foto-inputs-container input[type=file]').forEach(function(inp) {
            newCount += inp.files ? inp.files.length : 0;
        });
        var total = existingFotos.length + newCount;
        var el = document.getElementById('foto-counter');
        if (el) el.textContent = total + '/10 foto';
        var btn = document.getElementById('btn-add-foto');
        if (btn) btn.style.display = total >= 10 ? 'none' : '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Init RHK selector
        if (window.initRhkSelector) window.initRhkSelector(window.LAPORAN_RHKS);

        // Init Quill
        if (window.initQuillEditors) window.initQuillEditors();

        // Init TTD canvas langsung
        if (window.initTtdCanvas) window.initTtdCanvas();

        // Update counter awal
        updateEditFotoCounter();

        // Form submit
        var form = document.getElementById('form-laporan');
        if (form) {
            form.addEventListener('submit', function() {
                if (window.syncQuillToHidden) window.syncQuillToHidden();
                // Update existing foto
                document.getElementById('foto-existing-input').value = JSON.stringify(existingFotos);
                // Sync TTD canvas
                var canvas = document.getElementById('ttd-canvas');
                var canvasInput = document.getElementById('ttd-canvas-data');
                if (canvas && canvasInput && !canvasInput.value) {
                    canvasInput.value = canvas.toDataURL('image/png');
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
