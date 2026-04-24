<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rhk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RhkController extends Controller
{
    public function index(): View
    {
        $rhks = Rhk::withCount('jenisRhks')->orderBy('urutan')->paginate(10);

        return view('admin.rhk.index', compact('rhks'));
    }

    public function create(): View
    {
        return view('admin.rhk.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:500'],
            'urutan' => ['required', 'integer', 'min:1'],
            'jenis' => ['nullable', 'array'],
            'jenis.*.nama' => ['required', 'string', 'max:500'],
            'jenis.*.urutan' => ['required', 'integer', 'min:1'],
        ]);

        $rhk = Rhk::create([
            'nama' => $validated['nama'],
            'urutan' => $validated['urutan'],
        ]);

        foreach ($validated['jenis'] ?? [] as $jenis) {
            $rhk->jenisRhks()->create($jenis);
        }

        return redirect()->route('admin.rhk.index')
            ->with('success', 'RHK berhasil ditambahkan.');
    }

    public function edit(Rhk $rhk): View
    {
        $rhk->load('jenisRhks');

        return view('admin.rhk.edit', compact('rhk'));
    }

    public function update(Request $request, Rhk $rhk): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:500'],
            'urutan' => ['required', 'integer', 'min:1'],
            'jenis' => ['nullable', 'array'],
            'jenis.*.id' => ['nullable', 'integer', 'exists:jenis_rhks,id'],
            'jenis.*.nama' => ['required', 'string', 'max:500'],
            'jenis.*.urutan' => ['required', 'integer', 'min:1'],
        ]);

        $rhk->update([
            'nama' => $validated['nama'],
            'urutan' => $validated['urutan'],
        ]);

        $submittedIds = collect($validated['jenis'] ?? [])->pluck('id')->filter()->values();

        // Hapus jenis yang dihapus dari form
        $rhk->jenisRhks()->whereNotIn('id', $submittedIds)->delete();

        // Update atau buat jenis
        foreach ($validated['jenis'] ?? [] as $jenis) {
            if (! empty($jenis['id'])) {
                $rhk->jenisRhks()->where('id', $jenis['id'])->update([
                    'nama' => $jenis['nama'],
                    'urutan' => $jenis['urutan'],
                ]);
            } else {
                $rhk->jenisRhks()->create([
                    'nama' => $jenis['nama'],
                    'urutan' => $jenis['urutan'],
                ]);
            }
        }

        return redirect()->route('admin.rhk.index')
            ->with('success', 'RHK berhasil diperbarui.');
    }

    public function destroy(Rhk $rhk): RedirectResponse
    {
        $rhk->delete();

        return redirect()->route('admin.rhk.index')
            ->with('success', 'RHK berhasil dihapus.');
    }
}
