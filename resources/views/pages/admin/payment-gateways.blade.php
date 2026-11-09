<x-layouts.dashboard title="Payment Gateways - Mono LMS" active="payment-gateways" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Payment Gateways</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-none bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Multi-Gateway Enabled
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Configure checkout payment processors, API credentials, webhooks, and offline bank transfer options.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 1. Stripe Gateway Card -->
            <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-none bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center text-xs uppercase tracking-wider border border-indigo-200">
                            Stripe
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Stripe Payments</h3>
                            <p class="text-[11px] text-slate-400">Cards, Apple Pay, Google Pay, iDEAL</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold uppercase tracking-[0.12em] border {{ $gateways['stripe']['enabled'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                        {{ $gateways['stripe']['enabled'] ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="stripe">

                    <div class="flex items-center justify-between py-1">
                        <label class="font-semibold text-slate-700">Enable Stripe Gateway</label>
                        <input type="checkbox" name="enabled" value="1" {{ $gateways['stripe']['enabled'] ? 'checked' : '' }} class="control">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Environment Mode</label>
                            <select name="mode" data-mselect data-mselect-no-search class="h-9 w-full px-3 text-xs border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                                <option value="test" {{ $gateways['stripe']['mode'] === 'test' ? 'selected' : '' }}>Test / Sandbox</option>
                                <option value="live" {{ $gateways['stripe']['mode'] === 'live' ? 'selected' : '' }}>Live Production</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Publishable Key</label>
                            <input type="text" name="key" value="{{ $gateways['stripe']['key'] }}" class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Secret Key</label>
                        <input type="password" name="secret" value="{{ $gateways['stripe']['secret'] }}" class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Webhook Signing Secret</label>
                        <input type="text" name="webhook" value="{{ $gateways['stripe']['webhook'] }}" class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="action-btn action-btn-primary w-full h-10 text-xs font-semibold uppercase tracking-wider">
                            Save Stripe Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. PayPal Gateway Card -->
            <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-none bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-xs uppercase tracking-wider border border-blue-200">
                            PayPal
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">PayPal Express Checkout</h3>
                            <p class="text-[11px] text-slate-400">PayPal wallet & Pay in 4 installments</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold uppercase tracking-[0.12em] border {{ $gateways['paypal']['enabled'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                        {{ $gateways['paypal']['enabled'] ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="paypal">

                    <div class="flex items-center justify-between py-1">
                        <label class="font-semibold text-slate-700">Enable PayPal Gateway</label>
                        <input type="checkbox" name="enabled" value="1" {{ $gateways['paypal']['enabled'] ? 'checked' : '' }} class="control">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Environment Mode</label>
                        <select name="mode" data-mselect data-mselect-no-search class="h-9 w-full px-3 text-xs border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                            <option value="sandbox" {{ $gateways['paypal']['mode'] === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                            <option value="live" {{ $gateways['paypal']['mode'] === 'live' ? 'selected' : '' }}>Live Production</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Client ID</label>
                        <input type="text" name="client_id" value="{{ $gateways['paypal']['client_id'] }}" class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Client Secret</label>
                        <input type="password" name="secret" value="{{ $gateways['paypal']['secret'] }}" class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="action-btn action-btn-primary w-full h-10 text-xs font-semibold uppercase tracking-wider">
                            Save PayPal Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Razorpay Gateway Card -->
            <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-none bg-sky-50 text-sky-600 font-bold flex items-center justify-center text-xs uppercase tracking-wider border border-sky-200">
                            Razorpay
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Razorpay Gateway</h3>
                            <p class="text-[11px] text-slate-400">UPI, NetBanking, Domestic Wallets</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold uppercase tracking-[0.12em] border {{ $gateways['razorpay']['enabled'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                        {{ $gateways['razorpay']['enabled'] ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="razorpay">

                    <div class="flex items-center justify-between py-1">
                        <label class="font-semibold text-slate-700">Enable Razorpay Gateway</label>
                        <input type="checkbox" name="enabled" value="1" {{ $gateways['razorpay']['enabled'] ? 'checked' : '' }} class="control">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Key ID</label>
                        <input type="text" name="key_id" value="{{ $gateways['razorpay']['key_id'] }}" placeholder="rzp_test_..." class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Key Secret</label>
                        <input type="password" name="key_secret" value="{{ $gateways['razorpay']['key_secret'] }}" placeholder="••••••••" class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="action-btn action-btn-primary w-full h-10 text-xs font-semibold uppercase tracking-wider">
                            Save Razorpay Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- 4. Offline / Bank Wire Gateway Card -->
            <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-none bg-amber-50 text-amber-600 font-bold flex items-center justify-center text-xs uppercase tracking-wider border border-amber-200">
                            Bank
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Direct Bank Transfer</h3>
                            <p class="text-[11px] text-slate-400">Offline wire transfer with admin approval</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold uppercase tracking-[0.12em] border {{ $gateways['offline']['enabled'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                        {{ $gateways['offline']['enabled'] ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="offline">

                    <div class="flex items-center justify-between py-1">
                        <label class="font-semibold text-slate-700">Enable Bank Wire Option</label>
                        <input type="checkbox" name="enabled" value="1" {{ $gateways['offline']['enabled'] ? 'checked' : '' }} class="control">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Wire Instructions shown to Students</label>
                        <textarea name="instructions" rows="4" class="w-full font-mono text-[11px] px-3 py-2 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">{{ $gateways['offline']['instructions'] }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="action-btn action-btn-primary w-full h-10 text-xs font-semibold uppercase tracking-wider">
                            Save Bank Transfer Instructions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
