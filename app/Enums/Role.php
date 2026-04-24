<?php

namespace App\Enums;

enum Role: string
{
    case Superadmin = 'superadmin';
    case Pelanggan = 'pelanggan';

    public function label(): string
    {
        return match ($this) {
            Role::Superadmin => 'Super Admin',
            Role::Pelanggan => 'Pelanggan',
        };
    }
}
