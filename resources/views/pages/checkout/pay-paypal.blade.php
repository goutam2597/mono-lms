<x-layouts.app title="Pay with PayPal - Mono LMS">

    <div class="max-w-6xl mx-auto px-4 sm:px-8 py-10">
        <a href="{{ route('student.orders') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] mb-5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to My Orders
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 items-start">
            <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#e7e7e7] flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-[#003087]/10 text-[#003087] flex items-center justify-center font-bold text-sm italic">P</span>
                    <div>
                        <h2 class="font-bold text-[#191919]">Pay with PayPal</h2>
                        <p class="text-[11px] text-gray-400">PayPal balance · linked cards · buyer protection</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    <div class="bg-[#f8fafb] border border-[#e7e7e7] rounded-xl p-4 text-sm text-[#5d5d5d] leading-relaxed">
                        You will be redirected to <strong>PayPal</strong> to approve the payment. After approving, PayPal returns you here and your enrollment is activated instantly.
                    </div>

                    <ul class="space-y-2 text-xs text-gray-500">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Buyer protection included</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Enrollment activated immediately after approval</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Reservation is held for the countdown shown on the right</li>
                    </ul>

                    <form action="{{ route('checkout.pay.initiate', [$order, 'paypal']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#ffc439] hover:bg-[#f0b329] text-[#003087] font-bold text-sm py-3.5 rounded-xl transition-colors">
                            Pay {{ __('app.common.currency') }}{{ number_format((float) $order->amount, 2) }} with PayPal
                        </button>
                        <p class="text-center text-[11px] text-gray-400 mt-2">Secure redirect · powered by PayPal</p>
                    </form>
                </div>
            </div>

            @include('pages.checkout.partials.order-summary')
        </div>
    </div>

</x-layouts.app>
