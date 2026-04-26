<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGpsPhotoSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has active subscription
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return redirect()->route('subscription.expired')->with('error', 'Anda harus berlangganan untuk menggunakan fitur GPS Foto');
        }

        return $next($request);
    }
}
