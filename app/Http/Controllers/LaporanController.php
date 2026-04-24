<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Http\Requests\UpdateLaporanRequest;
use App\Models\Laporan;
use App\Models\Rhk;
use App\Services\LaporanExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function __construct(private readonly LaporanExportService $exportService) {}

    public function index(Request $request): View
    {
        $query = Laporan::with(['rhk', 'jenisRhk'])
            ->where('user_id', $request->user()->id);

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('rhk_id')) {
            $query->where('rhk_id', $request->rhk_id);
        }

        $laporans = $query
            ->orderByDesc('tahun')
            ->orderByRaw("FIELD(bulan, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember') DESC")
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $tahunList = Laporan::where('user_id', $request->user()->id)
            ->distinct()->orderByDesc('tahun')->pluck('tahun');

        $rhkList = Rhk::orderBy('urutan')->get(['id', 'nama', 'urutan']);
        $totalLaporan = Laporan::where('user_id', $request->user()->id)->count();

        return view('laporan.index', compact('laporans', 'tahunList', 'rhkList', 'totalLaporan'));
    }

    public function create(): View
    {
        $this->authorize('create', Laporan::class);

        $rhks = Rhk::with('jenisRhks')->orderBy('urutan')->get();
        $user = auth()->user();

        return view('laporan.create', compact('rhks', 'user'));
    }

    public function store(StoreLaporanRequest $request): RedirectResponse
    {
        $this->authorize('create', Laporan::class);

        $sub = $request->user()->activeSubscription();
        if (! $sub || ! $sub->bisaBuatLaporan()) {
            return redirect()->route('subscription.expired')
                ->with('warning', 'Kuota laporan Anda sudah habis atau langganan tidak aktif.');
        }

        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        // File dokumen utama
        if ($request->hasFile('file_dokumen')) {
            $validated['file_dokumen'] = $request->file('file_dokumen')->store('laporan-dokumen', 'public');
        }

        // TTD — canvas base64 lebih prioritas dari upload file, fallback ke profil user
        if (! empty($validated['ttd_gambar_canvas'])) {
            $validated['ttd_gambar'] = $this->saveBase64Image($validated['ttd_gambar_canvas'], 'ttd');
        } elseif ($request->hasFile('ttd_gambar')) {
            $validated['ttd_gambar'] = $request->file('ttd_gambar')->store('ttd', 'public');
        } elseif ($request->user()->tanda_tangan) {
            // Gunakan TTD dari profil user jika tidak ada TTD baru
            $validated['ttd_gambar'] = $request->user()->tanda_tangan;
        }
        unset($validated['ttd_gambar_canvas']);

        // Foto dokumentasi (max 10)
        $fotos = [];
        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $foto) {
                if (count($fotos) >= 10) {
                    break;
                }
                $fotos[] = $foto->store('dokumentasi', 'public');
            }
        }
        $validated['foto_dokumentasi'] = ! empty($fotos) ? $fotos : null;

        Laporan::create($validated);
        $sub->tambahPenggunaan();

        // Generate PDF & DOCX di background (simpan path)
        try {
            $laporan = Laporan::where('user_id', $request->user()->id)->latest()->first();
            $pdfPath = $this->exportService->generatePdf($laporan);
            $docxPath = $this->exportService->generateDocx($laporan);
            $laporan->update(['file_pdf' => $pdfPath, 'file_docx' => $docxPath]);
        } catch (\Throwable $e) {
            report($e);
        }

        // Redirect ke detail laporan — user bisa download dari sana
        $laporan = $laporan ?? Laporan::where('user_id', $request->user()->id)->latest()->first();

        return redirect()->route('laporan.show', $laporan)
            ->with('success', 'Laporan berhasil disimpan. Silakan download PDF atau Word dari halaman ini.');
    }

    public function show(Laporan $laporan): View
    {
        $this->authorize('view', $laporan);
        $laporan->load(['rhk', 'jenisRhk', 'user']);

        return view('laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan): View
    {
        $this->authorize('update', $laporan);
        $rhks = Rhk::with('jenisRhks')->orderBy('urutan')->get();
        $user = auth()->user();

        return view('laporan.edit', compact('laporan', 'rhks', 'user'));
    }

    public function update(UpdateLaporanRequest $request, Laporan $laporan): RedirectResponse
    {
        $this->authorize('update', $laporan);

        $validated = $request->validated();

        // File dokumen utama
        if ($request->hasFile('file_dokumen')) {
            if ($laporan->file_dokumen) {
                Storage::disk('public')->delete($laporan->file_dokumen);
            }
            $validated['file_dokumen'] = $request->file('file_dokumen')->store('laporan-dokumen', 'public');
        }

        // TTD
        if (! empty($validated['ttd_gambar_canvas'])) {
            if ($laporan->ttd_gambar) {
                Storage::disk('public')->delete($laporan->ttd_gambar);
            }
            $validated['ttd_gambar'] = $this->saveBase64Image($validated['ttd_gambar_canvas'], 'ttd');
        } elseif ($request->hasFile('ttd_gambar')) {
            if ($laporan->ttd_gambar) {
                Storage::disk('public')->delete($laporan->ttd_gambar);
            }
            $validated['ttd_gambar'] = $request->file('ttd_gambar')->store('ttd', 'public');
        }
        unset($validated['ttd_gambar_canvas']);

        // Foto dokumentasi — gabung existing + baru
        $existingFotos = json_decode($request->input('foto_dokumentasi_existing', '[]'), true) ?? [];
        $newFotos = [];
        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $foto) {
                if ((count($existingFotos) + count($newFotos)) >= 10) {
                    break;
                }
                $newFotos[] = $foto->store('dokumentasi', 'public');
            }
        }
        $allFotos = array_merge($existingFotos, $newFotos);

        // Hapus foto lama yang tidak ada di existing
        if ($laporan->foto_dokumentasi) {
            foreach ($laporan->foto_dokumentasi as $oldFoto) {
                if (! in_array($oldFoto, $existingFotos)) {
                    Storage::disk('public')->delete($oldFoto);
                }
            }
        }

        $validated['foto_dokumentasi'] = ! empty($allFotos) ? $allFotos : null;
        unset($validated['foto_dokumentasi_existing']);

        $laporan->update($validated);

        return redirect()->route('laporan.show', $laporan)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan): RedirectResponse
    {
        $this->authorize('delete', $laporan);

        if ($laporan->file_dokumen) {
            Storage::disk('public')->delete($laporan->file_dokumen);
        }
        if ($laporan->ttd_gambar) {
            Storage::disk('public')->delete($laporan->ttd_gambar);
        }
        if ($laporan->foto_dokumentasi) {
            foreach ($laporan->foto_dokumentasi as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }

        $laporan->delete();

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function getJenisRhk(Rhk $rhk): JsonResponse
    {
        return response()->json($rhk->jenisRhks()->orderBy('urutan')->get(['id', 'nama']));
    }

    private function saveBase64Image(string $base64, string $folder): string
    {
        // Format: data:image/png;base64,xxxx
        $data = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $decoded = base64_decode($data);
        $filename = $folder.'/'.uniqid('ttd_', true).'.png';
        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }
}
