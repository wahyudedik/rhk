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
        $laporan->load(['rhk', 'jenisRhk', 'user']);

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
        $laporan->load(['rhk', 'jenisRhk', 'user']);

        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop' => 1134,    // ~2cm
            'marginBottom' => 1134,
            'marginLeft' => 1418,   // ~2.5cm
            'marginRight' => 1418,
        ]);

        // Style
        $boldStyle = ['bold' => true];
        $centerPara = ['alignment' => Jc::CENTER];
        $justifyPara = ['alignment' => Jc::BOTH];

        // Header instansi dengan logo
        $h1 = $laporan->header_instansi_1 ?: 'KEMENTERIAN SOSIAL REPUBLIK INDONESIA';
        $h2 = $laporan->header_instansi_2 ?: 'DIREKTORAT JENDERAL PERLINDUNGAN DAN JAMINAN SOSIAL';
        $h3 = $laporan->header_instansi_3 ?: 'DIREKTORAT PERLINDUNGAN SOSIAL NON KEBENCANAAN';
        $h4 = $laporan->header_instansi_4 ?: 'Jl. Salemba Raya No. 28 Jakarta Pusat Tlp (021)22804288';

        // Buat tabel header dengan logo
        $logoAbsPath = public_path('logo-kemensos.png');
        $headerTable = $section->addTable(['borderSize' => 0, 'borderColor' => 'ffffff', 'cellMargin' => 0]);
        $headerTable->addRow();

        // Kolom logo
        $logoCell = $headerTable->addCell(1500, ['borderSize' => 0, 'borderColor' => 'ffffff', 'valign' => 'center']);
        if (file_exists($logoAbsPath)) {
            // Konversi backslash ke forward slash untuk PhpWord di Windows
            $logoPathNormalized = str_replace('\\', '/', $logoAbsPath);
            $logoCell->addImage($logoPathNormalized, ['width' => 60, 'height' => 60, 'alignment' => Jc::CENTER]);
        }

        // Kolom teks header
        $textCell = $headerTable->addCell(7500, ['borderSize' => 0, 'borderColor' => 'ffffff', 'valign' => 'center']);
        $textCell->addText($h1, ['bold' => true, 'size' => 13], $centerPara);
        $textCell->addText($h2, $boldStyle, $centerPara);
        $textCell->addText($h3, $boldStyle, $centerPara);
        $textCell->addText($h4, ['size' => 9], $centerPara);

        $section->addLine(['weight' => 3, 'width' => 9000, 'height' => 0, 'color' => '000000']);
        $section->addTextBreak(1);

        // Judul
        $section->addText('LAPORAN', $boldStyle, $centerPara);
        $section->addText('SASARAN KINERJA PEGAWAI (SKP)', $boldStyle, $centerPara);
        $section->addText(strtoupper($laporan->jenisRhk->nama), $boldStyle, $centerPara);
        $section->addText('BULAN '.strtoupper($laporan->bulan).' TAHUN '.$laporan->tahun, $boldStyle, $centerPara);
        $section->addTextBreak(1);

        // Isi laporan
        $this->addSection($section, 'A. PENDAHULUAN', null, $boldStyle);
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
            $this->addSection($section, 'D. SIMPULAN DAN SARAN', null, $boldStyle);
            $this->addHtmlContent($section, '1. Simpulan', $laporan->simpulan, $boldStyle, $justifyPara);
            $this->addHtmlContent($section, '2. Saran', $laporan->saran, $boldStyle, $justifyPara);
        }
        if ($laporan->penutup) {
            $this->addSection($section, 'E. PENUTUP', $laporan->penutup, $boldStyle, $justifyPara);
        }

        // TTD
        if ($laporan->ttd_nama || $laporan->ttd_kota) {
            $section->addTextBreak(2);
            $rightPara = ['alignment' => Jc::RIGHT];
            if ($laporan->ttd_kota) {
                $section->addText('Dibuat di '.$laporan->ttd_kota.',', [], $rightPara);
            }
            if ($laporan->ttd_tanggal) {
                $section->addText('Pada Tanggal '.$laporan->ttd_tanggal->translatedFormat('d F Y'), [], $rightPara);
            }
            if ($laporan->ttd_jabatan) {
                $section->addText($laporan->ttd_jabatan, [], $rightPara);
            }

            // Gambar TTD
            if ($laporan->ttd_gambar) {
                $ttdAbsPath = $this->resolveStoragePath($laporan->ttd_gambar);
                if ($ttdAbsPath && file_exists($ttdAbsPath)) {
                    // Konversi backslash ke forward slash untuk PhpWord di Windows
                    $ttdPathNormalized = str_replace('\\', '/', $ttdAbsPath);
                    $section->addImage($ttdPathNormalized, ['width' => 120, 'height' => 60, 'alignment' => Jc::RIGHT]);
                }
            } else {
                $section->addTextBreak(3);
            }

            if ($laporan->ttd_nama) {
                $section->addText($laporan->ttd_nama, ['bold' => true, 'underline' => Font::UNDERLINE_SINGLE], $rightPara);
            }
            if ($laporan->ttd_nip) {
                $section->addText('NIP. '.$laporan->ttd_nip, [], $rightPara);
            }
        }

        // Dokumentasi foto
        if ($laporan->foto_dokumentasi && count($laporan->foto_dokumentasi) > 0) {
            $section->addPageBreak();
            $section->addText('DOKUMENTASI', $boldStyle, $centerPara);
            $section->addTextBreak(1);

            foreach ($laporan->foto_dokumentasi as $foto) {
                $fotoAbsPath = $this->resolveStoragePath($foto);
                if ($fotoAbsPath && file_exists($fotoAbsPath)) {
                    // Konversi backslash ke forward slash untuk PhpWord di Windows
                    $fotoPathNormalized = str_replace('\\', '/', $fotoAbsPath);
                    $section->addImage($fotoPathNormalized, ['width' => 400, 'height' => 280, 'alignment' => Jc::CENTER]);
                    $section->addTextBreak(1);
                }
            }

            if ($laporan->keterangan_dokumentasi) {
                $section->addText($laporan->keterangan_dokumentasi, ['italic' => true], $centerPara);
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
        $section->addText($title, $boldStyle);
        if ($htmlContent) {
            $this->addHtmlContent($section, null, $htmlContent, [], $parStyle);
        }
    }

    private function addHtmlContent(Section $section, ?string $subTitle, ?string $htmlContent, array $boldStyle, array $parStyle = []): void
    {
        if (! $htmlContent) {
            return;
        }
        if ($subTitle) {
            $section->addText($subTitle, $boldStyle);
        }
        // Strip HTML tags untuk DOCX (PhpWord HTML parser terbatas)
        $plain = strip_tags(str_replace(['</p>', '<br>', '<br/>', '<br />', '</li>'], "\n", $htmlContent));
        $plain = html_entity_decode($plain, ENT_QUOTES, 'UTF-8');
        $lines = array_filter(array_map('trim', explode("\n", $plain)));
        foreach ($lines as $line) {
            $section->addText($line, [], $parStyle);
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
