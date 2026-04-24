<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Services\LaporanExportService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanDownloadController extends Controller
{
    public function __construct(private readonly LaporanExportService $exportService) {}

    public function downloadPdf(Laporan $laporan): StreamedResponse|Response
    {
        $this->authorize('view', $laporan);

        // Regenerate jika belum ada atau file hilang
        if (! $laporan->file_pdf || ! Storage::disk('public')->exists($laporan->file_pdf)) {
            $path = $this->exportService->generatePdf($laporan);
            $laporan->update(['file_pdf' => $path]);
        }

        $filename = $this->buildFilename($laporan, 'pdf');

        return Storage::disk('public')->download($laporan->file_pdf, $filename);
    }

    public function downloadDocx(Laporan $laporan): StreamedResponse|Response
    {
        $this->authorize('view', $laporan);

        if (! $laporan->file_docx || ! Storage::disk('public')->exists($laporan->file_docx)) {
            $path = $this->exportService->generateDocx($laporan);
            $laporan->update(['file_docx' => $path]);
        }

        $filename = $this->buildFilename($laporan, 'docx');

        return Storage::disk('public')->download($laporan->file_docx, $filename);
    }

    private function buildFilename(Laporan $laporan, string $ext): string
    {
        $jenis = Str::slug($laporan->jenisRhk->nama ?? 'laporan', '_');
        $jenis = substr($jenis, 0, 40);

        return "Laporan_{$laporan->bulan}_{$laporan->tahun}_{$jenis}.{$ext}";
    }
}
