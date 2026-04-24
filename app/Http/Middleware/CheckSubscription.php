<?php

namespace App\Http\Middleware;

use App\Models\BillingPlan;
use App\Models\UserSubscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isPelanggan()) {
            return $next($request);
        }

        $sub = $user->activeSubscription();

        // Tidak punya langganan aktif — paksa trial otomatis
        if (! $sub) {
            $sub = $this->assignTrialAutomatically($user);

            if (! $sub) {
                return redirect()->route('subscription.expired')
                    ->with('warning', 'Langganan Anda tidak aktif. Silakan hubungi admin untuk berlangganan.');
            }
        }

        // Cek hanya untuk route yang membuat laporan baru
        if ($request->routeIs('laporan.create') || $request->routeIs('laporan.store')) {
            if (! $sub->bisaBuatLaporan()) {
                $isTrial = $sub->billingPlan->is_trial;
                $pesan = $isTrial
                    ? 'Kuota trial Anda (5 laporan) sudah habis. Upgrade ke paket berbayar untuk terus membuat laporan.'
                    : 'Kuota laporan bulan ini sudah habis. Upgrade paket atau tunggu bulan berikutnya.';

                return redirect()->route('subscription.expired')
                    ->with('warning', $pesan);
            }
        }

        return $next($request);
    }

    private function assignTrialAutomatically($user): ?UserSubscription
    {
        $trialPlan = BillingPlan::where('is_trial', true)->where('is_active', true)->first();

        if (! $trialPlan) {
            return null;
        }

        return UserSubscription::create([
            'user_id' => $user->id,
            'billing_plan_id' => $trialPlan->id,
            'mulai_at' => now(),
            'berakhir_at' => now()->addDays($trialPlan->durasi_hari),
            'status' => 'active',
            'laporan_digunakan' => 0,
            'laporan_reset_at' => now(),
            'catatan' => 'Trial otomatis (assign saat login)',
        ]);
    }
}
