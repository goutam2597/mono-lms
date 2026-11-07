<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Setting;
use App\Models\Subscription;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Membership pricing page. Admin-dependent: hidden (404) entirely when
     * the super admin has disabled plans via settings.
     */
    public function index()
    {
        abort_unless(Setting::enabled('membership_enabled'), 404);

        $currentSubscription = auth('student')->user()
            ?->subscriptions()->with('plan')->where('status', 'active')->first();

        return view('pages.membership.index', [
            'plans' => Plan::active()->orderBy('sort_order')->get(),
            'currentSubscription' => $currentSubscription,
        ]);
    }

    /**
     * Subscribe the signed-in student to a plan. Any previous active
     * subscription is cancelled first — one plan per student.
     */
    public function subscribe(Request $request, Plan $plan)
    {
        abort_unless(Setting::enabled('membership_enabled'), 404);
        abort_unless($plan->is_active, 404);

        $student = auth('student')->user();

        Subscription::where('student_id', $student->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled', 'ends_at' => now()]);

        Subscription::create([
            'student_id' => $student->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $plan->billing_period === 'yearly' ? now()->addYear() : now()->addMonth(),
        ]);

        return redirect()
            ->route('membership.index')
            ->with('success', __('membership.subscribed', ['plan' => $plan->name]));
    }
}
