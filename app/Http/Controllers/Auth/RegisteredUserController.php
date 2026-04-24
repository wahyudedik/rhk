<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BillingPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Berikan trial otomatis
        $trialPlan = BillingPlan::where('is_trial', true)->where('is_active', true)->first();
        if ($trialPlan) {
            UserSubscription::create([
                'user_id' => $user->id,
                'billing_plan_id' => $trialPlan->id,
                'mulai_at' => now(),
                'berakhir_at' => now()->addDays($trialPlan->durasi_hari),
                'status' => 'active',
                'laporan_digunakan' => 0,
                'laporan_reset_at' => now(),
                'catatan' => 'Trial otomatis saat registrasi',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('laporan.index', absolute: false));
    }
}
