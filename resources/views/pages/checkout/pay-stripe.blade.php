<x-layouts.app title="Pay with Stripe - Mono LMS">

    <div class="max-w-6xl mx-auto px-4 sm:px-8 py-10">
        <a href="{{ route('student.orders') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] mb-5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to My Orders
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 items-start">
            <!-- Stripe Method Card -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#e7e7e7] flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-[#635bff]/10 text-[#635bff] flex items-center justify-center font-bold text-sm">S</span>
                    <div>
                        <h2 class="font-bold text-[#191919]">Pay with Stripe</h2>
                        <p class="text-[11px] text-gray-400">Cards · Apple Pay · Google Pay</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    <div class="bg-[#f8fafb] border border-[#e7e7e7] rounded-xl p-4 text-sm text-[#5d5d5d] leading-relaxed">
                        Click the button below and you will be redirected to <strong>Stripe's secure hosted checkout</strong> to complete your card payment. After payment you are returned automatically and your enrollment is activated instantly.
                    </div>

                    <ul class="space-y-2 text-xs text-gray-500">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Encrypted PCI-compliant checkout</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Enrollment activated immediately after payment</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Reservation is held for the countdown shown on the right</li>
                    </ul>

                    <form action="{{ route('checkout.pay.initiate', [$order, 'stripe']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#635bff] hover:bg-[#4b47d6] text-white font-bold text-sm py-3.5 rounded-xl transition-colors">
                            Pay {{ __('app.common.currency') }}{{ number_format((float) $order->amount, 2) }} with Stripe
                        </button>
                        <p class="text-center text-[11px] text-gray-400 mt-2">Secure redirect · powered by Stripe</p>
                    </form>
                </div>
            </div>

            @include('pages.checkout.partials.order-summary')
        </div>
    </div>

</x-layouts.app>
