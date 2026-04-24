<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'billing_plan_id',
        'mulai_at',
        'berakhir_at',
        'status',
        'laporan_digunakan',
        'laporan_reset_at',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'mulai_at' => 'datetime',
            'berakhir_at' => 'datetime',
            'laporan_reset_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function billingPlan(): BelongsTo
    {
        return $this->belongsTo(BillingPlan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->berakhir_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->berakhir_at->isPast() || $this->status === 'expired';
    }

    /** Sisa laporan. Untuk trial = total sisa, untuk berbayar = sisa bulan ini. null = unlimited */
    public function sisaLaporan(): ?int
    {
        if ($this->billingPlan->isUnlimited()) {
            return null;
        }

        if (! $this->billingPlan->is_trial) {
            $this->resetCounterIfNeeded();
        }

        return max(0, $this->billingPlan->batas_laporan_per_bulan - $this->laporan_digunakan);
    }

    public function bisaBuatLaporan(): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        if ($this->billingPlan->isUnlimited()) {
            return true;
        }

        $this->resetCounterIfNeeded();

        return $this->laporan_digunakan < $this->billingPlan->batas_laporan_per_bulan;
    }

    public function tambahPenggunaan(): void
    {
        $this->resetCounterIfNeeded();
        $this->increment('laporan_digunakan');
    }

    private function resetCounterIfNeeded(): void
    {
        // Paket trial: tidak reset, batas berlaku selama masa trial
        if ($this->billingPlan->is_trial) {
            return;
        }

        // Paket berbayar: reset counter setiap awal bulan
        if (is_null($this->laporan_reset_at) || $this->laporan_reset_at->month !== now()->month || $this->laporan_reset_at->year !== now()->year) {
            $this->update([
                'laporan_digunakan' => 0,
                'laporan_reset_at' => now(),
            ]);
        }
    }
}
