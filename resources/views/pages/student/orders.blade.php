<x-layouts.dashboard title="My Orders - Mono LMS" active="orders" userType="student">

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">My Orders</h1>
                <p class="text-xs text-neutral-400 mt-1">Course purchases, payments and verification status.</p>
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="flex flex-wrap gap-2">
            @foreach (['all' => 'All', 'pending' => 'Pending', 'paid' => 'Paid', 'cancelled' => 'Cancelled', 'failed' => 'Failed', 'refunded' => 'Refunded'] as $key => $label)
                <a href="{{ route('student.orders', $key === 'all' ? [] : ['status' => $key]) }}"
                   class="px-4 py-2 rounded-full text-xs font-semibold transition-colors {{ ($status ?? 'all') === $key ? 'bg-[#344c3d] text-white' : 'bg-white border border-[#e7e7e7] text-[#5d5d5d] hover:border-[#344c3d]' }}">
                    {{ $label }} ({{ $counts[$key] ?? 0 }})
                </a>
            @endforeach
        </div>

        <!-- Orders List -->
        <div class="space-y-4">
            @forelse ($orders as $order)
                <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                        @if ($order->course)
                            <img src="{{ $order->course->imageUrl() }}" alt="" class="w-24 h-16 rounded-lg object-cover shrink-0">
                        @endif

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-semibold text-[#191919] text-sm truncate">{{ $order->course?->title ?? 'Course purchase' }}</p>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                    {{ $order->statusTone() === 'emerald' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                    {{ $order->statusTone() === 'amber' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                    {{ $order->statusTone() === 'rose' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                                    {{ $order->statusTone() === 'slate' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                                @if ($order->isAwaitingVerification())
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-200">Verifying</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1 font-mono">{{ $order->order_number }} · {{ $order->methodLabel() }} · {{ $order->created_at->format('d M Y, h:i A') }}</p>

                            @if ($order->payment_status === 'pending' && $order->payment_deadline && blank($order->payment_reference))
                                <p class="text-[11px] text-amber-600 mt-1.5 font-medium">
                                    Reserved — pay within <span class="font-mono font-bold" data-countdown data-deadline="{{ $order->payment_deadline->toISOString() }}">--:--</span>
                                </p>
                            @elseif ($order->isAwaitingVerification())
                                <p class="text-[11px] text-blue-600 mt-1.5 font-medium">Trx ID <span class="font-mono">{{ $order->payment_reference }}</span> awaiting verification</p>
                            @elseif ($order->payment_status === 'paid')
                                <p class="text-[11px] text-emerald-600 mt-1.5 font-medium">Paid {{ $order->paid_at?->format('d M Y, h:i A') }}</p>
                            @elseif ($order->payment_status === 'cancelled')
                                <p class="text-[11px] text-slate-500 mt-1.5 font-medium">Reservation expired without payment</p>
                            @endif
                        </div>

                        <div class="flex flex-col items-start sm:items-end gap-2 shrink-0">
                            <p class="font-bold text-[#191919]">{{ __('app.common.currency') }}{{ number_format((float) $order->amount, 2) }}</p>
                            <div class="flex items-center gap-2">
                                @if (in_array($order->payment_status, ['pending', 'failed']) && $order->canPay())
                                    <a href="{{ route('checkout.pay', [$order, $order->payment_method]) }}" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-[11px] font-bold px-4 py-2 rounded-lg transition-colors">
                                        {{ $order->payment_status === 'failed' ? 'Retry Payment' : 'Pay Now' }}
                                    </a>
                                @elseif ($order->payment_status === 'paid' && $order->course)
                                    <a href="{{ route('student.courses.learn', $order->course) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-4 py-2 rounded-lg transition-colors">Start Learning</a>
                                @endif
                                <a href="{{ route('student.orders.show', $order) }}" class="border border-[#e7e7e7] hover:border-[#344c3d] text-[#5d5d5d] text-[11px] font-bold px-4 py-2 rounded-lg transition-colors">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-12 text-center">
                    <div class="w-12 h-12 mx-auto rounded-full bg-[#f8fafb] flex items-center justify-center text-xl text-gray-300">🛒</div>
                    <p class="text-sm font-semibold text-[#191919] mt-4">No orders yet</p>
                    <p class="text-xs text-gray-400 mt-1">Purchases you make will appear here.</p>
                    <a href="{{ route('courses.index') }}" class="inline-block mt-4 bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-bold px-5 py-2.5 rounded-lg transition-colors">Browse Courses</a>
                </div>
            @endforelse
        </div>

        @if ($orders->hasPages())
            <div class="pt-2">{{ $orders->links() }}</div>
        @endif
    </div>

    @include('pages.checkout.partials.order-countdown-script')

</x-layouts.dashboard>
