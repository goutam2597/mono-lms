<x-layouts.app title="Manual / Bank Transfer - Mono LMS">

    <div class="max-w-6xl mx-auto px-4 sm:px-8 py-10">
        <a href="{{ route('student.orders') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] mb-5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to My Orders
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 items-start">
            <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#e7e7e7] flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-[#0d9488]/10 text-[#0d9488] flex items-center justify-center font-bold text-sm">🏦</span>
                    <div>
                        <h2 class="font-bold text-[#191919]">Bank Transfer / Manual Payment</h2>
                        <p class="text-[11px] text-gray-400">Send the amount, then submit your transaction ID below</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Account details & instructions from admin settings --}}
                    <div class="bg-[#f8fafb] border border-[#e7e7e7] rounded-xl p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#344c3d] mb-3">Payment Details</h3>
                        <div class="text-sm text-[#383838] leading-relaxed whitespace-pre-line font-medium">{{ $offlineInstructions }}</div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-800 leading-relaxed">
                        <strong>Steps:</strong>
                        <ol class="list-decimal ml-4 mt-1.5 space-y-1">
                            <li>Send exactly <strong>{{ __('app.common.currency') }}{{ number_format((float) $order->amount, 2) }}</strong> using the details above.</li>
                            <li>Use your <strong>order number {{ $order->order_number }}</strong> as the payment reference where possible.</li>
                            <li>Submit the transaction ID below — an administrator will verify it shortly.</li>
                        </ol>
                    </div>

                    <form action="{{ route('checkout.pay.initiate', [$order, 'offline']) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919]">Transaction ID / Reference *</label>
                            <input type="text" name="payment_reference" value="{{ old('payment_reference') }}" required minlength="4" placeholder="e.g. TrxID8H2K9D1X" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-3 text-sm font-mono focus:outline-none focus:border-[#344c3d]">
                            @error('payment_reference')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="w-full bg-[#344c3d] hover:bg-[#283a2f] text-white font-bold text-sm py-3.5 rounded-xl transition-colors">
                            Submit Transaction ID
                        </button>
                        <p class="text-center text-[11px] text-gray-400">Verification usually completes within a few hours. Your seat reservation is held while you submit.</p>
                    </form>
                </div>
            </div>

            @include('pages.checkout.partials.order-summary')
        </div>
    </div>

</x-layouts.app>
