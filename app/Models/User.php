<?php

namespace App\Models;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'jabatan',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'tanda_tangan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function isSuperadmin(): bool
    {
        return $this->role === Role::Superadmin;
    }

    public function isPelanggan(): bool
    {
        return $this->role === Role::Pelanggan;
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription(): ?UserSubscription
    {
        return $this->subscriptions()
            ->with('billingPlan')
            ->where('status', 'active')
            ->where('berakhir_at', '>', now())
            ->latest()
            ->first();
    }

    public function bisaBuatLaporan(): bool
    {
        $sub = $this->activeSubscription();

        return $sub !== null && $sub->bisaBuatLaporan();
    }
}
