<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Superadmin melewati semua gate check
        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperadmin()) {
                return true;
            }

            return null;
        });
    }
}
