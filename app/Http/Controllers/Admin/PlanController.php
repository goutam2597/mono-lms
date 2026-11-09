<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        return view('pages.admin.plans', [
            'plans' => Plan::orderBy('sort_order')->withCount('subscriptions')->get(),
            'membershipEnabled' => Setting::enabled('membership_enabled'),
        ]);
    }

    /**
     * The admin-dependent switch: enables/disables the whole membership
     * area (nav entry + pricing page) for everyone.
     */
    public function toggleMembership()
    {
        $new = Setting::enabled('membership_enabled') ? '0' : '1';
        Setting::set('membership_enabled', $new);

        return back()->with('success', __('admin.plans.membership_'.($new === '1' ? 'enabled' : 'disabled')));
    }

    public function create()
    {
        return view('pages.admin.plans-form', ['plan' => new Plan]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        Plan::create($data);

        return redirect()->route('admin.plans.index')->with('success', __('admin.plans.created'));
    }

    public function edit(Plan $plan)
    {
        return view('pages.admin.plans-form', ['plan' => $plan]);
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validated($request);

        if ($plan->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $plan->id);
        }

        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('success', __('admin.plans.updated'));
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', __('admin.plans.has_subscribers'));
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', __('admin.plans.deleted'));
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:plans,id'],
        ]);

        $plans = Plan::whereIn('id', $data['ids'])->withCount(['subscriptions' => fn ($q) => $q->where('status', 'active')])->get();
        $deleted = 0;
        $skipped = 0;

        foreach ($plans as $plan) {
            if ($plan->subscriptions_count > 0) {
                $skipped++;

                continue;
            }
            $plan->delete();
            $deleted++;
        }

        $msg = "Deleted {$deleted} plans successfully.";
        if ($skipped > 0) {
            $msg .= " ({$skipped} plans skipped because they have active subscribers)";
        }

        return redirect()->route('admin.plans.index')->with('success', $msg);
    }

    private function validated(Request $request): array
    {
        $features = array_values(array_filter(array_map('trim', explode("\n", (string) $request->input('features')))));

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999'],
            'billing_period' => ['required', 'in:monthly,yearly'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]) + ['features' => $features];

        // Explicit booleans so unchecking actually unsets.
        $data['is_active'] = $request->boolean('is_active');
        $data['is_popular'] = $request->boolean('is_popular');

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'plan';
        $slug = $base;
        $i = 2;

        while (Plan::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
