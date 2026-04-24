<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserSubscriptionController extends Controller
{
    public function index(): View
    {
        $subscriptions = UserSubscription::with(['user', 'billingPlan'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create(): View
    {
        $users = User::where('role', 'pelanggan')->orderBy('name')->get(['id', 'name', 'email']);
        $plans = BillingPlan::active()->orderBy('urutan')->get();

        return view('admin.subscriptions.create', compact('users', 'plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'billing_plan_id' => ['required', 'exists:billing_plans,id'],
            'mulai_at' => ['required', 'date'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $plan = BillingPlan::findOrFail($validated['billing_plan_id']);
        $mulai = Carbon::parse($validated['mulai_at']);
        $berakhir = $mulai->copy()->addDays($plan->durasi_hari);

        // Nonaktifkan langganan aktif sebelumnya
        UserSubscription::where('user_id', $validated['user_id'])
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        UserSubscription::create([
            'user_id' => $validated['user_id'],
            'billing_plan_id' => $validated['billing_plan_id'],
            'mulai_at' => $mulai,
            'berakhir_at' => $berakhir,
            'status' => 'active',
            'laporan_digunakan' => 0,
            'laporan_reset_at' => now(),
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Langganan berhasil ditambahkan.');
    }

    public function edit(UserSubscription $subscription): View
    {
        $plans = BillingPlan::active()->orderBy('urutan')->get();

        return view('admin.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, UserSubscription $subscription): RedirectResponse
    {
        $validated = $request->validate([
            'billing_plan_id' => ['required', 'exists:billing_plans,id'],
            'mulai_at' => ['required', 'date'],
            'berakhir_at' => ['required', 'date', 'after:mulai_at'],
            'status' => ['required', 'in:active,expired,cancelled'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Langganan berhasil diperbarui.');
    }

    public function destroy(UserSubscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Langganan berhasil dihapus.');
    }
}
