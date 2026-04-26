<?php

namespace App\Services;

use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;

class LaporanExportService
{
    /**
     * Generate PDF, simpan ke storage, return path.
     */
    public function generatePdf(Laporan $laporan): string
    {
        $laporan->load(['rhk', 'jenisRhk', 'user', 'gpsPhoto']);

        // Konversi semua gambar ke base64 untuk dompdf
        $logoBase64 = null;
        $logoRawPath = $this->resolvePublicPath('logo-kemensos.png');
        if ($logoRawPath && file_exists($logoRawPath)) {
            $logoBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoRawPath));
        }

        $ttdBase64 = null;
        if ($laporan->ttd_gambar) {
            $ttdRawPath = $this->resolveStoragePath($laporan->ttd_gambar);
            if ($ttdRawPath && file_exists($ttdRawPath)) {
                $extension = pathinfo($ttdRawPath, PATHINFO_EXTENSION);
                $mimeType = $this->getMimeType($extension);
                $ttdBase64 = 'data:'.$mimeType.';base64,'.base64_encode(file_get_contents($ttdRawPath));
            }
        }

        $fotoBase64 = [];
        foreach ($laporan->foto_dokumentasi ?? [] as $foto) {
            $fotoRawPath = $this->resolveStoragePath($foto);
            if ($fotoRawPath && file_exists($fotoRawPath)) {
                $extension = pathinfo($fotoRawPath, PATHINFO_EXTENSION);
                $mimeType = $this->getMimeType($extension);
                $fotoBase64[$foto] = 'data:'.$mimeType.';base64,'.base64_encode(file_get_contents($fotoRawPath));
            }
        }

        $pdf = Pdf::loadView('laporan.exports.pdf', compact('laporan', 'logoBase64', 'ttdBase64', 'fotoBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Times New Roman',
                'dpi' => 150,
            ]);

        $filename = 'laporan-pdf/'.$laporan->id.'_'.now()->format('YmdHis').'.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }

    /**
     * Generate DOCX, simpan ke storage, return path.
     */
    public function generateDocx(Laporan $laporan): string
    {
        $laporan->load(['rhk', 'jenisRhk', 'user', 'gpsPhoto']);

        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop' => 1134,    // ~2cm
            'marginBottom' => 1134,
            'marginLeft' => 1418,   // ~2.5cm
            'marginRight' => 1418,
        ]);

        // Style definitions
        $boldStyle = ['bold' => true];
        $centerPara = ['alignment' => Jc::CENTER, 'spaceAfter' => 0];
        $justifyPara = ['alignment' => Jc::BOTH, 'spaceAfter' => 120];
        $leftPara = ['alignment' => Jc::LEFT, 'spaceAfter' => 120];

        // Header instansi dengan logo
        $h1 = $laporan->header_instansi_1 ?: 'KEMENTERIAN SOSIAL REPUBLIK INDONESIA';
        $h2 = $laporan->header_instansi_2 ?: 'DIREKTORAT JENDERAL PERLINDUNGAN DAN JAMINAN SOSIAL';
        $h3 = $laporan->header_instansi_3 ?: 'DIREKTORAT PERLINDUNGAN SOSIAL NON KEBENCANAAN';
        $h4 = $laporan->header_instansi_4 ?: 'Jl. Salemba Raya No. 28 Jakarta Pusat Tlp (021)22804288';

        // Buat tabel header dengan logo
        $logoAbsPath = public_path('logo-kemensos.png');
        $headerTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'ffffff',
            'cellMargin' => 0,
            'width' => 100 * 50,
            'unit' => 'pct',
        ]);
        $headerTable->addRow();

        // Kolom logo
        $logoCell = $headerTable->addCell(1800, ['borderSize' => 0, 'borderColor' => 'ffffff', 'valign' => 'center']);
        if (file_exists($logoAbsPath)) {
            $logoPathNormalized = str_replace('\\', '/', $logoAbsPath);
            $logoCell->addImage($logoPathNormalized, ['width' => 80, 'height' => 80, 'alignment' => Jc::CENTER]);
        }

        // Kolom teks header
        $textCell = $headerTable->addCell(7200, ['borderSize' => 0, 'borderColor' => 'ffffff', 'valign' => 'center']);
        $textCell->addText(strtoupper($h1), ['bold' => true, 'size' => 12], $centerPara);
        $textCell->addText(strtoupper($h2), ['bold' => true, 'size' => 11], $centerPara);
        $textCell->addText(strtoupper($h3), ['bold' => true, 'size' => 11], $centerPara);
        $textCell->addText($h4, ['size' => 9], $centerPara);

        // Garis pemisah dengan margin yang cukup dari tepi
        $section->addLine([
            'weight' => 2,
            'width' => 450,
            'height' => 0,
            'color' => '000000',
        ]);
        $section->addTextBreak(1);

        // Judul - semuanya BOLD dan CENTER
        $section->addText('LAPORAN', ['bold' => true, 'size' => 12], $centerPara);
        $section->addText('SASARAN KINERJA PEGAWAI (SKP)', ['bold' => true, 'size' => 12], $centerPara);
        $section->addText(strtoupper($laporan->jenisRhk->nama), ['bold' => true, 'size' => 12], $centerPara);
        $section->addText('BULAN '.strtoupper($laporan->bulan).' TAHUN '.$laporan->tahun, ['bold' => true, 'size' => 12], $centerPara);
        $section->addTextBreak(1);

        // Isi laporan
        $this->addSection($section, 'A. PENDAHULUAN', null, $boldStyle, $leftPara);
        $this->addHtmlContent($section, '1. Umum', $laporan->latar_belakang, $boldStyle, $justifyPara);
        $this->addHtmlContent($section, '2. Maksud dan Tujuan', $laporan->maksud_tujuan, $boldStyle, $justifyPara);
        $this->addHtmlContent($section, '3. Ruang Lingkup', $laporan->ruang_lingkup, $boldStyle, $justifyPara);
        $this->addHtmlContent($section, '4. Dasar', $laporan->dasar, $boldStyle, $justifyPara);

        if ($laporan->kegiatan_dilaksanakan) {
            $this->addSection($section, 'B. KEGIATAN YANG DILAKSANAKAN', $laporan->kegiatan_dilaksanakan, $boldStyle, $justifyPara);
        }
        if ($laporan->hasil_dicapai) {
            $this->addSection($section, 'C. HASIL YANG DICAPAI', $laporan->hasil_dicapai, $boldStyle, $justifyPara);
        }
        if ($laporan->simpulan || $laporan->saran) {
            $this->addSection($section, 'D. SIMPULAN DAN SARAN', null, $boldStyle, $leftPara);
            $this->addHtmlContent($section, '1. Simpulan', $laporan->simpulan, $boldStyle, $justifyPara);
            $this->addHtmlContent($section, '2. Saran', $laporan->saran, $boldStyle, $justifyPara);
        }
        if ($laporan->penutup) {
            $this->addSection($section, 'E. PENUTUP', $laporan->penutup, $boldStyle, $justifyPara);
        }

        // TTD - Tanpa tabel, langsung align right
        if ($laporan->ttd_nama || $laporan->ttd_kota) {
            $section->addTextBreak(2);
            $rightPara = ['alignment' => Jc::RIGHT, 'spaceAfter' => 0];

            if ($laporan->ttd_kota) {
                $section->addText('Dibuat di '.$laporan->ttd_kota.',', ['size' => 12], $rightPara);
            }
            if ($laporan->ttd_tanggal) {
                $section->addText('Pada Tanggal '.$laporan->ttd_tanggal->translatedFormat('d F Y'), ['size' => 12], $rightPara);
            }
            if ($laporan->ttd_jabatan) {
                $section->addText($laporan->ttd_jabatan, ['size' => 12], $rightPara);
            }

            // Gambar TTD
            if ($laporan->ttd_gambar) {
                $ttdAbsPath = $this->resolveStoragePath($laporan->ttd_gambar);
                if ($ttdAbsPath && file_exists($ttdAbsPath)) {
                    $ttdPathNormalized = str_replace('\\', '/', $ttdAbsPath);
                    $section->addImage($ttdPathNormalized, ['width' => 120, 'height' => 60, 'alignment' => Jc::RIGHT]);
                }
            } else {
                $section->addTextBreak(3);
            }

            if ($laporan->ttd_nama) {
                $section->addText($laporan->ttd_nama, ['bold' => true, 'underline' => Font::UNDERLINE_SINGLE, 'size' => 12], $rightPara);
            }
            if ($laporan->ttd_nip) {
                $section->addText('NIP. '.$laporan->ttd_nip, ['size' => 12], $rightPara);
            }
        }

        // Dokumentasi foto
        if (($laporan->foto_dokumentasi && count($laporan->foto_dokumentasi) > 0) || $laporan->gpsPhoto) {
            $section->addPageBreak();
            $section->addText('DOKUMENTASI', ['bold' => true, 'size' => 12], $centerPara);
            $section->addTextBreak(1);

            // GPS Photo jika ada
            if ($laporan->gpsPhoto) {
                $gpsPhotoAbsPath = $this->resolveStoragePath('gps-photos/'.$laporan->gpsPhoto->filename);
                if ($gpsPhotoAbsPath && file_exists($gpsPhotoAbsPath)) {
                    $gpsPhotoPathNormalized = str_replace('\\', '/', $gpsPhotoAbsPath);
                    $section->addText('Foto GPS', ['bold' => true, 'size' => 11], $centerPara);
                    $section->addImage($gpsPhotoPathNormalized, ['width' => 400, 'height' => 300, 'alignment' => Jc::CENTER]);
                    $section->addText('📍 '.$laporan->gpsPhoto->address, ['italic' => true, 'size' => 9], $centerPara);
                    $section->addText('📅 '.$laporan->gpsPhoto->photo_datetime->translatedFormat('d F Y H:i'), ['italic' => true, 'size' => 9], $centerPara);
                    $section->addTextBreak(2);
                }
            }

            // Foto dokumentasi lainnya - 2 per row
            if ($laporan->foto_dokumentasi && count($laporan->foto_dokumentasi) > 0) {
                $section->addText('Foto Dokumentasi', ['bold' => true, 'size' => 11], $centerPara);
                $section->addTextBreak(1);
                
                $fotos = $laporan->foto_dokumentasi;
                $chunks = array_chunk($fotos, 2);
                
                foreach ($chunks as $row) {
                    $table = $section->addTable(['borderSize' => 0, 'borderColor' => 'ffffff', 'cellMargin' => 100, 'width' => 100 * 50, 'unit' => 'pct']);
                    $table->addRow();
                    
                    foreach ($row as $foto) {
                        $fotoAbsPath = $this->resolveStoragePath($foto);
                        if ($fotoAbsPath && file_exists($fotoAbsPath)) {
                            $fotoPathNormalized = str_replace('\\', '/', $fotoAbsPath);
                            $cell = $table->addCell(5000, ['borderSize' => 0, 'borderColor' => 'ffffff']);
                            $cell->addImage($fotoPathNormalized, ['width' => 350, 'height' => 280, 'alignment' => Jc::CENTER]);
                        }
                    }
                    
                    // Add empty cell if odd number of photos
                    if (count($row) === 1) {
                        $table->addCell(5000, ['borderSize' => 0, 'borderColor' => 'ffffff']);
                    }
                }
                
                $section->addTextBreak(1);
            }

            if ($laporan->keterangan_dokumentasi) {
                $section->addText($laporan->keterangan_dokumentasi, ['italic' => true, 'size' => 10], $centerPara);
            }
        }

        // Simpan ke storage
        $filename = 'laporan-docx/'.$laporan->id.'_'.now()->format('YmdHis').'.docx';
        $tmpPath = sys_get_temp_dir().'/'.basename($filename);

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tmpPath);

        Storage::disk('public')->put($filename, file_get_contents($tmpPath));
        @unlink($tmpPath);

        return $filename;
    }

    private function addSection(Section $section, string $title, ?string $htmlContent, array $boldStyle, array $parStyle = []): void
    {
        $section->addText($title, $boldStyle, $parStyle);
        if ($htmlContent) {
            $this->addHtmlContent($section, null, $htmlContent, [], ['alignment' => Jc::BOTH, 'spaceAfter' => 120]);
        }
    }

    private function addHtmlContent(Section $section, ?string $subTitle, ?string $htmlContent, array $boldStyle, array $parStyle = []): void
    {
        if (! $htmlContent) {
            return;
        }
        if ($subTitle) {
            $section->addText($subTitle, array_merge($boldStyle, ['size' => 12]), ['alignment' => Jc::LEFT, 'spaceAfter' => 120]);
        }
        // Strip HTML tags untuk DOCX (PhpWord HTML parser terbatas)
        $plain = strip_tags(str_replace(['</p>', '<br>', '<br/>', '<br />', '</li>'], "\n", $htmlContent));
        $plain = html_entity_decode($plain, ENT_QUOTES, 'UTF-8');
        $lines = array_filter(array_map('trim', explode("\n", $plain)));
        foreach ($lines as $line) {
            $section->addText($line, ['size' => 12], $parStyle);
        }
    }

    private function resolveStoragePath(string $path): ?string
    {
        $abs = storage_path('app/public/'.$path);

        return file_exists($abs) ? $abs : null;
    }

    private function resolvePublicPath(string $filename): ?string
    {
        $abs = public_path($filename);

        return file_exists($abs) ? $abs : null;
    }

    private function getMimeType(string $extension): string
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'image/jpeg';
    }
}
