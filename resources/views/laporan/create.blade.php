<x-app-layout>

    <x-slot name="title">Buat Laporan</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Buat Laporan</h2>
    </x-slot>

    <div class="max-w-3xl">
        <div class="flex items-center gap-2 mb-5">
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Buat Laporan Baru</span>
        </div>

        <form id="form-laporan" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4"
              data-user-ttd="{{ $user->tanda_tangan ? Storage::url($user->tanda_tangan) : '' }}">
            @csrf

            {{-- Banner Template --}}
            @if ($templates->isNotEmpty())
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Gunakan Template</span>
                        </div>
                        <a href="{{ route('laporan.templates') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Kelola template</a>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Pilih template untuk mengisi otomatis isi laporan. Ganti bulan, tahun, tanggal, dan dokumentasi sesuai kebutuhan.</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($templates as $tmpl)
                            <button type="button"
                                onclick="window.loadTemplate({{ $tmpl->id }})"
                                data-template-id="{{ $tmpl->id }}"
                                class="template-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-950 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900 transition border border-purple-200 dark:border-purple-800">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                {{ Str::limit($tmpl->template_name, 40) }}
                            </button>
                        @endforeach
                    </div>
                    <div id="template-loaded-banner" class="hidden mt-3 flex items-center gap-2 p-2.5 bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800 rounded-xl text-xs text-green-700 dark:text-green-400">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span id="template-loaded-text">Template berhasil dimuat. Silakan sesuaikan bulan, tahun, dan tanggal TTD.</span>
                    </div>
                </div>
            @endif

            {{-- Banner info kuota --}}
            @php $sub = $user->activeSubscription(); @endphp
            @if ($sub)
                @php
                    $sisa = $sub->sisaLaporan();
                    $batas = $sub->billingPlan->batas_laporan_per_bulan;
                    $digunakan = $sub->laporan_digunakan;
                    $isTrial = $sub->billingPlan->is_trial;
                    $isUnlimited = $sub->billingPlan->isUnlimited();
                @endphp
                @if (!$isUnlimited)
                    <div class="flex items-center gap-3 p-3.5 rounded-2xl border
                        {{ $sisa === 0 ? 'bg-red-50 dark:bg-red-950 border-red-200 dark:border-red-800' : ($sisa <= 1 ? 'bg-amber-50 dark:bg-amber-950 border-amber-200 dark:border-amber-800' : 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800') }}">
                        <div class="shrink-0">
                            @if ($sisa === 0)
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            @elseif ($sisa <= 1)
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            @if ($sisa === 0)
                                <p class="text-sm font-semibold text-red-800 dark:text-red-300">Kuota laporan habis!</p>
                                <p class="text-xs text-red-600 dark:text-red-400">Anda tidak dapat membuat laporan baru. Upgrade paket untuk melanjutkan.</p>
                            @else
                                <p class="text-sm font-semibold {{ $sisa <= 1 ? 'text-amber-800 dark:text-amber-300' : 'text-blue-800 dark:text-blue-300' }}">
                                    {{ $isTrial ? 'Paket Trial' : 'Paket ' . $sub->billingPlan->nama }}
                                    — Sisa <strong>{{ $sisa }}</strong> dari {{ $batas }} laporan
                                </p>
                                <p class="text-xs {{ $sisa <= 1 ? 'text-amber-600 dark:text-amber-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    Sudah digunakan: {{ $digunakan }} laporan
                                    @if ($isTrial) · Trial berakhir {{ $sub->berakhir_at->format('d M Y') }} @endif
                                </p>
                            @endif
                        </div>
                        @if ($sisa === 0 || $isTrial)
                            <a href="{{ route('subscription.status') }}"
                               class="shrink-0 px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                                {{ $sisa === 0 ? 'Upgrade' : 'Lihat Paket' }}
                            </a>
                        @endif
                    </div>
                @endif
            @endif

            {{-- 1. RHK & Periode --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">1. Pilih RHK & Periode</h3>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Bulan <span class="text-red-500">*</span></label>
                        <select name="bulan" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">Pilih bulan</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                                <option value="{{ $bulan }}" {{ old('bulan') === $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                            @endforeach
                        </select>
                        @error('bulan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Tahun <span class="text-red-500">*</span></label>
                        <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" min="2020" max="2099" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        @error('tahun') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">RHK <span class="text-red-500">*</span></label>
                    <select name="rhk_id" id="rhk-select" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Pilih RHK</option>
                        @foreach ($rhks as $rhk)
                            <option value="{{ $rhk->id }}" {{ old('rhk_id') == $rhk->id ? 'selected' : '' }}>{{ $rhk->urutan }}. {{ $rhk->nama }}</option>
                        @endforeach
                    </select>
                    @error('rhk_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div id="jenis-rhk-wrapper" class="{{ old('rhk_id') ? '' : 'hidden' }}">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Jenis RHK <span class="text-red-500">*</span></label>
                    <select name="jenis_rhk_id" id="jenis-rhk-select"
                        data-selected="{{ old('jenis_rhk_id') }}"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Pilih jenis RHK</option>
                        @if(old('rhk_id'))
                            @foreach($rhks->firstWhere('id', old('rhk_id'))?->jenisRhks ?? [] as $jenis)
                                <option value="{{ $jenis->id }}" {{ old('jenis_rhk_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @error('jenis_rhk_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                @error('rhk_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
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
                            <input type="text" name="{{ $name }}" value="{{ old($name) }}"
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
                            <textarea name="{{ $name }}" id="hidden-{{ $name }}" class="hidden">{{ old($name) }}</textarea>
                            @error($name) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 4. Tanda Tangan --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">4. Tanda Tangan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Data diisi otomatis dari profil Anda.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Dibuat di (Kota)</label>
                        <input type="text" name="ttd_kota" value="{{ old('ttd_kota', $user->kecamatan ?? $user->kabupaten ?? '') }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Mojokerto">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Tanggal</label>
                        <input type="date" name="ttd_tanggal" value="{{ old('ttd_tanggal', date('Y-m-d')) }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Jabatan</label>
                        <input type="text" name="ttd_jabatan" value="{{ old('ttd_jabatan', $user->jabatan ?? '') }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Penata Layanan Operasional">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="ttd_nama" value="{{ old('ttd_nama', $user->name ?? '') }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">NIP</label>
                        <input type="text" name="ttd_nip" value="{{ old('ttd_nip', $user->nip ?? '') }}" class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="198906132025211066">
                    </div>
                </div>

                @if ($user->tanda_tangan)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-green-50 dark:bg-green-950 rounded-xl border border-green-200 dark:border-green-800">
                        <img src="{{ Storage::url($user->tanda_tangan) }}" class="h-10 object-contain">
                        <div>
                            <p class="text-xs font-medium text-green-700 dark:text-green-400">TTD dari profil akan digunakan otomatis</p>
                            <p class="text-xs text-green-600 dark:text-green-500">Gambar/upload baru di bawah untuk mengganti khusus laporan ini</p>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Tanda Tangan</label>
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

                {{-- GPS Photo Picker (dalam dokumentasi) --}}
                <div class="mb-5 pb-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400">Pilih Foto GPS (Opsional)</label>
                        <div class="flex items-center gap-2">
                            @if ($gpsPhotos->isNotEmpty())
                                <button type="button" onclick="window.openGpsPhotoModal()" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                    📷 Pilih dari galeri
                                </button>
                            @endif
                            <a href="{{ route('gps-photo.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Buat foto baru</a>
                        </div>
                    </div>
                    
                    {{-- Selected GPS Photo Preview --}}
                    @php
                        $selectedGpsPhoto = null;
                        if (old('gps_photo_id')) {
                            $selectedGpsPhoto = $gpsPhotos->firstWhere('id', old('gps_photo_id'));
                        }
                    @endphp
                    <div id="gps-photo-selected" class="@if (!$selectedGpsPhoto) hidden @endif">
                        <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-950 rounded-xl border border-blue-200 dark:border-blue-800">
                            <img id="gps-photo-preview-img" src="@if ($selectedGpsPhoto){{ Storage::url('gps-photos/' . $selectedGpsPhoto->filename) }}@endif" alt="GPS Photo" class="h-16 w-16 object-cover rounded-lg">
                            <div class="flex-1 min-w-0">
                                <p id="gps-photo-preview-name" class="text-sm font-medium text-gray-900 dark:text-white truncate">@if ($selectedGpsPhoto){{ $selectedGpsPhoto->original_filename }}@endif</p>
                                <p id="gps-photo-preview-date" class="text-xs text-gray-600 dark:text-gray-400">@if ($selectedGpsPhoto){{ $selectedGpsPhoto->created_at->format('d M Y H:i') }}@endif</p>
                                <p id="gps-photo-preview-location" class="text-xs text-gray-600 dark:text-gray-400 truncate">@if ($selectedGpsPhoto)📍 {{ $selectedGpsPhoto->address }}@endif</p>
                            </div>
                            <button type="button" onclick="window.clearGpsPhotoSelection()" class="px-2 py-1 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950 rounded-lg transition">
                                ✕ Hapus
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="gps_photo_id" id="gps-photo-id-input" value="{{ old('gps_photo_id') }}">
                </div>

                <div id="foto-inputs-container" class="space-y-3"></div>
                <button type="button" id="btn-add-foto" onclick="window.addFotoBatch()"
                    class="mt-3 inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition border border-blue-200 dark:border-blue-800">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Batch Foto
                </button>
                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Keterangan Dokumentasi</label>
                    <input type="text" name="keterangan_dokumentasi" value="{{ old('keterangan_dokumentasi') }}"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Contoh: Pendampingan KPM PKH di Bank BNI">
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pb-4">
                <a href="{{ route('laporan.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</a>
                <button type="button" onclick="window.openPreview()" class="px-5 py-2.5 text-sm font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition">👁 Preview</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">Simpan Laporan</button>
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

    {{-- GPS Photo Modal --}}
    <div id="gps-photo-modal" class="fixed inset-0 z-50 hidden bg-gray-900/80 overflow-y-auto">
        <div class="min-h-screen flex items-start justify-center py-8 px-4">
            <div class="bg-white dark:bg-gray-900 w-full max-w-2xl rounded-2xl shadow-2xl">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="font-semibold text-gray-900 dark:text-white">Pilih Foto GPS</h2>
                    <button type="button" onclick="window.closeGpsPhotoModal()" class="px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition">✕ Tutup</button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6">
                    {{-- Filter/Folder Tabs --}}
                    <div class="mb-6">
                        <div class="flex items-center gap-2 overflow-x-auto pb-2">
                            <button type="button" onclick="window.filterGpsPhotos('all')" class="filter-tab px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap transition active bg-blue-600 text-white" data-filter="all">
                                📁 Semua ({{ $gpsPhotos->count() }})
                            </button>
                            @php
                                $groupedPhotos = $gpsPhotos->groupBy(function($photo) {
                                    return $photo->created_at->format('Y-m-d');
                                })->sortByDesc(function($group, $date) {
                                    return $date;
                                });
                            @endphp
                            @foreach ($groupedPhotos as $date => $photos)
                                @php
                                    $dateObj = \Carbon\Carbon::parse($date);
                                    $today = \Carbon\Carbon::today();
                                    $yesterday = $today->copy()->subDay();
                                    
                                    if ($dateObj->isSameDay($today)) {
                                        $label = 'Hari Ini';
                                    } elseif ($dateObj->isSameDay($yesterday)) {
                                        $label = 'Kemarin';
                                    } else {
                                        $label = $dateObj->translatedFormat('d M Y');
                                    }
                                @endphp
                                <button type="button" onclick="window.filterGpsPhotos('{{ $date }}')" class="filter-tab px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap transition bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700" data-filter="{{ $date }}">
                                    📅 {{ $label }} ({{ $photos->count() }})
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Photos Grid --}}
                    <div id="gps-photos-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($gpsPhotos as $photo)
                            <div class="gps-photo-item" data-date="{{ $photo->created_at->format('Y-m-d') }}" data-id="{{ $photo->id }}">
                                <button type="button" onclick="window.selectGpsPhoto({{ $photo->id }}, '{{ Storage::url('gps-photos/' . $photo->filename) }}', '{{ $photo->original_filename }}', '{{ $photo->created_at->format('d M Y H:i') }}', '{{ $photo->address }}')" class="w-full group">
                                    <div class="relative h-32 rounded-xl border-2 border-gray-300 dark:border-gray-700 overflow-hidden hover:border-blue-500 transition">
                                        <img src="{{ Storage::url('gps-photos/' . $photo->filename) }}" 
                                             alt="{{ $photo->original_filename }}"
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 truncate font-medium">{{ $photo->original_filename }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $photo->created_at->format('d M Y H:i') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 truncate">📍 {{ $photo->address }}</p>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    @if ($gpsPhotos->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Belum ada foto GPS. <a href="{{ route('gps-photo.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Buat foto baru</a></p>
                        </div>
                    @endif
                </div>
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
    // Data dari server — dipass ke JS file via window variable
    window.LAPORAN_RHKS = {!! json_encode($rhks, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
    window.USER_TTD_URL = '{{ $user->tanda_tangan ? Storage::url($user->tanda_tangan) : '' }}';
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init RHK selector
        if (window.initRhkSelector) window.initRhkSelector(window.LAPORAN_RHKS);

        // Init Quill
        if (window.initQuillEditors) window.initQuillEditors();

        // Init foto batch
        if (window.addFotoBatch) window.addFotoBatch();

        // Init TTD canvas langsung saat load
        if (window.initTtdCanvas) window.initTtdCanvas();

        // Form submit
        var form = document.getElementById('form-laporan');
        if (form) {
            form.addEventListener('submit', function() {
                if (window.syncQuillToHidden) window.syncQuillToHidden();
                // Sync TTD canvas — ambil data terbaru
                var canvas = document.getElementById('ttd-canvas');
                var canvasInput = document.getElementById('ttd-canvas-data');
                if (canvas && canvasInput) {
                    // Hanya simpan jika canvas tidak kosong (ada gambar)
                    var blank = document.createElement('canvas');
                    blank.width = canvas.width;
                    blank.height = canvas.height;
                    if (canvas.toDataURL() !== blank.toDataURL()) {
                        canvasInput.value = canvas.toDataURL('image/png');
                    }
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
