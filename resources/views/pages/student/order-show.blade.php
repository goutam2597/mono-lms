<x-layouts.dashboard title="Order {{ $order->order_number }} - Mono LMS" active="orders" userType="student">

    <div class="space-y-6">
        <div>
            <a href="{{ route('student.orders') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] mb-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to My Orders
            </a>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Order <span class="font-mono">{{ $order->order_number }}</span></h1>
            <p class="text-xs text-neutral-400 mt-1">Placed {{ $order->created_at->format('d M Y, h:i A') }} · {{ $order->methodLabel() }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 items-start">
            <!-- Order Body -->
            <div class="space-y-6">
                @if ($order->payment_status === 'pending' && blank($order->payment_reference))
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-amber-800">Complete your payment</h3>
                        <p class="text-xs text-amber-700 mt-1">Reservation ends in <span class="font-mono font-bold" data-countdown data-deadline="{{ $order->payment_deadline?->toISOString() }}">--:--</span></p>
                        @if ($order->canPay())
                            <a href="{{ route('checkout.pay', [$order, $order->payment_method]) }}" class="mt-3 inline-flex bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-bold px-5 py-2.5 rounded-lg transition-colors">Open Payment Page</a>
                        @endif
                    </div>
                @elseif ($order->isAwaitingVerification())
                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-blue-800">Awaiting verification</h3>
                        <p class="text-xs text-blue-700 mt-1">Transaction ID <span class="font-mono">{{ $order->payment_reference }}</span> submitted — an administrator will review it shortly.</p>
                    </div>
                @elseif ($order->payment_status === 'paid')
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-emerald-800">Paid — enrollment active</h3>
                        <p class="text-xs text-emerald-700 mt-1">Verified {{ $order->paid_at?->format('d M Y, h:i A') }}.</p>
                        @if ($order->course)
                            <a href="{{ route('student.courses.learn', $order->course) }}" class="mt-3 inline-flex bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition-colors">Start Learning</a>
                        @endif
                    </div>
                @elseif ($order->payment_status === 'cancelled')
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-slate-700">Reservation expired</h3>
                        <p class="text-xs text-slate-500 mt-1">No payment was completed within 60 minutes. Start a new purchase from the course page.</p>
                        @if ($order->course)
                            <a href="{{ route('courses.show', $order->course->slug) }}" class="mt-3 inline-flex bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-bold px-5 py-2.5 rounded-lg transition-colors">Purchase Again</a>
                        @endif
                    </div>
                @elseif ($order->payment_status === 'failed')
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-rose-800">Payment rejected</h3>
                        <p class="text-xs text-rose-700 mt-1">The submitted payment could not be verified.</p>
                        @if ($order->canPay())
                            <a href="{{ route('checkout.pay', [$order, $order->payment_method]) }}" class="mt-3 inline-flex bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-bold px-5 py-2.5 rounded-lg transition-colors">Retry Payment</a>
                        @endif
                    </div>
                @endif

                <!-- Course Card -->
                @if ($order->course)
                    <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden">
                        <div class="p-5 flex items-center gap-4">
                            <img src="{{ $order->course->imageUrl() }}" alt="" class="w-24 h-16 rounded-lg object-cover shrink-0">
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-[#191919] text-sm">{{ $order->course->trans('title') }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">By {{ $order->course->instructor?->name }} · {{ $order->course->typeLabel() }} · {{ $order->course->level }}</p>
                            </div>
                            @if ($order->payment_status === 'paid')
                                <a href="{{ route('student.courses.learn', $order->course) }}" class="action-btn action-btn-primary h-9 px-4 text-[10px] shrink-0">Open Course</a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Summary -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#e7e7e7]">
                    <h3 class="font-bold text-[#191919] text-sm">Payment Details</h3>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex justify-between text-gray-500"><span>Status</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                            {{ $order->statusTone() === 'emerald' ? 'bg-emerald-50 text-emerald-700' : '' }}
                            {{ $order->statusTone() === 'amber' ? 'bg-amber-50 text-amber-700' : '' }}
                            {{ $order->statusTone() === 'rose' ? 'bg-rose-50 text-rose-700' : '' }}
                            {{ $order->statusTone() === 'slate' ? 'bg-slate-100 text-slate-600' : '' }}">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500"><span>Method</span><span class="text-[#191919] font-medium">{{ $order->methodLabel() }}</span></div>
                    @if ($order->payment_reference)
                        <div class="flex justify-between text-gray-500"><span>Trx ID</span><span class="font-mono text-[#191919]">{{ $order->payment_reference }}</span></div>
                    @endif
                    <div class="border-t border-dashed border-[#e7e7e7] pt-3 flex justify-between text-gray-500"><span>Course price</span><span>{{ __('app.common.currency') }}{{ number_format((float) $order->course?->price ?? 0, 2) }}</span></div>
                    @if ((float) $order->discount > 0)
                        <div class="flex justify-between text-emerald-600"><span>Coupon {{ $order->coupon_code }}</span><span>-{{ __('app.common.currency') }}{{ number_format((float) $order->discount, 2) }}</span></div>
                    @endif
                    <div class="border-t border-[#e7e7e7] pt-3 flex justify-between font-bold text-[#191919]"><span>Total</span><span>{{ __('app.common.currency') }}{{ number_format((float) $order->amount, 2) }}</span></div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.checkout.partials.order-countdown-script')

</x-layouts.dashboard>
