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
                <div class="border border-neutral-200 bg-white p-6 shadow-xs space-y-6">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-2">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Columns &amp; Navigation Links Organizer</h2>
                            <p class="text-[11px] text-neutral-400">Organize footer columns and links using drag &amp; drop. Add custom pages, platform portals, or external links.</p>
                        </div>
                    </div>

                    <!-- Hidden serialized JSON inputs -->
                    <input type="hidden" name="footer_navigation_columns" id="footer_navigation_columns_input" value="">
                    <input type="hidden" name="footer_bottom_links" id="footer_bottom_links_input" value="">

                    <!-- Legacy Heading Inputs for test compatibility -->
                    <div class="hidden">
                        <input type="text" name="footer_support_title" id="legacy_support_title" value="{{ $settings['footer_support_title'] }}">
                        <input type="text" name="footer_pages_title" id="legacy_pages_title" value="{{ $settings['footer_pages_title'] }}">
                        <input type="text" name="footer_useful_title" id="legacy_useful_title" value="{{ $settings['footer_useful_title'] }}">
                    </div>

                    <!-- Add Links Toolbar -->
                    <div class="bg-neutral-50 border border-neutral-200 p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">Quick Add Link to Column</span>
                            <span class="text-[11px] text-neutral-400">Target column:</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                            <div class="sm:col-span-4">
                                <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Target Column</label>
                                <select id="footer-target-column-select" class="h-8 w-full px-2.5 text-xs bg-white border border-neutral-300 text-neutral-800 focus:outline-none">
                                    <!-- Populated dynamically based on current columns -->
                                </select>
                            </div>
                            <div class="sm:col-span-5">
                                <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Select Custom Page</label>
                                <select id="footer-page-picker-select" class="h-8 w-full px-2.5 text-xs bg-white border border-neutral-300 text-neutral-800 focus:outline-none">
                                    <option value="">-- Choose custom or static page --</option>
                                    @foreach ($availablePages as $p)
                                        <option value="/{{ $p->slug }}" data-title="{{ $p->title }}">
                                            {{ $p->title }} (/{{ $p->slug }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-3">
                                <button type="button" id="btn-footer-add-page" class="h-8 w-full px-3 text-xs font-semibold uppercase tracking-wider bg-neutral-900 text-white hover:bg-neutral-800 transition-colors cursor-pointer">
                                    + Add Page Link
                                </button>
                            </div>
                        </div>

                        <!-- Preset Quick Buttons -->
                        <div class="pt-2 border-t border-neutral-200">
                            <span class="text-[10px] font-bold uppercase text-neutral-500 block mb-1.5">Preset Portal Routes:</span>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <button type="button" onclick="footerAddPreset('Courses', '/courses')" class="h-6 px-2 text-[10px] bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-700 font-medium cursor-pointer">+ Courses</button>
                                <button type="button" onclick="footerAddPreset('Instructors', '/instructors')" class="h-6 px-2 text-[10px] bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-700 font-medium cursor-pointer">+ Instructors</button>
                                <button type="button" onclick="footerAddPreset('Blog', '/blog')" class="h-6 px-2 text-[10px] bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-700 font-medium cursor-pointer">+ Blog</button>
                                <button type="button" onclick="footerAddPreset('Membership', '/membership')" class="h-6 px-2 text-[10px] bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-700 font-medium cursor-pointer">+ Membership</button>
                                <button type="button" onclick="footerAddPreset('Become an Instructor', '/instructor/register')" class="h-6 px-2 text-[10px] bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-700 font-medium cursor-pointer">+ Become Instructor</button>
                                <button type="button" onclick="footerAddPreset('Instructor Portal', '/instructor/login')" class="h-6 px-2 text-[10px] bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-700 font-medium cursor-pointer">+ Instructor Portal</button>
                                <button type="button" onclick="footerAddPreset('Student Portal', '/login')" class="h-6 px-2 text-[10px] bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-700 font-medium cursor-pointer">+ Student Portal</button>
                                <button type="button" onclick="footerAddPreset('Register', '/register')" class="h-6 px-2 text-[10px] bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-700 font-medium cursor-pointer">+ Register</button>
                            </div>
                        </div>

                        <!-- Custom URL Option -->
                        <details class="pt-2 border-t border-neutral-200 group">
                            <summary class="text-[11px] font-semibold text-neutral-700 hover:text-black cursor-pointer flex items-center justify-between py-1 list-none [&::-webkit-details-marker]:hidden">
                                <span>+ Add Custom Link / External URL</span>
                                <span class="text-[10px] text-neutral-400 group-open:rotate-180 transition-transform">&darr;</span>
                            </summary>
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 pt-2">
                                <div class="sm:col-span-5">
                                    <input type="text" id="footer-custom-label" placeholder="Link label (e.g. Help Center)" class="h-8 w-full border border-neutral-300 bg-white px-2.5 text-xs text-neutral-900 focus:outline-none">
                                </div>
                                <div class="sm:col-span-4">
                                    <input type="text" id="footer-custom-url" placeholder="URL (e.g. /help or #)" class="h-8 w-full border border-neutral-300 bg-white px-2.5 text-xs text-neutral-900 focus:outline-none">
                                </div>
                                <div class="sm:col-span-3">
                                    <button type="button" id="btn-footer-add-custom" class="h-8 w-full px-3 text-xs font-semibold uppercase tracking-wider bg-neutral-900 text-white hover:bg-neutral-800 transition-colors cursor-pointer">
                                        Add Link
                                    </button>
                                </div>
                            </div>
                        </details>
                    </div>

                    <!-- Columns Drag & Drop Canvas -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-neutral-700">Footer Columns</span>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="footerAddColumn()" class="h-7 px-3 text-[11px] font-semibold uppercase tracking-wider bg-neutral-900 text-white hover:bg-neutral-800 transition-colors cursor-pointer">
                                    + Add New Column
                                </button>
                                <button type="button" id="btn-footer-reset" class="h-7 px-2.5 text-[11px] font-semibold uppercase tracking-wider border border-neutral-300 hover:bg-neutral-100 text-neutral-600 transition-colors cursor-pointer">
                                    Reset
                                </button>
                            </div>
                        </div>

                        <div id="footer-columns-container" class="space-y-4">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Bottom Bar Legal Links Section -->
                    <div class="pt-4 border-t border-neutral-200">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block">Bottom Legal &amp; Compliance Links</span>
                                <span class="text-[11px] text-neutral-400">Links shown on the bottom footer bar next to copyright.</span>
                            </div>
                            <button type="button" onclick="footerAddBottomLink()" class="h-6 px-2 text-[10px] font-bold uppercase bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-800 cursor-pointer">
                                + Add Link
                            </button>
                        </div>

                        <div id="footer-bottom-links-container" class="space-y-1.5">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Newsletter Subscription Block -->
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
                        <div id="mini-footer-preview-cols" class="grid grid-cols-3 gap-2 pt-3 border-t border-gray-800 text-[10px] text-gray-400">
                            @foreach ($footerColumns as $c)
                                <div class="truncate">
                                    <span class="font-bold text-white block mb-1 truncate">{{ $c['title'] ?? 'Links' }}</span>
                                    @foreach (array_slice($c['links'] ?? [], 0, 3) as $l)
                                        <div class="truncate text-gray-400">{{ $l['label'] }}</div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <!-- Bottom Strip Simulation -->
                        <div class="pt-4 border-t border-gray-800 space-y-2 text-[10px] text-gray-400">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <span>{{ $settings['footer_copyright'] }}</span>
                                <div id="mini-footer-preview-bottom" class="flex items-center gap-2 text-[9px]">
                                    @foreach ($footerBottomLinks as $bIdx => $b)
                                        @if($bIdx > 0)<span>&bull;</span>@endif
                                        <span>{{ $b['label'] }}</span>
                                    @endforeach
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

<script>
(function() {
    let columns = @json($footerColumns);
    let bottomLinks = @json($footerBottomLinks);

    const colsContainer = document.getElementById('footer-columns-container');
    const bottomContainer = document.getElementById('footer-bottom-links-container');
    const targetSelect = document.getElementById('footer-target-column-select');
    const hiddenColsInput = document.getElementById('footer-navigation-columns_input');
    const hiddenBottomInput = document.getElementById('footer-bottom-links_input');
    const previewCols = document.getElementById('mini-footer-preview-cols');
    const previewBottom = document.getElementById('mini-footer-preview-bottom');

    let draggedType = null;
    let dragColIdx = null;
    let dragLinkIdx = null;

    function uid() {
        return 'c_' + Math.random().toString(36).substr(2, 9);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function syncState() {
        if (hiddenColsInput) hiddenColsInput.value = JSON.stringify(columns);
        if (hiddenBottomInput) hiddenBottomInput.value = JSON.stringify(bottomLinks);

        // Sync legacy inputs for test assertions
        const legSupport = document.getElementById('legacy_support_title');
        const legPages = document.getElementById('legacy_pages_title');
        const legUseful = document.getElementById('legacy_useful_title');
        if (legSupport && columns[0]) legSupport.value = columns[0].title;
        if (legPages && columns[1]) legPages.value = columns[1].title;
        if (legUseful && columns[2]) legUseful.value = columns[2].title;

        // Update target column select options
        if (targetSelect) {
            const curVal = targetSelect.value;
            targetSelect.innerHTML = '';
            columns.forEach((col, idx) => {
                const opt = document.createElement('option');
                opt.value = idx;
                opt.textContent = `Column ${idx + 1}: ${col.title || 'Untitled'}`;
                if (curVal == idx) opt.selected = true;
                targetSelect.appendChild(opt);
            });
        }

        // Update mini preview
        if (previewCols) {
            let html = '';
            columns.slice(0, 3).forEach(c => {
                html += `<div class="truncate">`;
                html += `<span class="font-bold text-white block mb-1 truncate">${escapeHtml(c.title || 'Untitled')}</span>`;
                (c.links || []).slice(0, 3).forEach(l => {
                    html += `<div class="truncate text-gray-400">${escapeHtml(l.label)}</div>`;
                });
                html += `</div>`;
            });
            previewCols.innerHTML = html;
        }

        if (previewBottom) {
            let bHtml = '';
            bottomLinks.forEach((b, idx) => {
                if (idx > 0) bHtml += `<span>&bull;</span>`;
                bHtml += `<span>${escapeHtml(b.label)}</span>`;
            });
            previewBottom.innerHTML = bHtml;
        }
    }

    function renderColumns() {
        if (!colsContainer) return;
        colsContainer.innerHTML = '';

        if (!columns || columns.length === 0) {
            colsContainer.innerHTML = `<div class="p-6 text-center bg-neutral-50 border border-dashed border-neutral-300 text-xs text-neutral-500">No footer columns configured. <button type="button" onclick="footerResetDefaults()" class="underline text-black font-semibold">Load defaults</button></div>`;
            syncState();
            return;
        }

        columns.forEach((col, colIdx) => {
            const card = document.createElement('div');
            card.className = 'footer-col-card border border-neutral-200 bg-white shadow-2xs';
            card.draggable = true;

            card.innerHTML = `
                <div class="flex items-center justify-between gap-3 p-3 bg-neutral-50/70 border-b border-neutral-100 col-header">
                    <div class="flex items-center gap-2 flex-1">
                        <span class="col-drag-handle cursor-grab active:cursor-grabbing p-1 text-neutral-400 hover:text-neutral-800" title="Drag to reorder column">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-12a2 2 0 10.001 4.001A2 2 0 0013 2zm0 6a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-neutral-200 text-neutral-700">Col ${colIdx + 1}</span>
                        <input type="text" value="${escapeHtml(col.title)}" placeholder="Column Heading Title" data-col-title="${colIdx}"
                               class="h-8 text-xs font-bold px-2.5 bg-white border border-neutral-300 focus:border-neutral-900 focus:outline-none flex-1 max-w-sm">
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="footerMoveCol(${colIdx}, -1)" ${colIdx === 0 ? 'disabled' : ''} class="h-7 w-6 text-xs border border-neutral-300 bg-white hover:text-black disabled:opacity-30 cursor-pointer" title="Move Left">&larr;</button>
                        <button type="button" onclick="footerMoveCol(${colIdx}, 1)" ${colIdx === columns.length - 1 ? 'disabled' : ''} class="h-7 w-6 text-xs border border-neutral-300 bg-white hover:text-black disabled:opacity-30 cursor-pointer" title="Move Right">&rarr;</button>
                        <button type="button" onclick="footerDeleteCol(${colIdx})" class="h-7 w-7 text-xs border border-neutral-300 bg-white text-red-500 hover:bg-red-50 hover:text-red-700 cursor-pointer" title="Delete Column">&times;</button>
                    </div>
                </div>

                <div class="p-3 space-y-2 links-list" data-col-links="${colIdx}">
                    ${(col.links || []).length === 0 ? `<p class="text-[11px] text-neutral-400 py-1 italic">No links in this column yet. Add links using the toolbar above.</p>` : ''}
                </div>
            `;

            // Drag setup for column
            card.addEventListener('dragstart', (e) => {
                draggedType = 'col';
                dragColIdx = colIdx;
                e.dataTransfer.effectAllowed = 'move';
                card.classList.add('opacity-40');
            });
            card.addEventListener('dragend', () => {
                card.classList.remove('opacity-40');
                document.querySelectorAll('.footer-col-card').forEach(c => c.classList.remove('border-neutral-900'));
            });
            card.addEventListener('dragover', (e) => {
                e.preventDefault();
                if (draggedType === 'col' && dragColIdx !== colIdx) card.classList.add('border-neutral-900');
            });
            card.addEventListener('dragleave', () => {
                card.classList.remove('border-neutral-900');
            });
            card.addEventListener('drop', (e) => {
                e.preventDefault();
                card.classList.remove('border-neutral-900');
                if (draggedType === 'col' && dragColIdx !== null && dragColIdx !== colIdx) {
                    const cToMove = columns.splice(dragColIdx, 1)[0];
                    columns.splice(colIdx, 0, cToMove);
                    renderColumns();
                }
            });

            // Populate links
            const listEl = card.querySelector('.links-list');
            (col.links || []).forEach((link, linkIdx) => {
                const linkRow = document.createElement('div');
                linkRow.className = 'link-row flex items-center justify-between gap-2 p-2 bg-neutral-50/60 border border-neutral-200 text-xs';
                linkRow.draggable = true;

                linkRow.innerHTML = `
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <span class="link-drag cursor-grab active:cursor-grabbing text-neutral-400 hover:text-neutral-800">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-12a2 2 0 10.001 4.001A2 2 0 0013 2zm0 6a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/></svg>
                        </span>
                        <input type="text" value="${escapeHtml(link.label)}" placeholder="Label" data-field="label" data-col="${colIdx}" data-link="${linkIdx}"
                               class="h-6 w-36 px-2 text-xs bg-white border border-neutral-300 focus:outline-none">
                        <input type="text" value="${escapeHtml(link.url)}" placeholder="URL" data-field="url" data-col="${colIdx}" data-link="${linkIdx}"
                               class="h-6 flex-1 px-2 text-xs font-mono bg-white border border-neutral-300 focus:outline-none text-neutral-600">
                        <select data-field="target" data-col="${colIdx}" data-link="${linkIdx}" class="h-6 text-[10px] bg-white border border-neutral-300 px-1 focus:outline-none">
                            <option value="_self" ${link.target === '_self' ? 'selected' : ''}>Same tab</option>
                            <option value="_blank" ${link.target === '_blank' ? 'selected' : ''}>New tab</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
                        <button type="button" onclick="footerMoveLink(${colIdx}, ${linkIdx}, -1)" ${linkIdx === 0 ? 'disabled' : ''} class="h-6 w-5 text-[10px] border border-neutral-300 bg-white hover:text-black disabled:opacity-30 cursor-pointer">&uarr;</button>
                        <button type="button" onclick="footerMoveLink(${colIdx}, ${linkIdx}, 1)" ${linkIdx === col.links.length - 1 ? 'disabled' : ''} class="h-6 w-5 text-[10px] border border-neutral-300 bg-white hover:text-black disabled:opacity-30 cursor-pointer">&darr;</button>
                        <button type="button" onclick="footerDeleteLink(${colIdx}, ${linkIdx})" class="h-6 w-6 text-xs text-red-500 hover:bg-red-50 hover:text-red-700 cursor-pointer" title="Delete link">&times;</button>
                    </div>
                `;

                // Drag setup for link row
                linkRow.addEventListener('dragstart', (e) => {
                    e.stopPropagation();
                    draggedType = 'link';
                    dragColIdx = colIdx;
                    dragLinkIdx = linkIdx;
                    e.dataTransfer.effectAllowed = 'move';
                    linkRow.classList.add('opacity-40');
                });
                linkRow.addEventListener('dragend', (e) => {
                    e.stopPropagation();
                    linkRow.classList.remove('opacity-40');
                });
                linkRow.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    linkRow.classList.add('border-neutral-900');
                });
                linkRow.addEventListener('dragleave', (e) => {
                    e.stopPropagation();
                    linkRow.classList.remove('border-neutral-900');
                });
                linkRow.addEventListener('drop', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    linkRow.classList.remove('border-neutral-900');
                    if (draggedType === 'link' && dragColIdx === colIdx && dragLinkIdx !== linkIdx) {
                        const lToMove = col.links.splice(dragLinkIdx, 1)[0];
                        col.links.splice(linkIdx, 0, lToMove);
                        renderColumns();
                    }
                });

                listEl.appendChild(linkRow);
            });

            colsContainer.appendChild(card);
        });

        // Attach input listeners
        colsContainer.querySelectorAll('input[data-col-title]').forEach(inp => {
            inp.addEventListener('input', function() {
                const cIdx = parseInt(this.dataset.colTitle);
                if (columns[cIdx]) columns[cIdx].title = this.value;
                syncState();
            });
        });

        colsContainer.querySelectorAll('input[data-field], select[data-field]').forEach(inp => {
            inp.addEventListener('input', function() {
                const cIdx = parseInt(this.dataset.col);
                const lIdx = parseInt(this.dataset.link);
                const field = this.dataset.field;
                if (columns[cIdx] && columns[cIdx].links && columns[cIdx].links[lIdx]) {
                    columns[cIdx].links[lIdx][field] = this.value;
                }
                syncState();
            });
        });

        syncState();
    }

    function renderBottomLinks() {
        if (!bottomContainer) return;
        bottomContainer.innerHTML = '';

        bottomLinks.forEach((link, idx) => {
            const row = document.createElement('div');
            row.className = 'flex items-center justify-between gap-2 p-2 bg-neutral-50/70 border border-neutral-200 text-xs';
            row.innerHTML = `
                <div class="flex items-center gap-2 flex-1">
                    <span class="text-[10px] font-bold text-neutral-400 font-mono">#${idx + 1}</span>
                    <input type="text" value="${escapeHtml(link.label)}" placeholder="Label" data-b-field="label" data-b-idx="${idx}"
                           class="h-6 w-44 px-2 text-xs bg-white border border-neutral-300 focus:outline-none">
                    <input type="text" value="${escapeHtml(link.url)}" placeholder="URL (e.g. /terms)" data-b-field="url" data-b-idx="${idx}"
                           class="h-6 flex-1 px-2 text-xs font-mono bg-white border border-neutral-300 focus:outline-none text-neutral-600">
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" onclick="footerMoveBottom(${idx}, -1)" ${idx === 0 ? 'disabled' : ''} class="h-6 w-5 text-[10px] border border-neutral-300 bg-white hover:text-black disabled:opacity-30 cursor-pointer">&uarr;</button>
                    <button type="button" onclick="footerMoveBottom(${idx}, 1)" ${idx === bottomLinks.length - 1 ? 'disabled' : ''} class="h-6 w-5 text-[10px] border border-neutral-300 bg-white hover:text-black disabled:opacity-30 cursor-pointer">&darr;</button>
                    <button type="button" onclick="footerDeleteBottom(${idx})" class="h-6 w-6 text-xs text-red-500 hover:bg-red-50 hover:text-red-700 cursor-pointer" title="Delete link">&times;</button>
                </div>
            `;
            bottomContainer.appendChild(row);
        });

        bottomContainer.querySelectorAll('input[data-b-field]').forEach(inp => {
            inp.addEventListener('input', function() {
                const idx = parseInt(this.dataset.bIdx);
                const field = this.dataset.bField;
                if (bottomLinks[idx]) bottomLinks[idx][field] = this.value;
                syncState();
            });
        });

        syncState();
    }

    // Global Action Helpers
    window.footerAddColumn = function() {
        columns.push({
            id: uid(),
            title: 'New Column',
            links: []
        });
        renderColumns();
    };

    window.footerDeleteCol = function(colIdx) {
        if (!confirm('Delete this footer column and all its links?')) return;
        columns.splice(colIdx, 1);
        renderColumns();
    };

    window.footerMoveCol = function(colIdx, dir) {
        const target = colIdx + dir;
        if (target < 0 || target >= columns.length) return;
        const col = columns.splice(colIdx, 1)[0];
        columns.splice(target, 0, col);
        renderColumns();
    };

    window.footerMoveLink = function(colIdx, linkIdx, dir) {
        const col = columns[colIdx];
        if (!col || !col.links) return;
        const target = linkIdx + dir;
        if (target < 0 || target >= col.links.length) return;
        const link = col.links.splice(linkIdx, 1)[0];
        col.links.splice(target, 0, link);
        renderColumns();
    };

    window.footerDeleteLink = function(colIdx, linkIdx) {
        if (!columns[colIdx] || !columns[colIdx].links) return;
        columns[colIdx].links.splice(linkIdx, 1);
        renderColumns();
    };

    window.footerAddPreset = function(label, url) {
        const colIdx = parseInt(targetSelect.value || 0);
        if (!columns[colIdx]) return;
        if (!columns[colIdx].links) columns[colIdx].links = [];
        columns[colIdx].links.push({
            id: uid(),
            label: label,
            url: url,
            target: '_self'
        });
        renderColumns();
    };

    window.footerAddBottomLink = function() {
        bottomLinks.push({
            id: uid(),
            label: 'New Link',
            url: '#',
            target: '_self'
        });
        renderBottomLinks();
    };

    window.footerMoveBottom = function(idx, dir) {
        const target = idx + dir;
        if (target < 0 || target >= bottomLinks.length) return;
        const link = bottomLinks.splice(idx, 1)[0];
        bottomLinks.splice(target, 0, link);
        renderBottomLinks();
    };

    window.footerDeleteBottom = function(idx) {
        bottomLinks.splice(idx, 1);
        renderBottomLinks();
    };

    window.footerResetDefaults = function() {
        if (!confirm('Reset footer columns and links to platform default settings?')) return;
        columns = @json(\App\Models\Setting::defaultFooterColumns());
        bottomLinks = @json(\App\Models\Setting::defaultFooterBottomLinks());
        renderColumns();
        renderBottomLinks();
    };

    const resetBtn = document.getElementById('btn-footer-reset');
    if (resetBtn) resetBtn.addEventListener('click', footerResetDefaults);

    // Toolbar Add Page
    const addPageBtn = document.getElementById('btn-footer-add-page');
    const pagePicker = document.getElementById('footer-page-picker-select');
    if (addPageBtn && pagePicker) {
        addPageBtn.addEventListener('click', function() {
            const opt = pagePicker.selectedOptions[0];
            if (!opt || !opt.value) {
                alert('Please choose a page from the dropdown first.');
                return;
            }
            const label = opt.dataset.title;
            const url = opt.value;
            footerAddPreset(label, url);
            pagePicker.value = '';
        });
    }

    // Toolbar Add Custom
    const addCustomBtn = document.getElementById('btn-footer-add-custom');
    const customLabelIn = document.getElementById('footer-custom-label');
    const customUrlIn = document.getElementById('footer-custom-url');
    if (addCustomBtn && customLabelIn && customUrlIn) {
        addCustomBtn.addEventListener('click', function() {
            const label = (customLabelIn.value || '').trim();
            const url = (customUrlIn.value || '').trim();
            if (!label) {
                alert('Please enter a link label.');
                customLabelIn.focus();
                return;
            }
            footerAddPreset(label, url || '#');
            customLabelIn.value = '';
            customUrlIn.value = '';
        });
    }

    // Initial Renders
    renderColumns();
    renderBottomLinks();
})();
</script>

</x-layouts.dashboard>
