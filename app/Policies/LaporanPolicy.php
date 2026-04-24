<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    /**
     * Superadmin dapat melakukan semua aksi.
     */
    public function before(User $user): ?bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Laporan $laporan): bool
    {
        return $user->id === $laporan->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isPelanggan();
    }

    public function update(User $user, Laporan $laporan): bool
    {
        return $user->id === $laporan->user_id;
    }

    public function delete(User $user, Laporan $laporan): bool
    {
        return $user->id === $laporan->user_id;
    }
}
