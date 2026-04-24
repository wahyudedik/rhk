/* Laporan Create/Edit JS — loaded after DOM */

// ── RHK Selector ─────────────────────────────────────────────────────────────
function initRhkSelector(rhksData, oldRhkId, oldJenisId) {
    var rhkSelect = document.getElementById('rhk-select');
    var jenisWrapper = document.getElementById('jenis-rhk-wrapper');
    var jenisSelect = document.getElementById('jenis-rhk-select');
    if (!rhkSelect) return;

    function loadJenis(rhkId) {
        jenisSelect.innerHTML = '<option value="">Pilih jenis RHK</option>';
        if (!rhkId) { jenisWrapper.classList.add('hidden'); return; }
        var rhk = rhksData.find(function (r) { return String(r.id) === String(rhkId); });
        if (rhk && rhk.jenis_rhks) {
            rhk.jenis_rhks.forEach(function (j) {
                var opt = document.createElement('option');
                opt.value = j.id;
                opt.textContent = j.nama;
                if (String(j.id) === String(oldJenisId)) opt.selected = true;
                jenisSelect.appendChild(opt);
            });
            jenisWrapper.classList.remove('hidden');
            if (rhk.jenis_rhks.length === 1) jenisSelect.value = rhk.jenis_rhks[0].id;
        }
    }

    rhkSelect.addEventListener('change', function () { loadJenis(this.value); });
    if (oldRhkId) { rhkSelect.value = oldRhkId; loadJenis(oldRhkId); }
}

// ── TTD Canvas ────────────────────────────────────────────────────────────────
var _ttdCanvas = null, _ttdCtx = null, _ttdDrawing = false;

function initTtdCanvas() {
    _ttdCanvas = document.getElementById('ttd-canvas');
    if (!_ttdCanvas) return;
    _ttdCtx = _ttdCanvas.getContext('2d');
    _ttdCtx.strokeStyle = '#1a1a1a';
    _ttdCtx.lineWidth = 2;
    _ttdCtx.lineCap = 'round';
    _ttdCtx.lineJoin = 'round';

    function getPos(e) {
        var rect = _ttdCanvas.getBoundingClientRect();
        var scaleX = _ttdCanvas.width / rect.width;
        var scaleY = _ttdCanvas.height / rect.height;
        var clientX = e.touches ? e.touches[0].clientX : e.clientX;
        var clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return { x: (clientX - rect.left) * scaleX, y: (clientY - rect.top) * scaleY };
    }

    _ttdCanvas.addEventListener('mousedown', function (e) {
        _ttdDrawing = true;
        var p = getPos(e);
        _ttdCtx.beginPath();
        _ttdCtx.moveTo(p.x, p.y);
    });
    _ttdCanvas.addEventListener('mousemove', function (e) {
        if (!_ttdDrawing) return;
        var p = getPos(e);
        _ttdCtx.lineTo(p.x, p.y);
        _ttdCtx.stroke();
    });
    _ttdCanvas.addEventListener('mouseup', function () { _ttdDrawing = false; syncTtdCanvas(); });
    _ttdCanvas.addEventListener('mouseleave', function () { _ttdDrawing = false; });
    _ttdCanvas.addEventListener('touchstart', function (e) {
        e.preventDefault();
        _ttdDrawing = true;
        var p = getPos(e);
        _ttdCtx.beginPath();
        _ttdCtx.moveTo(p.x, p.y);
    }, { passive: false });
    _ttdCanvas.addEventListener('touchmove', function (e) {
        e.preventDefault();
        if (!_ttdDrawing) return;
        var p = getPos(e);
        _ttdCtx.lineTo(p.x, p.y);
        _ttdCtx.stroke();
    }, { passive: false });
    _ttdCanvas.addEventListener('touchend', function () { _ttdDrawing = false; syncTtdCanvas(); });
}

function syncTtdCanvas() {
    var input = document.getElementById('ttd-canvas-data');
    if (input && _ttdCanvas) input.value = _ttdCanvas.toDataURL('image/png');
}

function setTtdMode(mode) {
    var drawArea = document.getElementById('ttd-draw-area');
    var uploadArea = document.getElementById('ttd-upload-area');
    var btnDraw = document.getElementById('btn-draw');
    var btnUpload = document.getElementById('btn-upload');
    if (mode === 'draw') {
        drawArea.classList.remove('hidden');
        uploadArea.classList.add('hidden');
        btnDraw.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition bg-blue-600 text-white';
        btnUpload.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition bg-gray-100 text-gray-700';
        initTtdCanvas();
    } else {
        drawArea.classList.add('hidden');
        uploadArea.classList.remove('hidden');
        btnDraw.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition bg-gray-100 text-gray-700';
        btnUpload.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition bg-blue-600 text-white';
    }
}

function clearTtdCanvas() {
    if (_ttdCtx && _ttdCanvas) _ttdCtx.clearRect(0, 0, _ttdCanvas.width, _ttdCanvas.height);
    var input = document.getElementById('ttd-canvas-data');
    if (input) input.value = '';
}

function previewTtdUpload(input) {
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
}

// ── Foto Manager ──────────────────────────────────────────────────────────────
var _fotoBatches = [];
var _fotoNextId = 0;

function getTotalFoto() {
    return _fotoBatches.reduce(function (s, b) { return s + b.count; }, 0);
}

function updateFotoCounter() {
    var el = document.getElementById('foto-counter');
    if (el) el.textContent = getTotalFoto() + '/10 foto';
    var btn = document.getElementById('btn-add-foto');
    if (btn) btn.style.display = getTotalFoto() >= 10 ? 'none' : '';
}

function addFotoBatch() {
    if (getTotalFoto() >= 10) return;
    var id = _fotoNextId++;
    _fotoBatches.push({ id: id, count: 0 });
    var container = document.getElementById('foto-inputs-container');
    var batchNum = _fotoBatches.length;
    var div = document.createElement('div');
    div.id = 'foto-batch-' + id;
    div.className = 'p-3 bg-gray-50 rounded-xl border border-gray-200';
    div.innerHTML =
        '<div class="flex items-center justify-between mb-2">' +
        '<span class="text-xs font-medium text-gray-600">Batch foto ' + batchNum + '</span>' +
        '<button type="button" onclick="removeFotoBatch(' + id + ')" class="text-xs text-red-500 hover:text-red-700">Hapus</button>' +
        '</div>' +
        '<input type="file" name="foto_dokumentasi[]" accept=".jpg,.jpeg,.png" multiple ' +
        'onchange="onFotoChange(this,' + id + ')" ' +
        'class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700">' +
        '<div id="foto-previews-' + id + '" class="flex flex-wrap gap-2 mt-2"></div>' +
        '<p id="foto-error-' + id + '" class="mt-1 text-xs text-red-500 hidden"></p>';
    container.appendChild(div);
    updateFotoCounter();
}

function removeFotoBatch(id) {
    var idx = _fotoBatches.findIndex(function (b) { return b.id === id; });
    if (idx > -1) _fotoBatches.splice(idx, 1);
    var el = document.getElementById('foto-batch-' + id);
    if (el) el.remove();
    updateFotoCounter();
}

function onFotoChange(input, id) {
    var files = Array.from(input.files);
    var batch = _fotoBatches.find(function (b) { return b.id === id; });
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
            img.className = 'w-16 h-16 object-cover rounded-lg border border-gray-200';
            previewEl.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

// ── Quill Editors ─────────────────────────────────────────────────────────────
var _quillInstances = {};
var _quillFields = ['latar_belakang', 'maksud_tujuan', 'ruang_lingkup', 'dasar', 'kegiatan_dilaksanakan', 'hasil_dicapai', 'simpulan', 'saran', 'penutup'];
var _toolbarOptions = [
    ['bold', 'italic', 'underline'],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    [{ 'align': [] }],
    ['clean']
];

function initQuillEditors() {
    _quillFields.forEach(function (field) {
        var editorEl = document.getElementById('editor-' + field);
        var hiddenEl = document.getElementById('hidden-' + field);
        if (!editorEl || !hiddenEl) return;
        var quill = new Quill(editorEl, {
            theme: 'snow',
            placeholder: 'Isi bagian ini...',
            modules: { toolbar: _toolbarOptions }
        });
        var init = hiddenEl.value.trim();
        if (init) {
            if (init.charAt(0) === '<') {
                quill.root.innerHTML = init;
            } else {
                quill.setText(init);
            }
        }
        _quillInstances[field] = quill;
    });
}

function syncQuillToHidden() {
    _quillFields.forEach(function (field) {
        var quill = _quillInstances[field];
        var hiddenEl = document.getElementById('hidden-' + field);
        if (!quill || !hiddenEl) return;
        var content = quill.root.innerHTML;
        hiddenEl.value = (content === '<p><br></p>') ? '' : content;
    });
}

function getQuillHtml(field) {
    var q = _quillInstances[field];
    if (!q) return '';
    var html = q.root.innerHTML;
    return (html === '<p><br></p>') ? '' : html;
}

// ── Preview ───────────────────────────────────────────────────────────────────
function getFormVal(name) {
    var el = document.querySelector('[name="' + name + '"]');
    return el ? el.value : '';
}

function openPreview() {
    var h1 = getFormVal('header_instansi_1') || 'KEMENTERIAN SOSIAL REPUBLIK INDONESIA';
    var h2 = getFormVal('header_instansi_2') || 'DIREKTORAT JENDERAL PERLINDUNGAN DAN JAMINAN SOSIAL';
    var h3 = getFormVal('header_instansi_3') || 'DIREKTORAT PERLINDUNGAN SOSIAL NON KEBENCANAAN';
    var h4 = getFormVal('header_instansi_4') || 'Jl. Salemba Raya No. 28 Jakarta Pusat Tlp (021)22804288';
    var bulan = getFormVal('bulan').toUpperCase();
    var tahun = getFormVal('tahun');
    var jenisSelect = document.querySelector('[name="jenis_rhk_id"]');
    var jenisNama = jenisSelect && jenisSelect.selectedIndex >= 0 ? jenisSelect.options[jenisSelect.selectedIndex].text : '';

    var html = '<div style="font-family:\'Times New Roman\',serif;font-size:12pt;line-height:1.6;color:#000;">';

    // Header
    html += '<table style="width:100%;border-bottom:3px solid #000;margin-bottom:16px;" cellpadding="0" cellspacing="0"><tr>';
    html += '<td style="width:80px;vertical-align:middle;"><img src="/logo.png" style="width:70px;height:auto;"></td>';
    html += '<td style="text-align:center;vertical-align:middle;">';
    html += '<div style="font-size:13pt;font-weight:bold;">' + h1 + '</div>';
    html += '<div style="font-size:11pt;font-weight:bold;">' + h2 + '</div>';
    html += '<div style="font-size:11pt;font-weight:bold;">' + h3 + '</div>';
    html += '<div style="font-size:9pt;">' + h4 + '</div>';
    html += '</td></tr></table>';

    // Judul
    html += '<div style="text-align:center;margin:16px 0;">';
    html += '<div style="font-weight:bold;">LAPORAN</div>';
    html += '<div style="font-weight:bold;">SASARAN KINERJA PEGAWAI (SKP)</div>';
    html += '<div style="font-weight:bold;text-transform:uppercase;">' + jenisNama + '</div>';
    html += '<div style="font-weight:bold;">BULAN ' + bulan + ' TAHUN ' + tahun + '</div>';
    html += '</div>';

    // Isi
    var sections = [
        {
            label: 'A. PENDAHULUAN', sub: [
                { title: '1. Umum', field: 'latar_belakang' },
                { title: '2. Maksud dan Tujuan', field: 'maksud_tujuan' },
                { title: '3. Ruang Lingkup', field: 'ruang_lingkup' },
                { title: '4. Dasar', field: 'dasar' },
            ]
        },
        { label: 'B. KEGIATAN YANG DILAKSANAKAN', field: 'kegiatan_dilaksanakan' },
        { label: 'C. HASIL YANG DICAPAI', field: 'hasil_dicapai' },
        {
            label: 'D. SIMPULAN DAN SARAN', sub: [
                { title: '1. Simpulan', field: 'simpulan' },
                { title: '2. Saran', field: 'saran' },
            ]
        },
        { label: 'E. PENUTUP', field: 'penutup' },
    ];

    sections.forEach(function (sec) {
        html += '<div style="margin-top:12px;"><strong>' + sec.label + '</strong></div>';
        if (sec.sub) {
            sec.sub.forEach(function (s) {
                var content = getQuillHtml(s.field);
                if (content) {
                    html += '<div style="margin-top:6px;"><strong>' + s.title + '</strong></div>';
                    html += '<div style="margin-left:16px;text-align:justify;">' + content + '</div>';
                }
            });
        } else if (sec.field) {
            var content = getQuillHtml(sec.field);
            if (content) html += '<div style="margin-top:4px;text-align:justify;">' + content + '</div>';
        }
    });

    // TTD
    var kota = getFormVal('ttd_kota');
    var tgl = getFormVal('ttd_tanggal');
    var jabatan = getFormVal('ttd_jabatan');
    var nama = getFormVal('ttd_nama');
    var nip = getFormVal('ttd_nip');
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

    var ttdSrc = '';
    if (canvasData && canvasData.length > 50 && canvasData !== 'data:image/png;base64,') {
        ttdSrc = canvasData;
    } else if (ttdUploadImg && ttdUploadImg.src && ttdUploadImg.src.startsWith('data:')) {
        ttdSrc = ttdUploadImg.src;
    }

    if (ttdSrc) {
        html += '<div style="margin:10px 0;"><img src="' + ttdSrc + '" style="height:80px;"></div>';
    } else {
        html += '<div style="height:80px;"></div>';
    }

    html += '<div><strong><u>' + (nama || '...') + '</u></strong></div>';
    html += '<div>NIP. ' + (nip || '...') + '</div>';
    html += '</td></tr></table>';

    // Dokumentasi
    var allPreviews = [];
    document.querySelectorAll('#foto-inputs-container img').forEach(function (img) {
        if (img.src && img.src.startsWith('data:')) allPreviews.push(img.src);
    });
    var keterangan = getFormVal('keterangan_dokumentasi');
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
}

function closePreview() {
    document.getElementById('preview-modal').classList.add('hidden');
}

function printPreview() {
    var previewEl = document.getElementById('preview-content');
    if (!previewEl) return;

    // Encode content sebagai base64 untuk menghindari masalah </script>
    var content = previewEl.innerHTML;
    var encoded = btoa(unescape(encodeURIComponent(content)));

    var win = window.open('', '_blank', 'width=900,height=700');
    win.document.write(
        '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Preview Laporan</title>' +
        '<style>@page{size:A4 portrait;margin:2cm 2.5cm}' +
        '*{box-sizing:border-box}' +
        'body{font-family:"Times New Roman",serif;font-size:12pt;line-height:1.6;color:#000;margin:0;padding:0}' +
        'table{border-collapse:collapse}img{max-width:100%}' +
        'p{margin-bottom:4px}ul,ol{padding-left:20px;margin-bottom:4px}li{margin-bottom:2px}' +
        '.ql-align-center{text-align:center}.ql-align-right{text-align:right}' +
        '.ql-align-justify{text-align:justify}' +
        '.ql-indent-1{padding-left:2em}.ql-indent-2{padding-left:4em}' +
        '</style></head>' +
        '<body><div id="c"></div>' +
        '<script>document.getElementById("c").innerHTML=decodeURIComponent(escape(atob("' + encoded + '")));' +
        'setTimeout(function(){window.print();},600);<\/script>' +
        '</body></html>'
    );
    win.document.close();
}

// ── Form Submit ───────────────────────────────────────────────────────────────
function initFormSubmit() {
    var form = document.getElementById('form-laporan');
    if (!form) return;
    form.addEventListener('submit', function () {
        syncQuillToHidden();
        syncTtdCanvas();
    });
}

// ── Init ──────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    initTtdCanvas();
    addFotoBatch();
    initFormSubmit();
});
