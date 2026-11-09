<x-layouts.dashboard title="General Settings - Mono LMS" active="settings" userType="admin">

    <div class="max-w-4xl space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-6 shadow-xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Platform Settings</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Configure site branding, commerce defaults, contact channels, and system features.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6 text-xs">
            @csrf

            <!-- 1. General & Branding -->
            <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>General & Branding</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Platform Name</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Timezone</label>
                        <select name="timezone" class="w-full px-3 py-2.5 border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                            <option value="UTC" {{ $settings['timezone'] === 'UTC' ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                            <option value="America/New_York" {{ $settings['timezone'] === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                            <option value="Europe/London" {{ $settings['timezone'] === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT/BST)</option>
                            <option value="Asia/Dhaka" {{ $settings['timezone'] === 'Asia/Dhaka' ? 'selected' : '' }}>Asia/Dhaka (BST)</option>
                            <option value="Asia/Dubai" {{ $settings['timezone'] === 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                        </select>
                    </div>
                </div>

                <!-- Branding Media (Logo & Favicon) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <x-media-selector
                        name="site_logo"
                        :value="$settings['site_logo']"
                        label="Platform Logo"
                        shape="wide"
                        :defaultUrl="asset('images/logo.png')"
                    />
                    <x-media-selector
                        name="site_favicon"
                        :value="$settings['site_favicon']"
                        label="Platform Favicon"
                        shape="square"
                        :defaultUrl="asset('favicon.ico')"
                    />
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Platform Tagline / Short Description</label>
                    <input type="text" name="site_description" value="{{ $settings['site_description'] }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Footer Copyright Line</label>
                    <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                </div>
            </div>

            <!-- 2. Commerce & Currency -->
            <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Commerce & Currency</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Default Currency Code</label>
                        <input type="text" name="currency_code" value="{{ $settings['currency_code'] }}" class="w-full uppercase font-mono px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] }}" class="w-full font-bold px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                </div>
            </div>

            <!-- 3. Contact Details -->
            <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Public Contact Channels</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Support Email</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Contact Phone</label>
                        <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                </div>
            </div>

            <!-- 4. Module & Feature Flags -->
            <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    <span>Feature Modules</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-none cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="membership_enabled" value="1" {{ $settings['membership_enabled'] ? 'checked' : '' }} class="h-4 w-4 text-[#344c3d] rounded-none">
                        <div>
                            <span class="font-bold text-slate-800 block">Membership Subscriptions</span>
                            <span class="text-[11px] text-slate-400">Recurring unlimited access plans</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-none cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="blog_enabled" value="1" {{ $settings['blog_enabled'] ? 'checked' : '' }} class="h-4 w-4 text-[#344c3d] rounded-none">
                        <div>
                            <span class="font-bold text-slate-800 block">Blog & Knowledge Base</span>
                            <span class="text-[11px] text-slate-400">Public articles and news updates</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-none cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="certificates_enabled" value="1" {{ $settings['certificates_enabled'] ? 'checked' : '' }} class="h-4 w-4 text-[#344c3d] rounded-none">
                        <div>
                            <span class="font-bold text-slate-800 block">Accredited Certificates</span>
                            <span class="text-[11px] text-slate-400">Generate completion diplomas</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-none cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="forums_enabled" value="1" {{ $settings['forums_enabled'] ? 'checked' : '' }} class="h-4 w-4 text-[#344c3d] rounded-none">
                        <div>
                            <span class="font-bold text-slate-800 block">Community Forums</span>
                            <span class="text-[11px] text-slate-400">Student and mentor discussions</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 5. SEO & Meta Tags -->
            <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Search Engine Optimization (SEO)</span>
                </h3>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Global Meta Title</label>
                    <input type="text" name="seo_meta_title" value="{{ $settings['seo_meta_title'] }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Global Meta Description</label>
                    <textarea name="seo_meta_description" rows="2" class="w-full px-3.5 py-2 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">{{ $settings['seo_meta_description'] }}</textarea>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit" class="action-btn action-btn-primary h-10 px-6 text-xs font-semibold uppercase tracking-wider">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</x-layouts.dashboard>
