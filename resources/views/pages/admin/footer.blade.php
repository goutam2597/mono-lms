<x-layouts.dashboard title="Footer Editor - Mono LMS" active="footer" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 38px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn:disabled { opacity: .5; cursor: wait; }
</style>

<div class="space-y-6">
    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 bg-white p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-neutral-100 text-neutral-700">
                    Editor &bull; Storefront
                </span>
            </div>
            <h1 class="text-2xl font-medium tracking-tight text-neutral-900">Footer Editor</h1>
            <p class="text-xs text-neutral-500 mt-1">Configure payment methods strip, mobile app download links, footer copyright, about bio, and newsletter widget.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="h-10 px-4 border border-neutral-300 bg-white hover:bg-neutral-50 text-neutral-700 text-xs font-medium flex items-center gap-1.5 transition-colors">
                <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Live Site
            </a>
            <button type="submit" form="footer-form" class="action-btn h-10 px-6">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Save Footer Changes
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="underline text-emerald-900 hover:text-black">Check storefront &rarr;</a>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.footer.update') }}" id="footer-form" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
            <!-- Left Main Form Column (7 cols) -->
            <div class="xl:col-span-7 space-y-6">

                <!-- 1. Footer Payment Methods Strip -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-4 mb-5">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            </span>
                            <div>
                                <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Footer Payment Methods</h2>
                                <p class="text-[11px] text-neutral-400">Accepted card networks, digital wallets, and security certification logos.</p>
                            </div>
                        </div>
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="footer_payment_methods_enabled" value="1" @checked($settings['footer_payment_methods_enabled'] ?? true) class="control">
                            <span class="text-xs font-semibold text-neutral-800">Display in footer</span>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <x-media-selector
                            name="footer_payment_methods"
                            :value="$settings['footer_payment_methods']"
                            label="Payment Methods Banner Image"
                            hint="Upload a replacement image (PNG, SVG, or WebP) or choose from your Media Library"
                            shape="wide"
                            :defaultUrl="asset('images/payment-methods.png')"
                        />

                        <!-- Strip Display Width Controls -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Display Width Preset</label>
                                <select id="footer_payment_methods_width_preset" onchange="if(this.value){ document.getElementById('footer_payment_methods_width_input').value = this.value; }" class="h-10 w-full cursor-pointer border border-neutral-300 bg-white px-3 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                                    <option value="180px" @selected(($settings['footer_payment_methods_width'] ?? '340px') === '180px')>Compact (180px)</option>
                                    <option value="260px" @selected(($settings['footer_payment_methods_width'] ?? '340px') === '260px')>Medium (260px)</option>
                                    <option value="340px" @selected(($settings['footer_payment_methods_width'] ?? '340px') === '340px')>Standard (340px) &mdash; Default</option>
                                    <option value="420px" @selected(($settings['footer_payment_methods_width'] ?? '340px') === '420px')>Large (420px)</option>
                                    <option value="500px" @selected(($settings['footer_payment_methods_width'] ?? '340px') === '500px')>Extra Large (500px)</option>
                                    <option value="auto" @selected(($settings['footer_payment_methods_width'] ?? '340px') === 'auto')>Natural / Auto</option>
                                    <option value="" @selected(!in_array(($settings['footer_payment_methods_width'] ?? '340px'), ['180px', '260px', '340px', '420px', '500px', 'auto']))>Custom value</option>
                                </select>
                                <p class="text-[10px] text-neutral-400 mt-1">Select a preset to automatically populate the width value.</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Custom Strip Width</label>
                                <input type="text" id="footer_payment_methods_width_input" name="footer_payment_methods_width" value="{{ $settings['footer_payment_methods_width'] ?? '340px' }}" placeholder="340px" class="h-10 w-full border border-neutral-300 px-3.5 text-xs font-mono text-neutral-900 focus:border-neutral-900 focus:outline-none">
                                <p class="text-[10px] text-neutral-400 mt-1">Accepts px (e.g. 340px), %, rem, or auto.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Mobile App Store Links -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Mobile App Store Links</h2>
                            <p class="text-[11px] text-neutral-400">Direct download destination URLs for Apple iOS App Store and Google Android Play Store.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Apple App Store Card -->
                        <div class="p-4 border border-neutral-200 bg-neutral-50/70 rounded-xs space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-8 px-2 bg-black rounded flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/app-store-badge.svg') }}" alt="App Store" class="h-6 w-auto" />
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-neutral-900 block">Apple App Store (iOS)</span>
                                        <span class="text-[10px] text-neutral-400">iPhone, iPad &amp; Mac application</span>
                                    </div>
                                </div>
                                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" name="app_store_enabled" value="1" @checked($settings['app_store_enabled'] ?? true) class="control">
                                    <span class="text-xs font-medium text-neutral-700">Display Badge</span>
                                </label>
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">App Store Destination Link</label>
                                <div class="flex gap-2">
                                    <input type="url" name="app_store_url" value="{{ $settings['app_store_url'] ?? '' }}"
                                           placeholder="https://apps.apple.com/app/your-app-name/id123456789"
                                           class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                                    @if(!empty($settings['app_store_url']) && str_starts_with($settings['app_store_url'], 'http'))
                                        <a href="{{ $settings['app_store_url'] }}" target="_blank" rel="noopener noreferrer" class="h-10 px-3.5 border border-neutral-300 bg-white hover:bg-neutral-100 flex items-center justify-center text-neutral-700 text-xs shrink-0 transition-colors" title="Test App Store link in new tab">
                                            <svg class="w-4 h-4 mr-1 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            <span class="hidden sm:inline font-medium">Test</span>
                                        </a>
                                    @endif
                                </div>
                                <p class="text-[10px] text-neutral-400 mt-1">Visitors clicking the badge in the storefront footer will be directed to this link.</p>
                            </div>
                        </div>

                        <!-- Google Play Store Card -->
                        <div class="p-4 border border-neutral-200 bg-neutral-50/70 rounded-xs space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-8 px-2 bg-black rounded flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/google-play-badge.svg') }}" alt="Google Play" class="h-6 w-auto" />
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-neutral-900 block">Google Play Store (Android)</span>
                                        <span class="text-[10px] text-neutral-400">Android phone &amp; tablet application</span>
                                    </div>
                                </div>
                                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" name="play_store_enabled" value="1" @checked($settings['play_store_enabled'] ?? true) class="control">
                                    <span class="text-xs font-medium text-neutral-700">Display Badge</span>
                                </label>
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Google Play Store Destination Link</label>
                                <div class="flex gap-2">
                                    <input type="url" name="play_store_url" value="{{ $settings['play_store_url'] ?? '' }}"
                                           placeholder="https://play.google.com/store/apps/details?id=com.yourcompany.lms"
                                           class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                                    @if(!empty($settings['play_store_url']) && str_starts_with($settings['play_store_url'], 'http'))
                                        <a href="{{ $settings['play_store_url'] }}" target="_blank" rel="noopener noreferrer" class="h-10 px-3.5 border border-neutral-300 bg-white hover:bg-neutral-100 flex items-center justify-center text-neutral-700 text-xs shrink-0 transition-colors" title="Test Google Play link in new tab">
                                            <svg class="w-4 h-4 mr-1 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            <span class="hidden sm:inline font-medium">Test</span>
                                        </a>
                                    @endif
                                </div>
                                <p class="text-[10px] text-neutral-400 mt-1">Visitors clicking the badge in the storefront footer will be directed to this link.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Footer Branding, About Bio & Copyright -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Brand Identity &amp; Copyright</h2>
                            <p class="text-[11px] text-neutral-400">Footer bio text, optional white logo override, and bottom legal copyright.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Footer About Text / Bio</label>
                            <textarea name="footer_about_text" rows="3"
                                      placeholder="e.g. Empowering learners worldwide with professional, flexible online courses, certified degrees, and collaborative classrooms."
                                      class="w-full border border-neutral-300 p-3 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none leading-relaxed">{{ $settings['footer_about_text'] }}</textarea>
                            <p class="text-[10px] text-neutral-400 mt-1">Displayed in the first column under the storefront logo. Leave empty to use system default translation.</p>
                        </div>

                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Footer Copyright Notice</label>
                            <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] }}"
                                   placeholder="e.g. &copy; 2026 Mono LMS. All rights reserved."
                                   class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            <p class="text-[10px] text-neutral-400 mt-1">Displayed at the bottom-left bar of every public page.</p>
                        </div>
                    </div>
                </div>

                <!-- 4. Footer Columns & Newsletter -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Columns &amp; Newsletter</h2>
                            <p class="text-[11px] text-neutral-400">Column titles and newsletter subscription block.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Column 2 Heading</label>
                                <input type="text" name="footer_support_title" value="{{ $settings['footer_support_title'] }}" placeholder="Support"
                                       class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Column 3 Heading</label>
                                <input type="text" name="footer_pages_title" value="{{ $settings['footer_pages_title'] }}" placeholder="Pages"
                                       class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Column 4 Heading</label>
                                <input type="text" name="footer_useful_title" value="{{ $settings['footer_useful_title'] }}" placeholder="Useful Links"
                                       class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="p-4 border border-neutral-200 bg-neutral-50/70 rounded-xs space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-neutral-900">Newsletter Subscription Block</span>
                                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" name="footer_newsletter_enabled" value="1" @checked($settings['footer_newsletter_enabled']) class="control">
                                    <span class="text-xs font-medium text-neutral-700">Display Newsletter</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Newsletter Title</label>
                                    <input type="text" name="footer_newsletter_title" value="{{ $settings['footer_newsletter_title'] }}" placeholder="Newsletter"
                                           class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Subtitle / Hint</label>
                                    <input type="text" name="footer_newsletter_hint" value="{{ $settings['footer_newsletter_hint'] }}" placeholder="Stay informed on new courses"
                                           class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Visual Preview & Navigation (5 cols) -->
            <div class="xl:col-span-5 space-y-6">

                <!-- Visual Storefront Preview Card -->
                <div class="border border-neutral-200 bg-white p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-neutral-900">Live Visual Preview</span>
                        </div>
                        <span class="text-[11px] text-neutral-400">Storefront View</span>
                    </div>

                    <!-- Mini Footer Simulator -->
                    <div class="bg-[#08131e] text-white p-5 rounded-lg border border-gray-800 space-y-5 text-[11px]">
                        <!-- Brand & Badges -->
                        <div class="space-y-2.5">
                            <img src="{{ \App\Models\Setting::logoUrl(asset('images/logo-white.png')) }}" alt="Logo" class="h-6 w-auto object-contain" />
                            <p class="text-gray-400 text-[11px] leading-relaxed line-clamp-2">
                                {{ $settings['footer_about_text'] ?: 'Modern, accessible Learning Management System empowering learners worldwide.' }}
                            </p>
                            <!-- Badges -->
                            <div class="flex items-center gap-2 pt-1">
                                @if($settings['app_store_enabled'] && filled($settings['app_store_url']))
                                    <img src="{{ asset('images/app-store-badge.svg') }}" alt="App Store" class="h-7 w-auto opacity-90 hover:opacity-100 transition-opacity cursor-pointer" />
                                @endif
                                @if($settings['play_store_enabled'] && filled($settings['play_store_url']))
                                    <img src="{{ asset('images/google-play-badge.svg') }}" alt="Google Play" class="h-7 w-auto opacity-90 hover:opacity-100 transition-opacity cursor-pointer" />
                                @endif
                            </div>
                        </div>

                        <!-- Mini Columns Simulation -->
                        <div class="grid grid-cols-3 gap-2 pt-3 border-t border-gray-800 text-[10px] text-gray-400">
                            <div>
                                <span class="font-bold text-white block mb-1">{{ $settings['footer_support_title'] }}</span>
                                <div>Help Center</div>
                                <div>Contact</div>
                            </div>
                            <div>
                                <span class="font-bold text-white block mb-1">{{ $settings['footer_pages_title'] }}</span>
                                <div>Courses</div>
                                <div>Blog</div>
                            </div>
                            <div>
                                <span class="font-bold text-white block mb-1">{{ $settings['footer_useful_title'] }}</span>
                                <div>Instructor</div>
                                <div>Dashboard</div>
                            </div>
                        </div>

                        <!-- Bottom Strip Simulation -->
                        <div class="pt-4 border-t border-gray-800 space-y-2 text-[10px] text-gray-400">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <span>{{ $settings['footer_copyright'] }}</span>
                                <div class="flex items-center gap-2 text-[9px]">
                                    <span>Terms</span>
                                    <span>&bull;</span>
                                    <span>Privacy</span>
                                </div>
                            </div>

                            @if($settings['footer_payment_methods_enabled'])
                                <div class="pt-2 flex justify-center">
                                    <img src="{{ \App\Models\Setting::paymentMethodsUrl() }}" alt="Payment Methods" style="max-width: {{ $settings['footer_payment_methods_width'] ?: '260px' }}; width: 100%; height: auto;" class="opacity-90 max-h-7 object-contain" />
                                </div>
                            @endif
                        </div>
                    </div>

                    <p class="text-[11px] text-neutral-400 text-center">Preview dynamically renders current saved settings as seen by public learners.</p>
                </div>

                <!-- Site Editor Shortcuts -->
                <div class="border border-neutral-200 bg-white p-5 shadow-xs space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-900">Site Editor Navigation</h3>
                    <ul class="divide-y divide-neutral-100 text-xs">
                        <li class="py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                                <span class="font-semibold text-neutral-800">Header Editor</span>
                            </div>
                            <a href="{{ route('admin.header.index') }}" class="text-[var(--theme-primary,#344c3d)] hover:underline font-medium">Edit Header &rarr;</a>
                        </li>
                        <li class="py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                                <span class="font-semibold text-neutral-800">Home Page Editor</span>
                            </div>
                            <a href="{{ route('admin.homepage.index') }}" class="text-[var(--theme-primary,#344c3d)] hover:underline font-medium">Edit Homepage &rarr;</a>
                        </li>
                        <li class="py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                                <span class="font-semibold text-neutral-800">All Static Pages</span>
                            </div>
                            <a href="{{ route('admin.pages.index') }}" class="text-[var(--theme-primary,#344c3d)] hover:underline font-medium">Manage Pages &rarr;</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </form>
</div>

</x-layouts.dashboard>
