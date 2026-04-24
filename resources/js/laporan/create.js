// Laporan Create - All JS logic
// Loaded via @vite in create.blade.php

// ── RHK Selector ─────────────────────────────────────────────────────────────
window.initRhkSelector = function (rhksJson) {
    var rhksData = rhksJson;
    var rhkSelect = document.getElementById('rhk-select');
    var jenisWrapper = document.getElementById('jenis-rhk-wrapper');
    var jenisSelect = document.getElementById('jenis-rhk-select');
    if (!rhkSelect) return;

    function loadJenis(rhkId, selectedJenisId) {
        jenisSelect.innerHTML = '<option value="">Pilih jenis RHK</option>';
        if (!rhkId) { jenisWrapper.classList.add('hidden'); return; }
        var rhk = rhksData.find(function (r) { return String(r.id) === String(rhkId); });
        if (rhk && rhk.jenis_rhks && rhk.jenis_rhks.length > 0) {
            rhk.jenis_rhks.forEach(function (j) {
                var opt = document.createElement('option');
                opt.value = j.id;
                opt.textContent = j.nama;
                jenisSelect.appendChild(opt);
            });
            jenisWrapper.classList.remove('hidden');
            if (selectedJenisId) {
                jenisSelect.value = selectedJenisId;
            } else if (rhk.jenis_rhks.length === 1) {
                jenisSelect.value = rhk.jenis_rhks[0].id;
            }
        } else {
            jenisWrapper.classList.add('hidden');
        }
    }

    rhkSelect.addEventListener('change', function () {
        loadJenis(this.value, null);
    });

    // Trigger jika ada old value — baca selected jenis dari data attribute
    if (rhkSelect.value) {
        var oldJenisId = jenisSelect ? jenisSelect.getAttribute('data-selected') : null;
        loadJenis(rhkSelect.value, oldJenisId);
    }
};

// ── TTD Canvas ────────────────────────────────────────────────────────────────
var ttdCanvas = null, ttdCtx = null, ttdDrawing = false;

window.setTtdMode = function (mode) {
    var drawArea = document.getElementById('ttd-draw-area');
    var uploadArea = document.getElementById('ttd-upload-area');
    var btnDraw = document.getElementById('btn-draw');
    var btnUpload = document.getElementById('btn-upload');
    var activeClass = 'bg-blue-600 text-white';
    var inactiveClass = 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300';
    if (mode === 'draw') {
        drawArea.classList.remove('hidden');
        uploadArea.classList.add('hidden');
        btnDraw.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition ' + activeClass;
        btnUpload.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition ' + inactiveClass;
        // Init canvas setelah area visible
        requestAnimationFrame(function () { initTtdCanvas(); });
    } else {
        drawArea.classList.add('hidden');
        uploadArea.classList.remove('hidden');
        btnDraw.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition ' + inactiveClass;
        btnUpload.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition ' + activeClass;
    }
};

function initTtdCanvas() {
    var canvasEl = document.getElementById('ttd-canvas');
    if (!canvasEl) return;

    // Jika sudah diinit, cukup pastikan ctx masih valid
    if (ttdCanvas === canvasEl && ttdCtx) return;

    // Clone untuk hapus listener lama (hanya jika beda element)
    if (ttdCanvas && ttdCanvas !== canvasEl) {
        var newCanvas = canvasEl.cloneNode(true);
        canvasEl.parentNode.replaceChild(newCanvas, canvasEl);
        ttdCanvas = newCanvas;
    } else {
        ttdCanvas = canvasEl;
    }

    ttdCtx = ttdCanvas.getContext('2d');
    ttdCtx.strokeStyle = '#1a1a1a';
    ttdCtx.lineWidth = 2.5;
    ttdCtx.lineCap = 'round';
    ttdCtx.lineJoin = 'round';

    function getPos(e) {
        var rect = ttdCanvas.getBoundingClientRect();
        var scaleX = ttdCanvas.width / rect.width;
        var scaleY = ttdCanvas.height / rect.height;
        var clientX = e.touches ? e.touches[0].clientX : e.clientX;
        var clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return { x: (clientX - rect.left) * scaleX, y: (clientY - rect.top) * scaleY };
    }

    ttdCanvas.addEventListener('mousedown', function (e) {
        e.preventDefault();
        ttdDrawing = true;
        var p = getPos(e);
        ttdCtx.beginPath();
        ttdCtx.moveTo(p.x, p.y);
    });
    ttdCanvas.addEventListener('mousemove', function (e) {
        if (!ttdDrawing) return;
        var p = getPos(e);
        ttdCtx.lineTo(p.x, p.y);
        ttdCtx.stroke();
    });
    ttdCanvas.addEventListener('mouseup', function () { ttdDrawing = false; syncTtdCanvas(); });
    ttdCanvas.addEventListener('mouseleave', function () { if (ttdDrawing) { ttdDrawing = false; syncTtdCanvas(); } });
    ttdCanvas.addEventListener('touchstart', function (e) {
        e.preventDefault();
        ttdDrawing = true;
        var p = getPos(e);
        ttdCtx.beginPath();
        ttdCtx.moveTo(p.x, p.y);
    }, { passive: false });
    ttdCanvas.addEventListener('touchmove', function (e) {
        e.preventDefault();
        if (!ttdDrawing) return;
        var p = getPos(e);
        ttdCtx.lineTo(p.x, p.y);
        ttdCtx.stroke();
    }, { passive: false });
    ttdCanvas.addEventListener('touchend', function () { ttdDrawing = false; syncTtdCanvas(); });
}

function syncTtdCanvas() {
    var input = document.getElementById('ttd-canvas-data');
    if (input && ttdCanvas) input.value = ttdCanvas.toDataURL('image/png');
}

window.clearTtdCanvas = function () {
    if (ttdCtx && ttdCanvas) ttdCtx.clearRect(0, 0, ttdCanvas.width, ttdCanvas.height);
    var input = document.getElementById('ttd-canvas-data');
    if (input) input.value = '';
};

window.previewTtdUpload = function (input) {
    var file = input.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        var preview = document.getElementById('ttd-upload-preview');
        var img = document.getElementById('ttd-upload-img');
        img.src = e.target.result;
        preview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
};

// Expose initTtdCanvas ke global agar bisa dipanggil dari Blade
window.initTtdCanvas = initTtdCanvas;

// ── Foto Manager ──────────────────────────────────────────────────────────────
var fotoBatches = [];
var fotoNextId = 0;

function getTotalFoto() {
    return fotoBatches.reduce(function (s, b) { return s + b.count; }, 0);
}

function updateFotoCounter() {
    var el = document.getElementById('foto-counter');
    if (el) el.textContent = getTotalFoto() + '/10 foto';
    var btn = document.getElementById('btn-add-foto');
    if (btn) btn.style.display = getTotalFoto() >= 10 ? 'none' : '';
}

window.addFotoBatch = function () {
    if (getTotalFoto() >= 10) return;
    var id = fotoNextId++;
    fotoBatches.push({ id: id, count: 0 });
    var container = document.getElementById('foto-inputs-container');
    var batchNum = fotoBatches.length;
    var div = document.createElement('div');
    div.id = 'foto-batch-' + id;
    div.className = 'p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700';
    div.innerHTML =
        '<div class="flex items-center justify-between mb-2">' +
        '<span class="text-xs font-medium text-gray-600 dark:text-gray-400">Batch foto ' + batchNum + '</span>' +
        (batchNum > 1 ? '<button type="button" onclick="window.removeFotoBatch(' + id + ')" class="text-xs text-red-500 hover:text-red-700 transition">Hapus</button>' : '') +
        '</div>' +
        '<input type="file" name="foto_dokumentasi[]" accept=".jpg,.jpeg,.png" multiple ' +
        'onchange="window.onFotoChange(this,' + id + ')" ' +
        'class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">' +
        '<div id="foto-previews-' + id + '" class="flex flex-wrap gap-2 mt-2"></div>' +
        '<p id="foto-error-' + id + '" class="mt-1 text-xs text-red-500 hidden"></p>';
    container.appendChild(div);
    updateFotoCounter();
};

window.removeFotoBatch = function (id) {
    var idx = fotoBatches.findIndex(function (b) { return b.id === id; });
    if (idx > -1) fotoBatches.splice(idx, 1);
    var el = document.getElementById('foto-batch-' + id);
    if (el) el.remove();
    updateFotoCounter();
};

window.onFotoChange = function (input, id) {
    var files = Array.from(input.files);
    var batch = fotoBatches.find(function (b) { return b.id === id; });
    var errorEl = document.getElementById('foto-error-' + id);
    var previewEl = document.getElementById('foto-previews-' + id);
    errorEl.classList.add('hidden');
    previewEl.innerHTML = '';
    if (!batch) return;

    var oldCount = batch.count;
    if (files.length > 5) {
        errorEl.textContent = 'Maksimal 5 foto per batch.';
        errorEl.classList.remove('hidden');
        input.value = '';
        return;
    }
    var remaining = 10 - (getTotalFoto() - oldCount);
    if (files.length > remaining) {
        errorEl.textContent = 'Hanya bisa tambah ' + remaining + ' foto lagi (total maks 10).';
        errorEl.classList.remove('hidden');
        input.value = '';
        return;
    }
    batch.count = files.length;
    updateFotoCounter();
    files.forEach(function (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-16 h-16 object-cover rounded-lg border border-gray-200 dark:border-gray-700';
            previewEl.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
};

// ── Quill Editors ─────────────────────────────────────────────────────────────
var quillInstances = {};

window.initQuillEditors = function () {
    var fields = ['latar_belakang', 'maksud_tujuan', 'ruang_lingkup', 'dasar', 'kegiatan_dilaksanakan', 'hasil_dicapai', 'simpulan', 'saran', 'penutup'];
    var toolbar = [['bold', 'italic', 'underline'], [{ 'list': 'ordered' }, { 'list': 'bullet' }], [{ 'indent': '-1' }, { 'indent': '+1' }], [{ 'align': [] }], ['clean']];

    fields.forEach(function (field) {
        var editorEl = document.getElementById('editor-' + field);
        var hiddenEl = document.getElementById('hidden-' + field);
        if (!editorEl || !window.Quill) return;
        var quill = new window.Quill(editorEl, {
            theme: 'snow',
            placeholder: 'Isi bagian ini...',
            modules: { toolbar: toolbar }
        });
        var init = hiddenEl ? hiddenEl.value.trim() : '';
        if (init) {
            if (init.startsWith('<')) { quill.root.innerHTML = init; }
            else { quill.setText(init); }
        }
        quillInstances[field] = quill;
    });
};

window.syncQuillToHidden = function () {
    Object.keys(quillInstances).forEach(function (field) {
        var quill = quillInstances[field];
        var hiddenEl = document.getElementById('hidden-' + field);
        if (!quill || !hiddenEl) return;
        var content = quill.root.innerHTML;
        hiddenEl.value = content === '<p><br></p>' ? '' : content;
    });
};

// ── Preview ───────────────────────────────────────────────────────────────────
function getVal(name) {
    var el = document.querySelector('[name="' + name + '"]');
    return el ? el.value : '';
}

function getQuillHtml(field) {
    var q = quillInstances[field];
    if (!q) return '';
    var html = q.root.innerHTML;
    return html === '<p><br></p>' ? '' : html;
}

window.openPreview = function () {
    var sections = [
        {
            label: 'A. PENDAHULUAN', sub: [
                { title: '1. Umum', content: getQuillHtml('latar_belakang') },
                { title: '2. Maksud dan Tujuan', content: getQuillHtml('maksud_tujuan') },
                { title: '3. Ruang Lingkup', content: getQuillHtml('ruang_lingkup') },
                { title: '4. Dasar', content: getQuillHtml('dasar') },
            ]
        },
        { label: 'B. KEGIATAN YANG DILAKSANAKAN', content: getQuillHtml('kegiatan_dilaksanakan') },
        { label: 'C. HASIL YANG DICAPAI', content: getQuillHtml('hasil_dicapai') },
        {
            label: 'D. SIMPULAN DAN SARAN', sub: [
                { title: '1. Simpulan', content: getQuillHtml('simpulan') },
                { title: '2. Saran', content: getQuillHtml('saran') },
            ]
        },
        { label: 'E. PENUTUP', content: getQuillHtml('penutup') },
    ];

    var h1 = getVal('header_instansi_1') || 'KEMENTERIAN SOSIAL REPUBLIK INDONESIA';
    var h2 = getVal('header_instansi_2') || 'DIREKTORAT JENDERAL PERLINDUNGAN DAN JAMINAN SOSIAL';
    var h3 = getVal('header_instansi_3') || 'DIREKTORAT PERLINDUNGAN SOSIAL NON KEBENCANAAN';
    var h4 = getVal('header_instansi_4') || 'Jl. Salemba Raya No. 28 Jakarta Pusat Tlp (021)22804288';
    var bulan = getVal('bulan').toUpperCase();
    var tahun = getVal('tahun');
    var jenisSelect = document.querySelector('[name="jenis_rhk_id"]');
    var jenisNama = jenisSelect && jenisSelect.selectedIndex >= 0 ? jenisSelect.options[jenisSelect.selectedIndex].text : '';

    var html = '<div style="font-family:\'Times New Roman\',serif;font-size:12pt;line-height:1.6;color:#000;">';
    html += '<table style="width:100%;border-bottom:3px solid #000;margin-bottom:16px;" cellpadding="0" cellspacing="0"><tr>';
    html += '<td style="width:80px;vertical-align:middle;"><img src="/logo-kemensos.svg" style="width:70px;height:auto;"></td>';
    html += '<td style="text-align:center;vertical-align:middle;">';
    html += '<div style="font-size:13pt;font-weight:bold;">' + h1 + '</div>';
    html += '<div style="font-size:11pt;font-weight:bold;">' + h2 + '</div>';
    html += '<div style="font-size:11pt;font-weight:bold;">' + h3 + '</div>';
    html += '<div style="font-size:9pt;">' + h4 + '</div>';
    html += '</td></tr></table>';

    html += '<div style="text-align:center;margin:16px 0;">';
    html += '<div style="font-weight:bold;">LAPORAN</div>';
    html += '<div style="font-weight:bold;">SASARAN KINERJA PEGAWAI (SKP)</div>';
    html += '<div style="font-weight:bold;text-transform:uppercase;">' + jenisNama + '</div>';
    html += '<div style="font-weight:bold;">BULAN ' + bulan + ' TAHUN ' + tahun + '</div>';
    html += '</div>';

    sections.forEach(function (sec) {
        html += '<div style="margin-top:12px;"><strong>' + sec.label + '</strong></div>';
        if (sec.sub) {
            sec.sub.forEach(function (s) {
                if (s.content) {
                    html += '<div style="margin-top:6px;"><strong>' + s.title + '</strong></div>';
                    html += '<div style="margin-left:16px;">' + s.content + '</div>';
                }
            });
        } else if (sec.content) {
            html += '<div style="margin-top:4px;">' + sec.content + '</div>';
        }
    });

    var kota = getVal('ttd_kota');
    var tgl = getVal('ttd_tanggal');
    var jabatan = getVal('ttd_jabatan');
    var nama = getVal('ttd_nama');
    var nip = getVal('ttd_nip');
    var canvasInput = document.getElementById('ttd-canvas-data');
    var canvasData = canvasInput ? canvasInput.value : '';
    var ttdUploadImg = document.getElementById('ttd-upload-img');
    var tglFormatted = '';
    if (tgl) {
        var d = new Date(tgl);
        tglFormatted = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    html += '<table style="width:100%;margin-top:30px;" cellpadding="0" cellspacing="0"><tr>';
    html += '<td style="width:50%;"></td>';
    html += '<td style="width:50%;text-align:left;padding-left:20px;">';
    html += '<div>Dibuat di ' + (kota || '...') + ',</div>';
    html += '<div>Pada Tanggal ' + (tglFormatted || '...') + '</div>';
    html += '<div>' + (jabatan || '') + '</div>';
    if (canvasData && canvasData.length > 100) {
        html += '<div style="margin:10px 0;"><img src="' + canvasData + '" style="height:80px;"></div>';
    } else if (ttdUploadImg && ttdUploadImg.src && ttdUploadImg.src.startsWith('data:')) {
        html += '<div style="margin:10px 0;"><img src="' + ttdUploadImg.src + '" style="height:80px;"></div>';
    } else {
        // Coba ambil dari data attribute di form
        var profileTtd = window.USER_TTD_URL || '';
        if (!profileTtd) {
            var formEl = document.getElementById('form-laporan');
            profileTtd = formEl ? (formEl.getAttribute('data-user-ttd') || '') : '';
        }
        if (profileTtd) {
            html += '<div style="margin:10px 0;"><img src="' + profileTtd + '" style="height:80px;"></div>';
        } else {
            html += '<div style="height:80px;"></div>';
        }
    }
    html += '<div><strong><u>' + (nama || '...') + '</u></strong></div>';
    html += '<div>NIP. ' + (nip || '...') + '</div>';
    html += '</td></tr></table>';

    var allPreviews = [];
    document.querySelectorAll('#foto-inputs-container img').forEach(function (img) {
        if (img.src && img.src.startsWith('data:')) allPreviews.push(img.src);
    });
    var keterangan = getVal('keterangan_dokumentasi');
    if (allPreviews.length > 0) {
        html += '<div style="margin-top:24px;text-align:center;"><strong>DOKUMENTASI</strong></div>';
        allPreviews.forEach(function (src) {
            html += '<div style="text-align:center;margin:8px 0;"><img src="' + src + '" style="max-width:100%;max-height:300px;object-fit:contain;"></div>';
        });
        if (keterangan) html += '<div style="text-align:center;font-style:italic;margin-top:4px;">' + keterangan + '</div>';
    }
    html += '</div>';

    document.getElementById('preview-content').innerHTML = html;
    document.getElementById('preview-modal').classList.remove('hidden');
};

window.closePreview = function () {
    document.getElementById('preview-modal').classList.add('hidden');
};

window.printPreview = function () {
    var content = document.getElementById('preview-content').innerHTML;
    var win = window.open('', '_blank', 'width=900,height=700');
    var css = [
        '@page{size:A4 portrait;margin:2cm 2.5cm}',
        '*{box-sizing:border-box}',
        'body{font-family:"Times New Roman",serif;font-size:12pt;line-height:1.6;color:#000;margin:0;padding:0}',
        'table{border-collapse:collapse}',
        'img{max-width:100%}',
        'p{margin-bottom:4px}',
        'ul,ol{padding-left:20px;margin-bottom:4px}',
        'li{margin-bottom:2px}',
        '.ql-align-center{text-align:center}',
        '.ql-align-right{text-align:right}',
        '.ql-align-justify{text-align:justify}',
        '.ql-indent-1{padding-left:2em}',
        '.ql-indent-2{padding-left:4em}',
    ].join('');
    win.document.write('<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Preview Laporan</title><style>' + css + '</style></head><body>' + content + '</body></html>');
    win.document.close();
    win.focus();
    setTimeout(function () { win.print(); }, 600);
};
