<?php

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\BillingPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Console\Command;

class AssignTrialToExistingUsers extends Command
{
    protected $signature = 'trial:assign-existing';

    protected $description = 'Berikan trial ke user pelanggan yang belum punya subscription';

    public function handle(): void
    {
        $trialPlan = BillingPlan::where('is_trial', true)->where('is_active', true)->first();

        if (! $trialPlan) {
            $this->error('Paket trial tidak ditemukan.');

            return;
        }

        $users = User::where('role', Role::Pelanggan)
            ->whereDoesntHave('subscriptions')
            ->get();

        $count = 0;
        foreach ($users as $user) {
            UserSubscription::create([
                'user_id' => $user->id,
                'billing_plan_id' => $trialPlan->id,
                'mulai_at' => now(),
                'berakhir_at' => now()->addDays($trialPlan->durasi_hari),
                'status' => 'active',
                'laporan_digunakan' => 0,
                'laporan_reset_at' => now(),
                'catatan' => 'Trial otomatis (assign ke user lama)',
            ]);
            $count++;
        }

        $this->info("Trial diberikan ke {$count} user.");
    }
}
