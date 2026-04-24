<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisRhk;
use App\Models\Rhk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JenisRhkController extends Controller
{
    public function index(Rhk $rhk): View
    {
        $jenisRhks = $rhk->jenisRhks()->orderBy('urutan')->paginate(10);

        return view('admin.jenis-rhk.index', compact('rhk', 'jenisRhks'));
    }

    public function create(Rhk $rhk): View
    {
        return view('admin.jenis-rhk.create', compact('rhk'));
    }

    public function store(Request $request, Rhk $rhk): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:500'],
            'urutan' => ['required', 'integer', 'min:1'],
        ]);

        $rhk->jenisRhks()->create($validated);

        return redirect()->route('admin.rhk.jenis-rhk.index', $rhk)
            ->with('success', 'Jenis RHK berhasil ditambahkan.');
    }

    public function edit(Rhk $rhk, JenisRhk $jenisRhk): View
    {
        return view('admin.jenis-rhk.edit', compact('rhk', 'jenisRhk'));
    }

    public function update(Request $request, Rhk $rhk, JenisRhk $jenisRhk): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:500'],
            'urutan' => ['required', 'integer', 'min:1'],
        ]);

        $jenisRhk->update($validated);

        return redirect()->route('admin.rhk.jenis-rhk.index', $rhk)
            ->with('success', 'Jenis RHK berhasil diperbarui.');
    }

    public function destroy(Rhk $rhk, JenisRhk $jenisRhk): RedirectResponse
    {
        $jenisRhk->delete();

        return redirect()->route('admin.rhk.jenis-rhk.index', $rhk)
            ->with('success', 'Jenis RHK berhasil dihapus.');
    }
}
