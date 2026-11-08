<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        $query = Coupon::latest();

        if ($request->filled('search')) {
            $search = '%'.$request->query('search').'%';
            $query->where(fn ($q) => $q->where('code', 'like', $search)->orWhere('title', 'like', $search));
        }

        $coupons = $query->paginate(12)->withQueryString();

        return view('pages.admin.coupons', [
            'coupons' => $coupons,
            'stats' => [
                'total' => Coupon::count(),
                'active' => Coupon::where('is_active', true)->count(),
                'total_redemptions' => Coupon::sum('used_count'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'title' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'in:percent,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = true;

        Coupon::create($data);

        return back()->with('success', "Coupon {$data['code']} created successfully.");
    }

    public function toggle(Coupon $coupon): RedirectResponse
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);

        $status = $coupon->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Coupon {$coupon->code} {$status}.");
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $code = $coupon->code;
        $coupon->delete();

        return back()->with('success', "Coupon {$code} deleted.");
    }
}
