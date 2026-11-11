<x-layouts.app title="Order Complete - Mono LMS">

    <div class="max-w-2xl mx-auto px-4 sm:px-8 py-14">
        <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
            @if ($order->payment_status === 'paid')
                <div class="bg-emerald-500 p-8 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-white/25 flex items-center justify-center text-white text-2xl">✓</div>
                    <h1 class="text-xl font-bold text-white mt-4">Payment Successful!</h1>
                    <p class="text-emerald-50 text-sm mt-1">You are enrolled. Time to start learning.</p>
                </div>

                <div class="p-6 space-y-5">
                    @if ($order->course)
                        <div class="flex items-center gap-4 bg-[#f8fafb] border border-[#e7e7e7] rounded-xl p-4">
                            <img src="{{ $order->course->imageUrl() }}" alt="" class="w-20 h-14 rounded-lg object-cover shrink-0">
                            <div class="min-w-0">
                                <p class="font-semibold text-[#191919] text-sm line-clamp-2">{{ $order->course->trans('title') }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">By {{ $order->course->instructor?->name }} · {{ $order->course->typeLabel() }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="bg-[#f8fafb] border border-[#e7e7e7] rounded-xl px-4 py-3">
                                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Amount Paid</p>
                                <p class="font-bold text-[#191919] mt-1">{{ __('app.common.currency') }}{{ number_format((float) $order->amount, 2) }}</p>
                            </div>
                            <div class="bg-[#f8fafb] border border-[#e7e7e7] rounded-xl px-4 py-3">
                                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Order</p>
                                <p class="font-mono text-[13px] font-bold text-[#191919] mt-1">{{ $order->order_number }}</p>
                            </div>
                        </div>

                        <a href="{{ route('student.courses.learn', $order->course) }}"
                           class="w-full flex items-center justify-center gap-2 bg-[#344c3d] hover:bg-[#283a2f] text-white font-bold text-sm py-3.5 rounded-xl transition-colors">
                            Start Learning
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif
                </div>
            @else
                <div class="bg-[#0d9488] p-8 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-white/25 flex items-center justify-center text-white text-2xl">🏦</div>
                    <h1 class="text-xl font-bold text-white mt-4">Order Received</h1>
                    <p class="text-teal-50 text-sm mt-1">Waiting for your payment / verification.</p>
                </div>

                <div class="p-6 space-y-4 text-sm text-[#383838]">
                    @if (blank($order->payment_reference))
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-800">
                            <strong>Complete your payment within the reservation window</strong> — open the payment page below, send the amount and submit your transaction ID before the countdown ends.
                        </div>
                        <a href="{{ route('checkout.pay', [$order, $order->payment_method]) }}"
                           class="w-full flex items-center justify-center gap-2 bg-[#344c3d] hover:bg-[#283a2f] text-white font-bold text-sm py-3.5 rounded-xl transition-colors">
                            Open Payment Page
                        </a>
                    @else
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-blue-800">
                            Your transaction ID <span class="font-mono">{{ $order->payment_reference }}</span> has been received and is <strong>awaiting administrator verification</strong>. You will be notified the moment it is approved.
                        </div>
                    @endif

                    <a href="{{ route('student.orders') }}" class="block text-center text-xs font-semibold text-[#738a6e] hover:text-[#344c3d]">View all my orders →</a>
                </div>
            @endif
        </div>
    </div>

</x-layouts.app>
