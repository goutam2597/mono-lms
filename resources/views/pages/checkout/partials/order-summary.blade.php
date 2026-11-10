@php
    $symbol = __('app.common.currency');
    $showCountdown = $order->payment_status === 'pending' && $order->payment_deadline && blank($order->payment_reference);
@endphp

<div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
    <div class="px-5 py-4 border-b border-[#e7e7e7] flex items-center justify-between">
        <h3 class="font-bold text-[#191919] text-sm">Order Summary</h3>
        <span class="font-mono text-[11px] text-gray-400">{{ $order->order_number }}</span>
    </div>

    <div class="p-5 space-y-4">
        @if ($order->course)
            <div class="flex items-center gap-3">
                <img src="{{ $order->course->imageUrl() }}" alt="" class="w-16 h-12 rounded-lg object-cover shrink-0">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-[#191919] leading-snug line-clamp-2">{{ $order->course->title }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">By {{ $order->course->instructor?->name }} · {{ $order->course->typeLabel() }}</p>
                </div>
            </div>
        @endif

        <div class="border-t border-dashed border-[#e7e7e7] pt-4 space-y-2 text-sm">
            <div class="flex justify-between text-gray-500">
                <span>Course price</span>
                <span>{{ $symbol }}{{ number_format((float) $order->course->price, 2) }}</span>
            </div>
            @if ((float) $order->discount > 0)
                <div class="flex justify-between text-emerald-600">
                    <span>Coupon {{ $order->coupon_code }}</span>
                    <span>-{{ $symbol }}{{ number_format((float) $order->discount, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold text-[#191919] text-base pt-2 border-t border-[#e7e7e7]">
                <span>Total</span>
                <span>{{ $symbol }}{{ number_format((float) $order->amount, 2) }}</span>
            </div>
        </div>

        @if ($showCountdown)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-amber-700">Seat reserved — complete within</p>
                <p class="mt-1 font-mono text-3xl font-bold text-amber-800 tabular-nums" data-countdown data-deadline="{{ $order->payment_deadline->toISOString() }}">--:--</p>
                <p class="text-[11px] text-amber-600 mt-1">After that the reservation is released.</p>
            </div>
        @elseif ($order->isAwaitingVerification())
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-blue-700">Awaiting verification</p>
                <p class="text-[11px] text-blue-600 mt-1">Your transaction ID <span class="font-mono">{{ $order->payment_reference }}</span> is being reviewed by our team.</p>
            </div>
        @endif
    </div>
</div>

@include('pages.checkout.partials.order-countdown-script')
