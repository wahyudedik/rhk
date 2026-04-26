<?php

namespace App\Policies;

use App\Models\GpsPhoto;
use App\Models\User;

class GpsPhotoPolicy
{
    public function delete(User $user, GpsPhoto $gpsPhoto): bool
    {
        return $user->id === $gpsPhoto->user_id;
    }
}
