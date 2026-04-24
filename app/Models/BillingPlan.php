<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingPlan extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'harga',
        'durasi_hari',
        'batas_laporan_per_bulan',
        'fitur',
        'is_trial',
        'is_active',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'fitur' => 'array',
            'is_trial' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function isUnlimited(): bool
    {
        return is_null($this->batas_laporan_per_bulan);
    }

    public function hargaFormatted(): string
    {
        if ($this->harga == 0) {
            return 'Gratis';
        }

        return 'Rp '.number_format($this->harga, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_active', true)->where('is_trial', false)->orderBy('urutan');
    }
}
