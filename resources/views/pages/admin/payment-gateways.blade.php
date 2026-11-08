<x-layouts.dashboard title="Payment Gateways - Mono LMS" active="payment-gateways" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Payment Gateways</h1>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Multi-Gateway Enabled
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Configure checkout payment processors, API credentials, webhooks, and offline bank transfer options.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 1. Stripe Gateway Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center text-sm border border-indigo-100">
                            Stripe
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Stripe Payments</h3>
                            <p class="text-[11px] text-slate-400">Cards, Apple Pay, Google Pay, iDEAL</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $gateways['stripe']['enabled'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $gateways['stripe']['enabled'] ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="stripe">

                    <div class="flex items-center justify-between py-1">
                        <label class="font-semibold text-slate-700">Enable Stripe Gateway</label>
                        <input type="checkbox" name="enabled" value="1" {{ $gateways['stripe']['enabled'] ? 'checked' : '' }} class="h-4 w-4 text-[#344c3d] rounded">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Environment Mode</label>
                            <select name="mode" class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white">
                                <option value="test" {{ $gateways['stripe']['mode'] === 'test' ? 'selected' : '' }}>Test / Sandbox</option>
                                <option value="live" {{ $gateways['stripe']['mode'] === 'live' ? 'selected' : '' }}>Live Production</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Publishable Key</label>
                            <input type="text" name="key" value="{{ $gateways['stripe']['key'] }}" class="w-full font-mono text-[11px] px-3 py-2 border border-slate-200 rounded-xl">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Secret Key</label>
                        <input type="password" name="secret" value="{{ $gateways['stripe']['secret'] }}" class="w-full font-mono text-[11px] px-3 py-2 border border-slate-200 rounded-xl">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Webhook Signing Secret</label>
                        <input type="text" name="webhook" value="{{ $gateways['stripe']['webhook'] }}" class="w-full font-mono text-[11px] px-3 py-2 border border-slate-200 rounded-xl">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-[#344c3d] text-white font-semibold rounded-xl hover:bg-[#283a2f] shadow-xs transition-colors">
                            Save Stripe Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. PayPal Gateway Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-sm border border-blue-100">
                            PayPal
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">PayPal Express Checkout</h3>
                            <p class="text-[11px] text-slate-400">PayPal wallet & Pay in 4 installments</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $gateways['paypal']['enabled'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $gateways['paypal']['enabled'] ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="paypal">

                    <div class="flex items-center justify-between py-1">
                        <label class="font-semibold text-slate-700">Enable PayPal Gateway</label>
                        <input type="checkbox" name="enabled" value="1" {{ $gateways['paypal']['enabled'] ? 'checked' : '' }} class="h-4 w-4 text-[#344c3d] rounded">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Environment Mode</label>
                        <select name="mode" class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white">
                            <option value="sandbox" {{ $gateways['paypal']['mode'] === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                            <option value="live" {{ $gateways['paypal']['mode'] === 'live' ? 'selected' : '' }}>Live Production</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Client ID</label>
                        <input type="text" name="client_id" value="{{ $gateways['paypal']['client_id'] }}" class="w-full font-mono text-[11px] px-3 py-2 border border-slate-200 rounded-xl">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Client Secret</label>
                        <input type="password" name="secret" value="{{ $gateways['paypal']['secret'] }}" class="w-full font-mono text-[11px] px-3 py-2 border border-slate-200 rounded-xl">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-[#344c3d] text-white font-semibold rounded-xl hover:bg-[#283a2f] shadow-xs transition-colors">
                            Save PayPal Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Razorpay Gateway Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 font-bold flex items-center justify-center text-sm border border-sky-100">
                            Razorpay
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Razorpay Gateway</h3>
                            <p class="text-[11px] text-slate-400">UPI, NetBanking, Domestic Wallets</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $gateways['razorpay']['enabled'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $gateways['razorpay']['enabled'] ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="razorpay">

                    <div class="flex items-center justify-between py-1">
                        <label class="font-semibold text-slate-700">Enable Razorpay Gateway</label>
                        <input type="checkbox" name="enabled" value="1" {{ $gateways['razorpay']['enabled'] ? 'checked' : '' }} class="h-4 w-4 text-[#344c3d] rounded">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Key ID</label>
                        <input type="text" name="key_id" value="{{ $gateways['razorpay']['key_id'] }}" placeholder="rzp_test_..." class="w-full font-mono text-[11px] px-3 py-2 border border-slate-200 rounded-xl">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Key Secret</label>
                        <input type="password" name="key_secret" value="{{ $gateways['razorpay']['key_secret'] }}" placeholder="••••••••" class="w-full font-mono text-[11px] px-3 py-2 border border-slate-200 rounded-xl">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-[#344c3d] text-white font-semibold rounded-xl hover:bg-[#283a2f] shadow-xs transition-colors">
                            Save Razorpay Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- 4. Offline / Bank Wire Gateway Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 font-bold flex items-center justify-center text-sm border border-amber-100">
                            Bank
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Direct Bank Transfer</h3>
                            <p class="text-[11px] text-slate-400">Offline wire transfer with admin approval</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $gateways['offline']['enabled'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $gateways['offline']['enabled'] ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="offline">

                    <div class="flex items-center justify-between py-1">
                        <label class="font-semibold text-slate-700">Enable Bank Wire Option</label>
                        <input type="checkbox" name="enabled" value="1" {{ $gateways['offline']['enabled'] ? 'checked' : '' }} class="h-4 w-4 text-[#344c3d] rounded">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Wire Instructions shown to Students</label>
                        <textarea name="instructions" rows="4" class="w-full font-mono text-[11px] px-3 py-2 border border-slate-200 rounded-xl">{{ $gateways['offline']['instructions'] }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-[#344c3d] text-white font-semibold rounded-xl hover:bg-[#283a2f] shadow-xs transition-colors">
                            Save Bank Transfer Instructions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
