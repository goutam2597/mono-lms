<x-layouts.dashboard title="General Settings - Mono LMS" active="settings" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 38px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn:disabled { opacity: .5; cursor: wait; }
</style>

<div class="space-y-6">
    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 bg-white p-6 shadow-xs">
        <div>
            <h1 class="text-2xl font-medium tracking-tight text-neutral-900">Platform Settings</h1>
            <p class="text-xs text-neutral-500 mt-1">Configure site branding, commerce defaults, contact channels, feature switches, and SEO meta tags.</p>
        </div>
        <button type="submit" form="settings-form" class="action-btn h-10 px-6">
            <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save All Changes
        </button>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" id="settings-form" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
            <!-- Left Column: Branding, Commerce, Contact (7 cols) -->
            <div class="xl:col-span-7 space-y-6">
                <!-- 1. General & Branding -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">General & Branding</h2>
                            <p class="text-[11px] text-neutral-400">Core platform identity, logo assets, and global timezone.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Platform Name *</label>
                                <input type="text" name="site_name" value="{{ $settings['site_name'] }}" required
                                       class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Platform Timezone *</label>
                                <select name="timezone" data-mselect class="h-10 w-full cursor-pointer border border-neutral-300 bg-white px-3 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                                    @php
                                        $commonTimezones = [
                                            'UTC' => 'UTC (Coordinated Universal Time)',
                                            'America/New_York' => 'America/New York (EST/EDT)',
                                            'America/Chicago' => 'America/Chicago (CST/CDT)',
                                            'America/Los_Angeles' => 'America/Los Angeles (PST/PDT)',
                                            'Europe/London' => 'Europe/London (GMT/BST)',
                                            'Europe/Paris' => 'Europe/Paris (CET/CEST)',
                                            'Europe/Berlin' => 'Europe/Berlin (CET/CEST)',
                                            'Asia/Dubai' => 'Asia/Dubai (GST +04)',
                                            'Asia/Dhaka' => 'Asia/Dhaka (BST +06)',
                                            'Asia/Singapore' => 'Asia/Singapore (SGT +08)',
                                            'Asia/Tokyo' => 'Asia/Tokyo (JST +09)',
                                            'Australia/Sydney' => 'Australia/Sydney (AEST)',
                                        ];
                                    @endphp
                                    @foreach($commonTimezones as $tzCode => $tzLabel)
                                        <option value="{{ $tzCode }}" @selected($settings['timezone'] === $tzCode)>{{ $tzLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Branding Media (Logo & Favicon) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-neutral-100 pt-4">
                            <div>
                                <x-media-selector
                                    name="site_logo"
                                    :value="$settings['site_logo']"
                                    label="Platform Logo"
                                    hint="Displayed in header and emails"
                                    shape="wide"
                                    :defaultUrl="asset('images/logo.png')"
                                />
                            </div>
                            <div>
                                <x-media-selector
                                    name="site_favicon"
                                    :value="$settings['site_favicon']"
                                    label="Platform Favicon"
                                    hint="Browser tab icon (ICO or PNG)"
                                    shape="square"
                                    :defaultUrl="asset('favicon.ico')"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Platform Tagline / Short Description</label>
                            <input type="text" name="site_description" value="{{ $settings['site_description'] }}"
                                   placeholder="e.g. Modern, accessible Learning Management System"
                                   class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>

                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Footer Copyright Notice</label>
                            <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] }}"
                                   placeholder="e.g. © 2026 Mono LMS. All rights reserved."
                                   class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- 2. Commerce & Regional Settings -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Commerce & Currency</h2>
                            <p class="text-[11px] text-neutral-400">Pricing format across course catalog, orders, and receipts.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Default Currency Code *</label>
                            <input type="text" name="currency_code" id="input-currency-code" value="{{ $settings['currency_code'] }}" maxlength="5"
                                   class="h-10 w-full uppercase font-mono border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            <p class="text-[11px] text-neutral-400 mt-1">ISO 3-letter currency code (e.g. USD, EUR, GBP, CAD).</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Currency Symbol *</label>
                            <input type="text" name="currency_symbol" id="input-currency-symbol" value="{{ $settings['currency_symbol'] }}" maxlength="5"
                                   class="h-10 w-full font-bold border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            <p class="text-[11px] text-neutral-400 mt-1">Prefix or display symbol (e.g. $, €, £, ¥).</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-3 border border-neutral-100 bg-neutral-50 p-3.5">
                        <span class="text-[11px] font-medium text-neutral-500">Sample Price Rendering:</span>
                        <span id="preview-price-sample" class="inline-flex items-center px-2.5 py-1 text-xs font-bold text-neutral-900 bg-white border border-neutral-200">
                            {{ $settings['currency_symbol'] }} 99.00 {{ $settings['currency_code'] }}
                        </span>
                    </div>
                </div>

                <!-- 3. Public Contact Channels -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Public Contact Channels</h2>
                            <p class="text-[11px] text-neutral-400">Displayed in footer, contact page, and transactional notifications.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Support Inbound Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                </span>
                                <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}"
                                       class="h-10 w-full pl-9 border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Contact Telephone</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}"
                                       class="h-10 w-full pl-9 border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Feature Modules, SEO, Save Action (5 cols) -->
            <div class="xl:col-span-5 space-y-6">
                <!-- 4. Feature Modules Switcher -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Feature Modules</h2>
                            <p class="text-[11px] text-neutral-400">Instantly enable or disable LMS subsystems.</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <!-- Membership Subscriptions -->
                        <label class="group flex items-start justify-between gap-3 p-3.5 border border-neutral-200 bg-white hover:border-neutral-900 transition-colors cursor-pointer select-none">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="membership_enabled" value="1" @checked($settings['membership_enabled'])
                                       class="control mt-0.5">
                                <div>
                                    <span class="font-bold text-xs text-neutral-900 block">Membership Subscriptions</span>
                                    <span class="text-[11px] text-neutral-400">Recurring unlimited subscription access plans</span>
                                </div>
                            </div>
                            <span class="inline-flex h-5 items-center px-2 text-[9px] font-bold uppercase tracking-wider {{ $settings['membership_enabled'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-neutral-100 text-neutral-500' }}">
                                {{ $settings['membership_enabled'] ? 'Active' : 'Disabled' }}
                            </span>
                        </label>

                        <!-- Blog & Articles -->
                        <label class="group flex items-start justify-between gap-3 p-3.5 border border-neutral-200 bg-white hover:border-neutral-900 transition-colors cursor-pointer select-none">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="blog_enabled" value="1" @checked($settings['blog_enabled'])
                                       class="control mt-0.5">
                                <div>
                                    <span class="font-bold text-xs text-neutral-900 block">Blog & Knowledge Base</span>
                                    <span class="text-[11px] text-neutral-400">Public articles, news updates, and tutorials</span>
                                </div>
                            </div>
                            <span class="inline-flex h-5 items-center px-2 text-[9px] font-bold uppercase tracking-wider {{ $settings['blog_enabled'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-neutral-100 text-neutral-500' }}">
                                {{ $settings['blog_enabled'] ? 'Active' : 'Disabled' }}
                            </span>
                        </label>

                        <!-- Certificates -->
                        <label class="group flex items-start justify-between gap-3 p-3.5 border border-neutral-200 bg-white hover:border-neutral-900 transition-colors cursor-pointer select-none">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="certificates_enabled" value="1" @checked($settings['certificates_enabled'])
                                       class="control mt-0.5">
                                <div>
                                    <span class="font-bold text-xs text-neutral-900 block">Accredited Certificates</span>
                                    <span class="text-[11px] text-neutral-400">Generate completion certificates & verification</span>
                                </div>
                            </div>
                            <span class="inline-flex h-5 items-center px-2 text-[9px] font-bold uppercase tracking-wider {{ $settings['certificates_enabled'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-neutral-100 text-neutral-500' }}">
                                {{ $settings['certificates_enabled'] ? 'Active' : 'Disabled' }}
                            </span>
                        </label>

                        <!-- Forums -->
                        <label class="group flex items-start justify-between gap-3 p-3.5 border border-neutral-200 bg-white hover:border-neutral-900 transition-colors cursor-pointer select-none">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="forums_enabled" value="1" @checked($settings['forums_enabled'])
                                       class="control mt-0.5">
                                <div>
                                    <span class="font-bold text-xs text-neutral-900 block">Community Forums</span>
                                    <span class="text-[11px] text-neutral-400">Student & instructor Q&A discussion channels</span>
                                </div>
                            </div>
                            <span class="inline-flex h-5 items-center px-2 text-[9px] font-bold uppercase tracking-wider {{ $settings['forums_enabled'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-neutral-100 text-neutral-500' }}">
                                {{ $settings['forums_enabled'] ? 'Active' : 'Disabled' }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- 5. Search Engine Optimization (SEO) -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Search Engine Optimization (SEO)</h2>
                            <p class="text-[11px] text-neutral-400">Global fallback tags for Google & social media cards.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">Global Meta Title</label>
                                <span class="text-[10px] text-neutral-400" id="seo-title-count"></span>
                            </div>
                            <input type="text" name="seo_meta_title" id="seo-title-input" value="{{ $settings['seo_meta_title'] }}" maxlength="100"
                                   class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">Global Meta Description</label>
                                <span class="text-[10px] text-neutral-400" id="seo-desc-count"></span>
                            </div>
                            <textarea name="seo_meta_description" id="seo-desc-input" rows="3" maxlength="300"
                                      class="w-full border border-neutral-300 p-3 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">{{ $settings['seo_meta_description'] }}</textarea>
                        </div>

                        <!-- Live SERP Preview -->
                        <div class="border border-neutral-200 bg-neutral-50/70 p-4">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 block mb-2">Live Search Snippet Preview</span>
                            <div class="space-y-1">
                                <p class="text-xs text-neutral-400 truncate">{{ url('/') }}</p>
                                <h4 id="serp-title-preview" class="text-sm font-medium text-blue-700 hover:underline cursor-pointer truncate">
                                    {{ $settings['seo_meta_title'] ?: $settings['site_name'] }}
                                </h4>
                                <p id="serp-desc-preview" class="text-xs text-neutral-600 line-clamp-2 leading-relaxed">
                                    {{ $settings['seo_meta_description'] ?: 'High performance enterprise LMS platform.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Action Card -->
                <div class="border border-neutral-200 bg-white p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-neutral-900 block">Ready to apply?</span>
                        <span class="text-[11px] text-neutral-400">Settings take effect platform-wide immediately.</span>
                    </div>
                    <button type="submit" form="settings-form" class="action-btn h-10 px-6">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    // Dynamic price preview update
    var codeInput = document.getElementById('input-currency-code');
    var symbolInput = document.getElementById('input-currency-symbol');
    var pricePreview = document.getElementById('preview-price-sample');

    function updatePrice() {
        if (! pricePreview) return;
        var sym = symbolInput ? symbolInput.value.trim() : '$';
        var code = codeInput ? codeInput.value.trim().toUpperCase() : 'USD';
        pricePreview.textContent = sym + ' 99.00 ' + code;
    }
    if (codeInput) codeInput.addEventListener('input', updatePrice);
    if (symbolInput) symbolInput.addEventListener('input', updatePrice);

    // Live SERP preview update
    var titleInput = document.getElementById('seo-title-input');
    var descInput = document.getElementById('seo-desc-input');
    var serpTitle = document.getElementById('serp-title-preview');
    var serpDesc = document.getElementById('serp-desc-preview');
    var titleCount = document.getElementById('seo-title-count');
    var descCount = document.getElementById('seo-desc-count');

    function updateSerp() {
        var t = titleInput ? titleInput.value.trim() : '';
        var d = descInput ? descInput.value.trim() : '';
        if (serpTitle) serpTitle.textContent = t || 'Mono LMS - Learning Management System';
        if (serpDesc) serpDesc.textContent = d || 'High performance enterprise LMS platform.';
        if (titleCount && titleInput) titleCount.textContent = titleInput.value.length + '/100';
        if (descCount && descInput) descCount.textContent = descInput.value.length + '/300';
    }
    if (titleInput) titleInput.addEventListener('input', updateSerp);
    if (descInput) descInput.addEventListener('input', updateSerp);
    updateSerp();
})();
</script>

</x-layouts.dashboard>
