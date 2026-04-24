<section>
    <div class="mb-5">
        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Informasi Profil</h2>
        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Perbarui data diri dan informasi kepegawaian Anda.</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        {{-- Avatar & nama --}}
        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center shrink-0">
                <span class="text-xl font-bold text-blue-700 dark:text-blue-300">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                @if ($user->jabatan)
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">{{ $user->jabatan }}</p>
                @endif
            </div>
        </div>

        {{-- Identitas dasar --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Identitas Dasar</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @if ($errors->get('name'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div>
                    <label for="email" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @if ($errors->get('email'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('email') }}</p>
                    @endif
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs text-amber-600 dark:text-amber-400">Email belum diverifikasi.</span>
                            <button form="send-verification" class="text-xs text-blue-600 hover:underline">Kirim ulang</button>
                        </div>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 text-xs text-green-600 dark:text-green-400">Tautan verifikasi telah dikirim.</p>
                        @endif
                    @endif
                </div>

                <div>
                    <label for="nip" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">NIP</label>
                    <input id="nip" name="nip" type="text" value="{{ old('nip', $user->nip) }}" maxlength="30"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Nomor Induk Pegawai">
                    @if ($errors->get('nip'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('nip') }}</p>
                    @endif
                </div>

                <div class="sm:col-span-2">
                    <label for="jabatan" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Jabatan</label>
                    <input id="jabatan" name="jabatan" type="text" value="{{ old('jabatan', $user->jabatan) }}"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Contoh: Penata Layanan Operasional">
                    @if ($errors->get('jabatan'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('jabatan') }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Wilayah tugas --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Wilayah Tugas</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="desa" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Desa / Kelurahan</label>
                    <input id="desa" name="desa" type="text" value="{{ old('desa', $user->desa) }}"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Nama desa/kelurahan">
                    @if ($errors->get('desa'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('desa') }}</p>
                    @endif
                </div>

                <div>
                    <label for="kecamatan" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Kecamatan</label>
                    <input id="kecamatan" name="kecamatan" type="text" value="{{ old('kecamatan', $user->kecamatan) }}"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Nama kecamatan">
                    @if ($errors->get('kecamatan'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('kecamatan') }}</p>
                    @endif
                </div>

                <div>
                    <label for="kabupaten" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Kabupaten / Kota</label>
                    <input id="kabupaten" name="kabupaten" type="text" value="{{ old('kabupaten', $user->kabupaten) }}"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Nama kabupaten/kota">
                    @if ($errors->get('kabupaten'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('kabupaten') }}</p>
                    @endif
                </div>

                <div>
                    <label for="provinsi" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Provinsi</label>
                    <input id="provinsi" name="provinsi" type="text" value="{{ old('provinsi', $user->provinsi) }}"
                        class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Nama provinsi">
                    @if ($errors->get('provinsi'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('provinsi') }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tanda Tangan --}}
        <div x-data="ttdProfileManager()" class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-800">
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Tanda Tangan</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">TTD ini akan otomatis digunakan saat membuat laporan.</p>

            @if ($user->tanda_tangan)
                <div class="mb-3 flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-950 rounded-xl border border-blue-200 dark:border-blue-800">
                    <img src="{{ Storage::url($user->tanda_tangan) }}" class="h-12 object-contain">
                    <p class="text-xs text-blue-700 dark:text-blue-400">TTD tersimpan. Gambar/upload baru untuk mengganti.</p>
                </div>
            @endif

            <div class="flex gap-2 mb-3">
                <button type="button" @click="mode='draw'" :class="mode==='draw' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition">✏️ Gambar TTD</button>
                <button type="button" @click="mode='upload'" :class="mode==='upload' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition">📁 Upload PNG</button>
            </div>

            <div x-show="mode==='draw'" x-transition>
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl overflow-hidden bg-white">
                    <canvas id="profile-ttd-canvas" width="500" height="150" class="w-full cursor-crosshair touch-none"></canvas>
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="button" @click="clearCanvas()" class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 dark:bg-red-950 rounded-lg hover:bg-red-100 transition">Hapus</button>
                    <span class="text-xs text-gray-400 self-center">Gambar tanda tangan di atas</span>
                </div>
                <input type="hidden" name="tanda_tangan_canvas" id="profile-ttd-canvas-data">
            </div>

            <div x-show="mode==='upload'" x-transition>
                <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl cursor-pointer hover:border-blue-400 transition bg-gray-50 dark:bg-gray-800/50">
                    <svg class="w-5 h-5 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-xs text-gray-500">Upload gambar TTD (PNG/JPG, maks 2MB)</span>
                    <input type="file" name="tanda_tangan" accept=".jpg,.jpeg,.png" class="hidden" @change="previewTtd($event)">
                </label>
                <div x-show="ttdPreview" class="mt-2">
                    <img :src="ttdPreview" class="h-16 object-contain border border-gray-200 dark:border-gray-700 rounded-lg p-1">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button type="submit"
                class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span x-data="{ show: true }" x-show="show" x-transition
                      x-init="setTimeout(() => show = false, 3000)"
                      class="flex items-center gap-1.5 text-sm text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tersimpan
                </span>
            @endif
        </div>
    </form>
</section>

@push('scripts')
<script>
function ttdProfileManager() {
    return {
        mode: 'draw', ttdPreview: null, drawing: false, canvas: null, ctx: null,
        init() { this.$nextTick(() => this.initCanvas()); },
        initCanvas() {
            this.canvas = document.getElementById('profile-ttd-canvas');
            if (!this.canvas) return;
            this.ctx = this.canvas.getContext('2d');
            this.ctx.strokeStyle = '#1a1a1a'; this.ctx.lineWidth = 2; this.ctx.lineCap = 'round'; this.ctx.lineJoin = 'round';
            const getPos = (e) => { const rect = this.canvas.getBoundingClientRect(); const scaleX = this.canvas.width / rect.width; const scaleY = this.canvas.height / rect.height; const clientX = e.touches ? e.touches[0].clientX : e.clientX; const clientY = e.touches ? e.touches[0].clientY : e.clientY; return { x: (clientX - rect.left) * scaleX, y: (clientY - rect.top) * scaleY }; };
            this.canvas.addEventListener('mousedown', (e) => { this.drawing = true; const p = getPos(e); this.ctx.beginPath(); this.ctx.moveTo(p.x, p.y); });
            this.canvas.addEventListener('mousemove', (e) => { if (!this.drawing) return; const p = getPos(e); this.ctx.lineTo(p.x, p.y); this.ctx.stroke(); });
            this.canvas.addEventListener('mouseup', () => { this.drawing = false; this.syncCanvas(); });
            this.canvas.addEventListener('mouseleave', () => { this.drawing = false; });
            this.canvas.addEventListener('touchstart', (e) => { e.preventDefault(); this.drawing = true; const p = getPos(e); this.ctx.beginPath(); this.ctx.moveTo(p.x, p.y); }, { passive: false });
            this.canvas.addEventListener('touchmove', (e) => { e.preventDefault(); if (!this.drawing) return; const p = getPos(e); this.ctx.lineTo(p.x, p.y); this.ctx.stroke(); }, { passive: false });
            this.canvas.addEventListener('touchend', () => { this.drawing = false; this.syncCanvas(); });
        },
        syncCanvas() { const input = document.getElementById('profile-ttd-canvas-data'); if (input) input.value = this.canvas.toDataURL('image/png'); },
        clearCanvas() { if (this.ctx) this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height); const input = document.getElementById('profile-ttd-canvas-data'); if (input) input.value = ''; },
        previewTtd(e) { const file = e.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (ev) => { this.ttdPreview = ev.target.result; }; reader.readAsDataURL(file); } }
    }
}
</script>
@endpush
