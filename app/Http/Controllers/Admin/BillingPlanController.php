<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingPlanController extends Controller
{
    public function index(): View
    {
        $plans = BillingPlan::orderBy('urutan')->paginate(10);

        return view('admin.billing.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.billing.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:50', 'unique:billing_plans,slug', 'regex:/^[a-z0-9\-]+$/'],
            'harga' => ['required', 'numeric', 'min:0'],
            'durasi_hari' => ['required', 'integer', 'min:1'],
            'batas_laporan_per_bulan' => ['nullable', 'integer', 'min:1'],
            'fitur' => ['nullable', 'string'],
            'is_trial' => ['boolean'],
            'is_active' => ['boolean'],
            'urutan' => ['required', 'integer', 'min:0'],
        ]);

        $validated['fitur'] = $this->parseFitur($request->input('fitur', ''));
        $validated['is_trial'] = $request->boolean('is_trial');
        $validated['is_active'] = $request->boolean('is_active', true);

        BillingPlan::create($validated);

        return redirect()->route('admin.billing.index')
            ->with('success', 'Paket billing berhasil ditambahkan.');
    }

    public function edit(BillingPlan $billing): View
    {
        return view('admin.billing.edit', compact('billing'));
    }

    public function update(Request $request, BillingPlan $billing): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9\-]+$/', "unique:billing_plans,slug,{$billing->id}"],
            'harga' => ['required', 'numeric', 'min:0'],
            'durasi_hari' => ['required', 'integer', 'min:1'],
            'batas_laporan_per_bulan' => ['nullable', 'integer', 'min:1'],
            'fitur' => ['nullable', 'string'],
            'is_trial' => ['boolean'],
            'is_active' => ['boolean'],
            'urutan' => ['required', 'integer', 'min:0'],
        ]);

        $validated['fitur'] = $this->parseFitur($request->input('fitur', ''));
        $validated['is_trial'] = $request->boolean('is_trial');
        $validated['is_active'] = $request->boolean('is_active', true);

        $billing->update($validated);

        return redirect()->route('admin.billing.index')
            ->with('success', 'Paket billing berhasil diperbarui.');
    }

    public function destroy(BillingPlan $billing): RedirectResponse
    {
        if ($billing->subscriptions()->exists()) {
            return back()->with('error', 'Paket tidak dapat dihapus karena masih digunakan oleh pengguna.');
        }

        $billing->delete();

        return redirect()->route('admin.billing.index')
            ->with('success', 'Paket billing berhasil dihapus.');
    }

    private function parseFitur(string $raw): array
    {
        return collect(explode("\n", $raw))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->toArray();
    }
}
