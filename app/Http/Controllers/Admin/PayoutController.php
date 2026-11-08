<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payout::with('instructor')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $payouts = $query->paginate(12)->withQueryString();

        return view('pages.admin.payouts', [
            'payouts' => $payouts,
            'stats' => [
                'pending_amount' => (float) Payout::where('status', 'pending')->sum('amount'),
                'pending_count' => Payout::where('status', 'pending')->count(),
                'processed_amount' => (float) Payout::where('status', 'processed')->sum('amount'),
                'processed_count' => Payout::where('status', 'processed')->count(),
            ],
        ]);
    }

    public function update(Request $request, Payout $payout): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:pending,processed,rejected'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if ($data['status'] === 'processed' && ! $payout->processed_at) {
            $data['processed_at'] = now();
        }

        $payout->update($data);

        return back()->with('success', "Payout #{$payout->id} status updated to {$data['status']}.");
    }
}
