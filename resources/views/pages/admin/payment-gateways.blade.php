<x-layouts.dashboard title="Payment Gateways - Mono LMS" active="payment-gateways" userType="admin">
    <div class="space-y-6 max-w-7xl">
        <!-- Top Title Bar & Breadcrumbs -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-[#0d0d0d] border border-slate-200 dark:border-[#1f1f1f] rounded-[6px] p-5 sm:p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-neutral-400 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.settings.index') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Settings</a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-neutral-200 font-medium">Payment Gateways</span>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Payment Gateways</h1>
                    @php
                        $activeCount = collect($gateways)->where('enabled', true)->count();
                    @endphp
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-[4px] bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ $activeCount }} of {{ count($gateways) }} Active
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-neutral-400 mt-1">Configure checkout payment processors, API credentials, webhooks, and offline bank transfer options.</p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.orders.index') }}" class="action-btn action-btn-ghost sm flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    <span>View Orders</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="action-btn action-btn-ghost sm flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>General Settings</span>
                </a>
            </div>
        </div>

        <!-- Universal Webhook Endpoint Helper Banner -->
        <div class="bg-slate-50 dark:bg-[#121212] border border-slate-200 dark:border-[#222222] rounded-[6px] p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-[6px] bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] dark:text-[#86efac] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">Universal Webhook Listener URL</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-neutral-400 mt-0.5">Point your Stripe and PayPal webhook notifications to this URL for automatic order fulfillment and enrollment provisioning.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 self-start md:self-auto w-full md:w-auto">
                <div class="flex-1 md:flex-none flex items-center gap-2 bg-white dark:bg-[#0a0a0a] border border-slate-200 dark:border-[#262626] rounded-[4px] px-3 py-1.5">
                    <span class="font-mono text-xs text-slate-700 dark:text-neutral-200 select-all truncate max-w-[260px] sm:max-w-xs" id="webhook-url-text">{{ url('/api/v1/payments/webhook') }}</span>
                    <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('webhook-url-text').innerText); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy', 2000)" class="text-[10px] font-bold uppercase tracking-wider text-[var(--theme-primary,#344c3d)] dark:text-[#86efac] hover:underline cursor-pointer shrink-0">
                        Copy
                    </button>
                </div>
            </div>
        </div>

        <!-- Gateways Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <!-- 1. Stripe Gateway Card -->
            <div class="bg-white dark:bg-[#0d0d0d] border border-slate-200 dark:border-[#1f1f1f] rounded-[6px] p-6 shadow-xs space-y-5">
                <!-- Card Header with Brand Logo -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-[#1f1f1f] gap-3">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="h-11 w-28 px-3 py-1.5 flex items-center justify-center bg-white dark:bg-[#181818] border border-slate-200 dark:border-[#262626] rounded-[6px] shadow-2xs shrink-0">
                            <img src="{{ asset('images/gateways/stripe.svg') }}" alt="Stripe" class="max-h-6 max-w-full object-contain">
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Stripe Payments</h3>
                                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-[4px] {{ $gateways['stripe']['mode'] === 'live' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400' }}">
                                    {{ strtoupper($gateways['stripe']['mode']) }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 dark:text-neutral-500 truncate mt-0.5">Cards, Apple Pay, Google Pay, iDEAL & Klarna</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] text-[10px] font-bold uppercase tracking-[0.12em] border {{ $gateways['stripe']['enabled'] ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/40' : 'bg-slate-100 dark:bg-[#181818] text-slate-500 dark:text-neutral-500 border-slate-200 dark:border-[#262626]' }}">
                            {{ $gateways['stripe']['enabled'] ? 'Active' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-4 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="stripe">

                    <!-- Enable Toggle Switch -->
                    <div class="flex items-center justify-between p-3 rounded-[6px] bg-slate-50 dark:bg-[#121212] border border-slate-200/80 dark:border-[#222222]">
                        <div>
                            <span class="font-semibold text-slate-900 dark:text-white block">Enable Stripe Checkout</span>
                            <span class="text-[11px] text-slate-500 dark:text-neutral-400">Process international card and digital wallet transactions.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="enabled" value="1" {{ $gateways['stripe']['enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[var(--theme-primary,#344c3d)]"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Mode Selector -->
                        <div class="space-y-1">
                            <label class="block font-semibold text-slate-700 dark:text-neutral-300">Environment Mode</label>
                            <select name="mode" class="h-9 w-full px-3 text-xs border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                                <option value="test" {{ $gateways['stripe']['mode'] === 'test' ? 'selected' : '' }}>Test / Sandbox</option>
                                <option value="live" {{ $gateways['stripe']['mode'] === 'live' ? 'selected' : '' }}>Live Production</option>
                            </select>
                        </div>

                        <!-- Publishable Key -->
                        <div class="space-y-1">
                            <label class="block font-semibold text-slate-700 dark:text-neutral-300">Publishable Key</label>
                            <input type="text" name="key" value="{{ $gateways['stripe']['key'] }}" placeholder="pk_test_..." class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                        </div>
                    </div>

                    <!-- Secret Key -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="block font-semibold text-slate-700 dark:text-neutral-300">Secret Key</label>
                            <a href="https://dashboard.stripe.com/apikeys" target="_blank" class="text-[10px] text-indigo-600 dark:text-indigo-400 hover:underline">Stripe Dashboard &rarr;</a>
                        </div>
                        <input type="password" name="secret" value="{{ $gateways['stripe']['secret'] }}" placeholder="sk_test_..." class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                    </div>

                    <!-- Webhook Signing Secret -->
                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-neutral-300">Webhook Signing Secret</label>
                        <input type="text" name="webhook" value="{{ $gateways['stripe']['webhook'] }}" placeholder="whsec_..." class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="action-btn action-btn-primary w-full h-10 text-xs font-semibold uppercase tracking-wider">
                            Save Stripe Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. PayPal Gateway Card -->
            <div class="bg-white dark:bg-[#0d0d0d] border border-slate-200 dark:border-[#1f1f1f] rounded-[6px] p-6 shadow-xs space-y-5">
                <!-- Card Header with Brand Logo -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-[#1f1f1f] gap-3">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="h-11 w-28 px-3 py-1.5 flex items-center justify-center bg-white dark:bg-[#181818] border border-slate-200 dark:border-[#262626] rounded-[6px] shadow-2xs shrink-0">
                            <img src="{{ asset('images/gateways/paypal.svg') }}" alt="PayPal" class="max-h-6 max-w-full object-contain">
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">PayPal Express</h3>
                                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-[4px] {{ $gateways['paypal']['mode'] === 'live' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400' }}">
                                    {{ strtoupper($gateways['paypal']['mode']) }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 dark:text-neutral-500 truncate mt-0.5">PayPal wallet, Venmo & Pay in 4 installments</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] text-[10px] font-bold uppercase tracking-[0.12em] border {{ $gateways['paypal']['enabled'] ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/40' : 'bg-slate-100 dark:bg-[#181818] text-slate-500 dark:text-neutral-500 border-slate-200 dark:border-[#262626]' }}">
                            {{ $gateways['paypal']['enabled'] ? 'Active' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-4 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="paypal">

                    <!-- Enable Toggle Switch -->
                    <div class="flex items-center justify-between p-3 rounded-[6px] bg-slate-50 dark:bg-[#121212] border border-slate-200/80 dark:border-[#222222]">
                        <div>
                            <span class="font-semibold text-slate-900 dark:text-white block">Enable PayPal Checkout</span>
                            <span class="text-[11px] text-slate-500 dark:text-neutral-400">Accept one-touch payments via standard PayPal buttons.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="enabled" value="1" {{ $gateways['paypal']['enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[var(--theme-primary,#344c3d)]"></div>
                        </label>
                    </div>

                    <!-- Mode Selector -->
                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-neutral-300">Environment Mode</label>
                        <select name="mode" class="h-9 w-full px-3 text-xs border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                            <option value="sandbox" {{ $gateways['paypal']['mode'] === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                            <option value="live" {{ $gateways['paypal']['mode'] === 'live' ? 'selected' : '' }}>Live Production</option>
                        </select>
                    </div>

                    <!-- Client ID -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="block font-semibold text-slate-700 dark:text-neutral-300">Client ID</label>
                            <a href="https://developer.paypal.com/dashboard/applications" target="_blank" class="text-[10px] text-blue-600 dark:text-blue-400 hover:underline">PayPal Developer &rarr;</a>
                        </div>
                        <input type="text" name="client_id" value="{{ $gateways['paypal']['client_id'] }}" placeholder="PayPal REST Client ID" class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                    </div>

                    <!-- Client Secret -->
                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-neutral-300">Client Secret</label>
                        <input type="password" name="secret" value="{{ $gateways['paypal']['secret'] }}" placeholder="••••••••" class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="action-btn action-btn-primary w-full h-10 text-xs font-semibold uppercase tracking-wider">
                            Save PayPal Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Razorpay Gateway Card -->
            <div class="bg-white dark:bg-[#0d0d0d] border border-slate-200 dark:border-[#1f1f1f] rounded-[6px] p-6 shadow-xs space-y-5">
                <!-- Card Header with Brand Logo -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-[#1f1f1f] gap-3">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="h-11 w-28 px-3 py-1.5 flex items-center justify-center bg-white dark:bg-[#181818] border border-slate-200 dark:border-[#262626] rounded-[6px] shadow-2xs shrink-0">
                            <img src="{{ asset('images/gateways/razorpay.svg') }}" alt="Razorpay" class="max-h-6 max-w-full object-contain">
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Razorpay Payments</h3>
                            <p class="text-[11px] text-slate-400 dark:text-neutral-500 truncate mt-0.5">UPI, NetBanking, Cards across India and South Asia</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] text-[10px] font-bold uppercase tracking-[0.12em] border {{ $gateways['razorpay']['enabled'] ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/40' : 'bg-slate-100 dark:bg-[#181818] text-slate-500 dark:text-neutral-500 border-slate-200 dark:border-[#262626]' }}">
                            {{ $gateways['razorpay']['enabled'] ? 'Active' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-4 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="razorpay">

                    <!-- Enable Toggle Switch -->
                    <div class="flex items-center justify-between p-3 rounded-[6px] bg-slate-50 dark:bg-[#121212] border border-slate-200/80 dark:border-[#222222]">
                        <div>
                            <span class="font-semibold text-slate-900 dark:text-white block">Enable Razorpay Checkout</span>
                            <span class="text-[11px] text-slate-500 dark:text-neutral-400">Accept Indian Rupee (INR) via UPI QR, GPay & NetBanking.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="enabled" value="1" {{ $gateways['razorpay']['enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[var(--theme-primary,#344c3d)]"></div>
                        </label>
                    </div>

                    <!-- Key ID -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="block font-semibold text-slate-700 dark:text-neutral-300">Key ID</label>
                            <a href="https://dashboard.razorpay.com/#/app/keys" target="_blank" class="text-[10px] text-sky-600 dark:text-sky-400 hover:underline">Razorpay Dashboard &rarr;</a>
                        </div>
                        <input type="text" name="key_id" value="{{ $gateways['razorpay']['key_id'] }}" placeholder="rzp_test_..." class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                    </div>

                    <!-- Key Secret -->
                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-neutral-300">Key Secret</label>
                        <input type="password" name="key_secret" value="{{ $gateways['razorpay']['key_secret'] }}" placeholder="••••••••" class="h-9 w-full font-mono text-[11px] px-3 border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="action-btn action-btn-primary w-full h-10 text-xs font-semibold uppercase tracking-wider">
                            Save Razorpay Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- 4. Direct Bank Transfer / Offline Wire -->
            <div class="bg-white dark:bg-[#0d0d0d] border border-slate-200 dark:border-[#1f1f1f] rounded-[6px] p-6 shadow-xs space-y-5">
                <!-- Card Header with Brand Logo -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-[#1f1f1f] gap-3">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="h-11 w-28 px-3 py-1.5 flex items-center justify-center bg-white dark:bg-[#181818] border border-slate-200 dark:border-[#262626] rounded-[6px] shadow-2xs shrink-0">
                            <img src="{{ asset('images/gateways/bank-transfer.svg') }}" alt="Bank Transfer" class="max-h-6 max-w-full object-contain">
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Direct Bank Wire</h3>
                            <p class="text-[11px] text-slate-400 dark:text-neutral-500 truncate mt-0.5">Manual wire transfer, ACH, SEPA & admin receipt verification</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] text-[10px] font-bold uppercase tracking-[0.12em] border {{ $gateways['offline']['enabled'] ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/40' : 'bg-slate-100 dark:bg-[#181818] text-slate-500 dark:text-neutral-500 border-slate-200 dark:border-[#262626]' }}">
                            {{ $gateways['offline']['enabled'] ? 'Active' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('admin.payment-gateways.update') }}" class="space-y-4 text-xs">
                    @csrf
                    <input type="hidden" name="gateway" value="offline">

                    <!-- Enable Toggle Switch -->
                    <div class="flex items-center justify-between p-3 rounded-[6px] bg-slate-50 dark:bg-[#121212] border border-slate-200/80 dark:border-[#222222]">
                        <div>
                            <span class="font-semibold text-slate-900 dark:text-white block">Enable Offline Wire Option</span>
                            <span class="text-[11px] text-slate-500 dark:text-neutral-400">Allows students to submit payment proof for manual order approval.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="enabled" value="1" {{ $gateways['offline']['enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[var(--theme-primary,#344c3d)]"></div>
                        </label>
                    </div>

                    <!-- Instructions Textarea -->
                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-neutral-300">Wire Instructions shown to Students at Checkout</label>
                        <textarea name="instructions" rows="5" placeholder="Bank: Your Bank Name&#10;Account Name: Organization LLC&#10;IBAN / Account Number: ...&#10;SWIFT / BIC: ...&#10;Reference: Order #{order_id}" class="w-full font-mono text-[11px] px-3 py-2.5 border border-slate-200 dark:border-[#262626] rounded-[4px] bg-white dark:bg-[#121212] text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)] leading-relaxed">{{ $gateways['offline']['instructions'] }}</textarea>
                        <p class="text-[10px] text-slate-400 dark:text-neutral-500">Provide bank account details, beneficiary name, and instructions on how students should reference their order ID.</p>
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
