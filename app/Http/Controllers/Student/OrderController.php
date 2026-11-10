<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\PaymentMethodService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class OrderController extends Controller
{
    public function __construct(private readonly CheckoutService $checkout) {}

    public function index(Request $request): View
    {
        // Lazy sweep: cancel reservations whose 60-minute window lapsed.
        $this->checkout->cancelExpired();

        $student = auth('student')->user();
        $status = $request->input('status', 'all');

        $orders = Order::where('student_id', $student->id)
            ->when(in_array($status, ['pending', 'paid', 'failed', 'cancelled', 'refunded'], true), fn ($q) => $q->where('payment_status', $status))
            ->with('course')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'all' => Order::where('student_id', $student->id)->count(),
            'pending' => Order::where('student_id', $student->id)->where('payment_status', 'pending')->count(),
            'paid' => Order::where('student_id', $student->id)->where('payment_status', 'paid')->count(),
            'cancelled' => Order::where('student_id', $student->id)->where('payment_status', 'cancelled')->count(),
        ];

        return view('pages.student.orders', [
            'orders' => $orders,
            'status' => $status,
            'counts' => $counts,
        ]);
    }

    public function show(Order $order): View
    {
        abort_unless($order->student_id === auth('student')->id(), 403);

        // Re-run the sweep so a lapsed countdown is reflected before rendering.
        if ($order->payment_status === 'pending' && blank($order->payment_reference)) {
            $this->checkout->cancelExpired();
            $order->refresh();
        }

        return view('pages.student.order-show', [
            'order' => $order->load('course'),
            'offlineInstructions' => PaymentMethodService::offlineInstructions(),
        ]);
    }
}
