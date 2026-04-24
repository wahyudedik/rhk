<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.6; color: #000; }
        .page { padding: 2cm 2.5cm; }

        /* Header */
        .header-table { width: 100%; border-bottom: 2px solid #000; margin-bottom: 16px; padding-bottom: 8px; }
        .header-logo { width: 120px; vertical-align: middle; }
        .header-text { text-align: center; vertical-align: middle; }
        .header-text .line1 { font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header-text .line2, .header-text .line3 { font-size: 11pt; font-weight: bold; text-transform: uppercase; }
        .header-text .line4 { font-size: 9pt; }

        /* Judul */
        .judul { text-align: center; margin: 20px 0; }
        .judul div { font-weight: bold; font-size: 12pt; text-transform: uppercase; }

        /* Konten */
        .section-title { font-weight: bold; margin-top: 14px; margin-bottom: 4px; text-transform: uppercase; }
        .sub-title { font-weight: bold; margin-top: 8px; margin-bottom: 2px; }
        .content { text-align: justify; margin-bottom: 6px; line-height: 1.6; }
        .content p { margin-bottom: 4px; }
        .content ul { padding-left: 20px; margin-bottom: 4px; }
        .content ol { padding-left: 20px; margin-bottom: 4px; }
        .content li { margin-bottom: 2px; }
        .content strong { font-weight: bold; }
        .content em { font-style: italic; }
        .content u { text-decoration: underline; }

        /* TTD */
        .ttd-block { margin-top: 30px; text-align: right; padding-right: 40px; }
        .ttd-block .ttd-img { height: 80px; margin: 10px 0; }
        .ttd-block .ttd-nama { font-weight: bold; text-decoration: underline; }

        /* Dokumentasi */
        .dokumentasi-title { text-align: center; font-weight: bold; margin-top: 30px; margin-bottom: 12px; font-size: 12pt; }
        .foto-grid { width: 100%; }
        .foto-grid td { text-align: center; padding: 6px; vertical-align: top; }
        .foto-grid img { max-width: 350px; max-height: 280px; }
        .foto-keterangan { text-align: center; font-style: italic; margin-top: 8px; font-size: 10pt; }

        /* Page break */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header Instansi --}}
    @php
        $h1 = $laporan->header_instansi_1 ?: 'KEMENTERIAN SOSIAL REPUBLIK INDONESIA';
        $h2 = $laporan->header_instansi_2 ?: 'DIREKTORAT JENDERAL PERLINDUNGAN DAN JAMINAN SOSIAL';
        $h3 = $laporan->header_instansi_3 ?: 'DIREKTORAT PERLINDUNGAN SOSIAL NON KEBENCANAAN';
        $h4 = $laporan->header_instansi_4 ?: 'Jl. Salemba Raya No. 28 Jakarta Pusat Tlp (021)22804288 http://kemensos.go.id/';
        $logoPath = public_path('logo-kemensos.png');
        // Embed logo sebagai base64 agar dompdf tidak perlu load file eksternal
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            @if ($logoBase64)
                <td class="header-logo" style="width:120px;">
                    <img src="{{ $logoBase64 }}" style="width:100px; height:auto;">
                </td>
            @endif
            <td class="header-text">
                <div class="line1">{{ $h1 }}</div>
                <div class="line2">{{ $h2 }}</div>
                <div class="line3">{{ $h3 }}</div>
                <div class="line4">{{ $h4 }}</div>
            </td>
        </tr>
    </table>

    {{-- Judul --}}
    <div class="judul">
        <div>LAPORAN</div>
        <div>SASARAN KINERJA PEGAWAI (SKP)</div>
        <div>{{ $laporan->jenisRhk->nama }}</div>
        <div>BULAN {{ strtoupper($laporan->bulan) }} TAHUN {{ $laporan->tahun }}</div>
    </div>

    {{-- A. Pendahuluan --}}
    <div class="section-title">A. PENDAHULUAN</div>

    @if ($laporan->latar_belakang)
        <div class="sub-title">1. Umum</div>
        <div class="content">{!! $laporan->latar_belakang !!}</div>
    @endif

    @if ($laporan->maksud_tujuan)
        <div class="sub-title">2. Maksud dan Tujuan</div>
        <div class="content">{!! $laporan->maksud_tujuan !!}</div>
    @endif

    @if ($laporan->ruang_lingkup)
        <div class="sub-title">3. Ruang Lingkup</div>
        <div class="content">{!! $laporan->ruang_lingkup !!}</div>
    @endif

    @if ($laporan->dasar)
        <div class="sub-title">4. Dasar</div>
        <div class="content">{!! $laporan->dasar !!}</div>
    @endif

    {{-- B. Kegiatan --}}
    @if ($laporan->kegiatan_dilaksanakan)
        <div class="section-title">B. KEGIATAN YANG DILAKSANAKAN</div>
        <div class="content">{!! $laporan->kegiatan_dilaksanakan !!}</div>
    @endif

    {{-- C. Hasil --}}
    @if ($laporan->hasil_dicapai)
        <div class="section-title">C. HASIL YANG DICAPAI</div>
        <div class="content">{!! $laporan->hasil_dicapai !!}</div>
    @endif

    {{-- D. Simpulan & Saran --}}
    @if ($laporan->simpulan || $laporan->saran)
        <div class="section-title">D. SIMPULAN DAN SARAN</div>
        @if ($laporan->simpulan)
            <div class="sub-title">1. Simpulan</div>
            <div class="content">{!! $laporan->simpulan !!}</div>
        @endif
        @if ($laporan->saran)
            <div class="sub-title">2. Saran</div>
            <div class="content">{!! $laporan->saran !!}</div>
        @endif
    @endif

    {{-- E. Penutup --}}
    @if ($laporan->penutup)
        <div class="section-title">E. PENUTUP</div>
        <div class="content">{!! $laporan->penutup !!}</div>
    @endif

    {{-- Tanda Tangan --}}
    @if ($laporan->ttd_nama || $laporan->ttd_kota)
        <div style="margin-top:30px; margin-left:60%; text-align:left;">
            @if ($laporan->ttd_kota || $laporan->ttd_tanggal)
                <div>Dibuat di {{ $laporan->ttd_kota }},</div>
                <div>Pada Tanggal {{ $laporan->ttd_tanggal?->translatedFormat('d F Y') }}</div>
            @endif
            @if ($laporan->ttd_jabatan)
                <div>{{ $laporan->ttd_jabatan }}</div>
            @endif
            @if ($laporan->ttd_gambar && $ttdBase64)
                <div><img src="{{ $ttdBase64 }}" style="height:80px; margin:8px 0;"></div>
            @else
                <div style="height:80px;"></div>
            @endif
            @if ($laporan->ttd_nama)
                <div class="ttd-nama">{{ $laporan->ttd_nama }}</div>
            @endif
            @if ($laporan->ttd_nip)
                <div>NIP. {{ $laporan->ttd_nip }}</div>
            @endif
        </div>
    @endif

    {{-- Dokumentasi Foto --}}
    @if ($laporan->foto_dokumentasi && count($laporan->foto_dokumentasi) > 0)
        <div class="page-break"></div>
        <div class="dokumentasi-title">DOKUMENTASI</div>
        @php $fotos = $laporan->foto_dokumentasi; $chunks = array_chunk($fotos, 2); @endphp
        <table class="foto-grid" cellpadding="0" cellspacing="0">
            @foreach ($chunks as $row)
                <tr>
                    @foreach ($row as $foto)
                        <td>
                            @if (isset($fotoBase64[$foto]))
                                <img src="{{ $fotoBase64[$foto] }}">
                            @endif
                        </td>
                    @endforeach
                    @if (count($row) === 1)
                        <td></td>
                    @endif
                </tr>
            @endforeach
        </table>
        @if ($laporan->keterangan_dokumentasi)
            <div class="foto-keterangan">{{ $laporan->keterangan_dokumentasi }}</div>
        @endif
    @endif

</div>
</body>
</html>
