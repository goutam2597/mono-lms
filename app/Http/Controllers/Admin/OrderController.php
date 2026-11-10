<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(private readonly CheckoutService $checkout) {}

    public function index(Request $request): View
    {
        $query = Order::with(['student', 'course', 'plan'])->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->query('status'));
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->query('method'));
        }

        if ($request->filled('search')) {
            $search = '%'.$request->query('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', $search)
                    ->orWhere('payment_reference', 'like', $search)
                    ->orWhereHas('student', fn ($sq) => $sq->where('name', 'like', $search)->orWhere('email', 'like', $search))
                    ->orWhereHas('course', fn ($cq) => $cq->where('title', 'like', $search));
            });
        }

        $orders = $query->paginate(12)->withQueryString();

        $stats = [
            'total_sales' => (float) Order::where('payment_status', 'paid')->sum('amount'),
            'paid_count' => Order::where('payment_status', 'paid')->count(),
            'pending_count' => Order::where('payment_status', 'pending')->count(),
            'refunded_count' => Order::where('payment_status', 'refunded')->count(),
        ];

        return view('pages.admin.orders', [
            'orders' => $orders,
            'stats' => $stats,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['student', 'course.instructor', 'plan']);

        return view('pages.admin.order-detail', [
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'payment_status' => ['required', 'string', 'in:paid,pending,refunded,failed,cancelled'],
        ]);

        $order->update($data);

        return back()->with('success', "Order {$order->order_number} status updated to {$data['payment_status']}.");
    }

    /**
     * Admin verification of a submitted manual payment (mono_market pattern):
     * approve -> paid + enrollment activation; reject -> failed with reason.
     */
    public function verifyPayment(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'string', 'in:approve,reject'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            if ($data['action'] === 'approve') {
                $this->checkout->markPaid($order);

                return back()->with('success', "Payment verified — {$order->student->name} is now enrolled in \"{$order->course->title}\".");
            }

            $this->checkout->rejectPayment($order, $data['reason'] ?? null);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', "Payment for {$order->order_number} was rejected and the student was notified.");
    }
}
