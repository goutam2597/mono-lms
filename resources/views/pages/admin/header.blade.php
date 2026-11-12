<x-layouts.dashboard title="Header Editor - Mono LMS" active="header" userType="admin">

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
            <h1 class="text-2xl font-medium tracking-tight text-neutral-900">Header Editor</h1>
            <p class="text-xs text-neutral-500 mt-1">Configure top notification bar, address and phone contacts, social media icons, sticky navbar behavior, and primary navigation items.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="h-10 px-4 border border-neutral-300 bg-white hover:bg-neutral-50 text-neutral-700 text-xs font-medium flex items-center gap-1.5 transition-colors">
                <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Live Site
            </a>
            <button type="submit" form="header-form" class="action-btn h-10 px-6">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Save Header Changes
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

    <form method="POST" action="{{ route('admin.header.update') }}" id="header-form" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
            <!-- Left Main Form Column (7 cols) -->
            <div class="xl:col-span-7 space-y-6">

                <!-- 1. Top Bar Settings -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-4 mb-5">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="5" rx="1"/><rect x="2" y="11" width="20" height="10" rx="1"/></svg>
                            </span>
                            <div>
                                <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Storefront Top Bar</h2>
                                <p class="text-[11px] text-neutral-400">Dark announcement &amp; quick contact bar shown above the main navbar.</p>
                            </div>
                        </div>
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="header_topbar_enabled" value="1" @checked($settings['header_topbar_enabled']) class="control">
                            <span class="text-xs font-semibold text-neutral-800">Display Top Bar</span>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Contact Address / Location</label>
                                <input type="text" name="header_topbar_address" value="{{ $settings['header_topbar_address'] }}"
                                       placeholder="e.g. 70 Washington Square South, New York"
                                       class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Contact Phone Number</label>
                                <input type="text" name="header_topbar_phone" value="{{ $settings['header_topbar_phone'] }}"
                                       placeholder="e.g. +1 (555) 234-5678"
                                       class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="pt-2">
                            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="header_language_switcher_enabled" value="1" @checked($settings['header_language_switcher_enabled']) class="control">
                                <span class="text-xs font-medium text-neutral-700">Display Multilingual Language Switcher in Top Bar</span>
                            </label>
                            <p class="text-[10px] text-neutral-400 mt-0.5">Allows visitors to switch between active languages (English, Hindi, Arabic, etc.).</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Social Media Links -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Top Bar Social Profiles</h2>
                            <p class="text-[11px] text-neutral-400">Social network URLs shown in the top bar right-side icon group.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Facebook URL</label>
                            <input type="text" name="header_social_facebook" value="{{ $settings['header_social_facebook'] }}" placeholder="https://facebook.com/..."
                                   class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Instagram URL</label>
                            <input type="text" name="header_social_instagram" value="{{ $settings['header_social_instagram'] }}" placeholder="https://instagram.com/..."
                                   class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Twitter / X URL</label>
                            <input type="text" name="header_social_twitter" value="{{ $settings['header_social_twitter'] }}" placeholder="https://twitter.com/..."
                                   class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">LinkedIn URL</label>
                            <input type="text" name="header_social_linkedin" value="{{ $settings['header_social_linkedin'] }}" placeholder="https://linkedin.com/..."
                                   class="h-10 w-full border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- 3. Main Navbar Layout & Search -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Main Navbar Behavior &amp; Features</h2>
                            <p class="text-[11px] text-neutral-400">Sticky positioning and search functionality.</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="group flex items-start justify-between gap-3 p-3.5 border border-neutral-200 bg-neutral-50/60 hover:border-neutral-900 transition-colors cursor-pointer select-none">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="header_sticky_enabled" value="1" @checked($settings['header_sticky_enabled']) class="control mt-0.5">
                                <div>
                                    <span class="font-bold text-xs text-neutral-900 block">Sticky Header on Scroll</span>
                                    <span class="text-[11px] text-neutral-400">Keeps the navigation bar fixed to the top of the browser viewport while visitors scroll.</span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ $settings['header_sticky_enabled'] ? 'Active' : 'Off' }}</span>
                        </label>

                        <label class="group flex items-start justify-between gap-3 p-3.5 border border-neutral-200 bg-neutral-50/60 hover:border-neutral-900 transition-colors cursor-pointer select-none">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="header_search_enabled" value="1" @checked($settings['header_search_enabled']) class="control mt-0.5">
                                <div>
                                    <span class="font-bold text-xs text-neutral-900 block">Global Course Search Drawer</span>
                                    <span class="text-[11px] text-neutral-400">Displays search icon in the navbar with expanding keyboard shortcut drawer (Press /).</span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ $settings['header_search_enabled'] ? 'Active' : 'Off' }}</span>
                        </label>
                    </div>
                </div>

                <!-- 4. Navigation Menu Links -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Desktop &amp; Mobile Navigation Links</h2>
                            <p class="text-[11px] text-neutral-400">Choose which primary pages appear in the main navigation menu.</p>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <label class="flex items-center justify-between p-3 border border-neutral-200 bg-white hover:border-neutral-400 cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="header_nav_home_enabled" value="1" @checked($settings['header_nav_home_enabled']) class="control">
                                <span class="text-xs font-semibold text-neutral-900">Home Link</span>
                            </div>
                            <span class="text-[11px] font-mono text-neutral-400">/</span>
                        </label>

                        <label class="flex items-center justify-between p-3 border border-neutral-200 bg-white hover:border-neutral-400 cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="header_nav_courses_enabled" value="1" @checked($settings['header_nav_courses_enabled']) class="control">
                                <span class="text-xs font-semibold text-neutral-900">Courses Catalog Link</span>
                            </div>
                            <span class="text-[11px] font-mono text-neutral-400">/courses</span>
                        </label>

                        <label class="flex items-center justify-between p-3 border border-neutral-200 bg-white hover:border-neutral-400 cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="header_nav_instructors_enabled" value="1" @checked($settings['header_nav_instructors_enabled']) class="control">
                                <span class="text-xs font-semibold text-neutral-900">Instructors Directory Link</span>
                            </div>
                            <span class="text-[11px] font-mono text-neutral-400">/instructors</span>
                        </label>

                        <label class="flex items-center justify-between p-3 border border-neutral-200 bg-white hover:border-neutral-400 cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="header_nav_blog_enabled" value="1" @checked($settings['header_nav_blog_enabled']) class="control">
                                <span class="text-xs font-semibold text-neutral-900">Blog &amp; Articles Link</span>
                            </div>
                            <span class="text-[11px] font-mono text-neutral-400">/blog</span>
                        </label>

                        <label class="flex items-center justify-between p-3 border border-neutral-200 bg-white hover:border-neutral-400 cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="header_nav_membership_enabled" value="1" @checked($settings['header_nav_membership_enabled']) class="control">
                                <span class="text-xs font-semibold text-neutral-900">Membership Subscriptions Link</span>
                            </div>
                            <span class="text-[11px] font-mono text-neutral-400">/membership</span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Right Column: Visual Preview & Navigation (5 cols) -->
            <div class="xl:col-span-5 space-y-6">

                <!-- Visual Storefront Header Simulator -->
                <div class="border border-neutral-200 bg-white p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-neutral-900">Live Header Preview</span>
                        </div>
                        <span class="text-[11px] text-neutral-400">Storefront View</span>
                    </div>

                    <!-- Mini Header Mockup -->
                    <div class="border border-neutral-300 rounded-lg overflow-hidden shadow-xs">
                        @if($settings['header_topbar_enabled'])
                            <!-- Mini Topbar -->
                            <div class="bg-[#08131e] text-white text-[10px] px-3 py-1.5 flex items-center justify-between">
                                <div class="flex items-center gap-2 truncate">
                                    <span class="text-gray-300 truncate">{{ Str::limit($settings['header_topbar_address'], 30) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($settings['header_language_switcher_enabled'])
                                        <span class="text-gray-400 text-[9px] px-1 py-0.2 border border-gray-700 rounded">EN</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Mini Navbar -->
                        <div class="bg-white px-3 py-2.5 flex items-center justify-between border-t border-neutral-100 text-[11px]">
                            <!-- Logo -->
                            <img src="{{ \App\Models\Setting::logoUrl() }}" alt="Logo" class="h-5 w-auto object-contain shrink-0" />

                            <!-- Links -->
                            <div class="hidden sm:flex items-center gap-2.5 text-[10px] font-medium text-neutral-700">
                                @if($settings['header_nav_home_enabled'])<span class="text-[#344c3d] font-bold">Home</span>@endif
                                @if($settings['header_nav_courses_enabled'])<span>Courses</span>@endif
                                @if($settings['header_nav_instructors_enabled'])<span>Instructors</span>@endif
                                @if($settings['header_nav_blog_enabled'])<span>Blog</span>@endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-1.5">
                                @if($settings['header_search_enabled'])
                                    <div class="w-6 h-6 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-600">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                @endif
                                <span class="px-2 py-1 rounded bg-[#344c3d] text-white text-[9px] font-bold">Register</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-[11px] text-neutral-400 text-center">Preview dynamically renders current saved header configurations.</p>
                </div>

                <!-- Site Editor Shortcuts -->
                <div class="border border-neutral-200 bg-white p-5 shadow-xs space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-900">Site Editor Navigation</h3>
                    <ul class="divide-y divide-neutral-100 text-xs">
                        <li class="py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                                <span class="font-semibold text-neutral-800">Footer Editor</span>
                            </div>
                            <a href="{{ route('admin.footer.index') }}" class="text-[var(--theme-primary,#344c3d)] hover:underline font-medium">Edit Footer &rarr;</a>
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
