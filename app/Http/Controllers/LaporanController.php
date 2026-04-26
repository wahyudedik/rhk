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
            ->where('user_id', $request->user()->id)
            ->laporans();

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
        $totalLaporan = Laporan::where('user_id', $request->user()->id)->laporans()->count();

        return view('laporan.index', compact('laporans', 'tahunList', 'rhkList', 'totalLaporan'));
    }

    public function create(): View
    {
        $this->authorize('create', Laporan::class);

        $rhks = Rhk::with('jenisRhks')->orderBy('urutan')->get();
        $user = auth()->user();

        $templates = Laporan::with(['rhk', 'jenisRhk'])
            ->where('user_id', $user->id)
            ->templates()
            ->orderByDesc('updated_at')
            ->get();

        // Get GPS photos for this user
        $gpsPhotos = $user->gpsPhotos()
            ->orderByDesc('created_at')
            ->get();

        return view('laporan.create', compact('rhks', 'user', 'templates', 'gpsPhotos'));
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

        // Foto dokumentasi (max 10) — ambil langsung dari request, bukan dari validated
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

        $laporan = Laporan::create($validated);
        $sub->tambahPenggunaan();

        // Otomatis simpan/perbarui template untuk RHK + Jenis RHK ini
        $this->upsertTemplate($laporan);

        // Generate PDF & DOCX di background (simpan path)
        try {
            $pdfPath = $this->exportService->generatePdf($laporan);
            $docxPath = $this->exportService->generateDocx($laporan);
            $laporan->update(['file_pdf' => $pdfPath, 'file_docx' => $docxPath]);
        } catch (\Throwable $e) {
            report($e);
        }

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

    public function getGpsPhotos(): JsonResponse
    {
        $user = auth()->user();
        $photos = $user->gpsPhotos()
            ->orderByDesc('created_at')
            ->get(['id', 'filename', 'original_filename', 'latitude', 'longitude', 'address', 'created_at']);

        return response()->json($photos);
    }

    public function templates(): View
    {
        $templates = Laporan::with(['rhk', 'jenisRhk'])
            ->where('user_id', auth()->id())
            ->templates()
            ->orderByDesc('updated_at')
            ->get();

        return view('laporan.templates', compact('templates'));
    }

    public function loadTemplate(Laporan $laporan): JsonResponse
    {
        $this->authorize('view', $laporan);

        if (! $laporan->is_template) {
            abort(404);
        }

        return response()->json([
            'rhk_id' => $laporan->rhk_id,
            'jenis_rhk_id' => $laporan->jenis_rhk_id,
            'header_instansi_1' => $laporan->header_instansi_1,
            'header_instansi_2' => $laporan->header_instansi_2,
            'header_instansi_3' => $laporan->header_instansi_3,
            'header_instansi_4' => $laporan->header_instansi_4,
            'latar_belakang' => $laporan->latar_belakang,
            'maksud_tujuan' => $laporan->maksud_tujuan,
            'ruang_lingkup' => $laporan->ruang_lingkup,
            'dasar' => $laporan->dasar,
            'kegiatan_dilaksanakan' => $laporan->kegiatan_dilaksanakan,
            'hasil_dicapai' => $laporan->hasil_dicapai,
            'simpulan' => $laporan->simpulan,
            'saran' => $laporan->saran,
            'penutup' => $laporan->penutup,
            'ttd_kota' => $laporan->ttd_kota,
            'ttd_jabatan' => $laporan->ttd_jabatan,
            'ttd_nama' => $laporan->ttd_nama,
            'ttd_nip' => $laporan->ttd_nip,
            'template_name' => $laporan->template_name,
        ]);
    }

    public function saveAsTemplate(Laporan $laporan): RedirectResponse
    {
        $this->authorize('update', $laporan);

        $this->upsertTemplate($laporan);

        return back()->with('success', 'Laporan berhasil disimpan sebagai template.');
    }

    public function destroyTemplate(Laporan $laporan): RedirectResponse
    {
        $this->authorize('delete', $laporan);

        if (! $laporan->is_template) {
            abort(404);
        }

        $laporan->delete();

        return redirect()->route('laporan.templates')
            ->with('success', 'Template berhasil dihapus.');
    }

    /**
     * Simpan atau perbarui template berdasarkan user + rhk + jenis_rhk.
     * Satu kombinasi user+rhk+jenis_rhk hanya punya satu template (upsert).
     */
    private function upsertTemplate(Laporan $laporan): void
    {
        $templateName = $laporan->jenisRhk?->nama ?? 'Template';

        $existing = Laporan::where('user_id', $laporan->user_id)
            ->where('rhk_id', $laporan->rhk_id)
            ->where('jenis_rhk_id', $laporan->jenis_rhk_id)
            ->where('is_template', true)
            ->first();

        $templateData = [
            'user_id' => $laporan->user_id,
            'rhk_id' => $laporan->rhk_id,
            'jenis_rhk_id' => $laporan->jenis_rhk_id,
            'bulan' => $laporan->bulan,
            'tahun' => $laporan->tahun,
            'header_instansi_1' => $laporan->header_instansi_1,
            'header_instansi_2' => $laporan->header_instansi_2,
            'header_instansi_3' => $laporan->header_instansi_3,
            'header_instansi_4' => $laporan->header_instansi_4,
            'latar_belakang' => $laporan->latar_belakang,
            'maksud_tujuan' => $laporan->maksud_tujuan,
            'ruang_lingkup' => $laporan->ruang_lingkup,
            'dasar' => $laporan->dasar,
            'kegiatan_dilaksanakan' => $laporan->kegiatan_dilaksanakan,
            'hasil_dicapai' => $laporan->hasil_dicapai,
            'simpulan' => $laporan->simpulan,
            'saran' => $laporan->saran,
            'penutup' => $laporan->penutup,
            'ttd_kota' => $laporan->ttd_kota,
            'ttd_jabatan' => $laporan->ttd_jabatan,
            'ttd_nama' => $laporan->ttd_nama,
            'ttd_nip' => $laporan->ttd_nip,
            'ttd_gambar' => $laporan->ttd_gambar,
            'is_template' => true,
            'template_name' => $templateName,
        ];

        if ($existing) {
            $existing->update($templateData);
        } else {
            Laporan::create($templateData);
        }
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
