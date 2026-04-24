<?php

namespace App\Http\Controllers;

use App\Models\BillingPlan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function expired(Request $request): View
    {
        $plans = BillingPlan::public()->get();
        $subscription = $request->user()->activeSubscription();

        return view('subscription.expired', compact('plans', 'subscription'));
    }

    public function status(Request $request): View
    {
        $subscription = $request->user()->activeSubscription();
        $plans = BillingPlan::public()->get();

        return view('subscription.status', compact('subscription', 'plans'));
    }
}
