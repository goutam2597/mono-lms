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

                <!-- 4. Interactive Navigation Menu Builder with Drag & Drop and Submenus -->
                <div class="border border-neutral-200 bg-white p-6 shadow-xs space-y-5" id="nav-builder-card">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-neutral-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            </span>
                            <div>
                                <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Header Navbar Menu Organizer</h2>
                                <p class="text-[11px] text-neutral-400">Drag &amp; drop to reorder top-level links and submenus. Add custom pages, routes, or external links.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" id="btn-reset-menu" class="h-8 px-3 text-[11px] font-semibold uppercase tracking-wider border border-neutral-300 hover:bg-neutral-100 text-neutral-600 transition-colors cursor-pointer">
                                Reset Defaults
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Input Serializing Menu Tree -->
                    <input type="hidden" name="header_menu_items" id="header_menu_items_input" value="">

                    <!-- Add Items Toolbar -->
                    <div class="bg-neutral-50 border border-neutral-200 p-4 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500 block">Quick Add to Navbar</span>

                        <!-- Option 1: Preset System Routes -->
                        <div>
                            <span class="text-[11px] font-semibold text-neutral-700 block mb-1.5">Preset Routes:</span>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <button type="button" onclick="quickAddRoute('Home', '/', 'route')" class="h-7 px-2.5 text-[11px] bg-white border border-neutral-300 hover:border-neutral-900 hover:text-black text-neutral-700 font-medium transition-colors cursor-pointer">
                                    + Home (/)
                                </button>
                                <button type="button" onclick="quickAddRoute('Courses', '/courses', 'route')" class="h-7 px-2.5 text-[11px] bg-white border border-neutral-300 hover:border-neutral-900 hover:text-black text-neutral-700 font-medium transition-colors cursor-pointer">
                                    + Courses (/courses)
                                </button>
                                <button type="button" onclick="quickAddRoute('Instructors', '/instructors', 'route')" class="h-7 px-2.5 text-[11px] bg-white border border-neutral-300 hover:border-neutral-900 hover:text-black text-neutral-700 font-medium transition-colors cursor-pointer">
                                    + Instructors (/instructors)
                                </button>
                                <button type="button" onclick="quickAddRoute('Blog', '/blog', 'route')" class="h-7 px-2.5 text-[11px] bg-white border border-neutral-300 hover:border-neutral-900 hover:text-black text-neutral-700 font-medium transition-colors cursor-pointer">
                                    + Blog (/blog)
                                </button>
                                <button type="button" onclick="quickAddRoute('Membership', '/membership', 'route', 'Pro')" class="h-7 px-2.5 text-[11px] bg-white border border-neutral-300 hover:border-neutral-900 hover:text-black text-neutral-700 font-medium transition-colors cursor-pointer">
                                    + Membership (/membership)
                                </button>
                            </div>
                        </div>

                        <!-- Option 2: Custom / Static Pages Selector -->
                        <div class="pt-2 border-t border-neutral-200">
                            <span class="text-[11px] font-semibold text-neutral-700 block mb-1.5">Add From Custom &amp; Static Pages:</span>
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                <select id="page-picker-select" class="h-8 px-2.5 text-xs bg-white border border-neutral-300 text-neutral-800 focus:outline-none flex-1">
                                    <option value="">-- Choose a published custom page --</option>
                                    @foreach ($availablePages as $p)
                                        <option value="{{ $p->slug }}" data-title="{{ $p->title }}" data-url="/{{ $p->slug }}">
                                            {{ $p->title }} (/{{ $p->slug }}) {{ $p->is_system ? '[System]' : '[Custom]' }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" id="btn-add-selected-page" class="h-8 px-4 text-xs font-semibold uppercase tracking-wider bg-neutral-900 text-white hover:bg-neutral-800 transition-colors shrink-0 cursor-pointer">
                                    + Add Page
                                </button>
                            </div>
                        </div>

                        <!-- Option 3: Custom Link Form Accordion -->
                        <details class="pt-2 border-t border-neutral-200 group">
                            <summary class="text-[11px] font-semibold text-neutral-700 hover:text-black cursor-pointer flex items-center justify-between py-1 list-none [&::-webkit-details-marker]:hidden">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    <span>+ Add Custom Link or Dropdown Parent</span>
                                </span>
                                <span class="text-[10px] text-neutral-400 group-open:rotate-180 transition-transform">&darr;</span>
                            </summary>
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 pt-3">
                                <div class="sm:col-span-4">
                                    <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Link Title</label>
                                    <input type="text" id="custom-link-label" placeholder="e.g. Resources or Community" class="h-8 w-full border border-neutral-300 bg-white px-2.5 text-xs text-neutral-900 focus:outline-none">
                                </div>
                                <div class="sm:col-span-5">
                                    <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Target URL (or # for dropdown menu)</label>
                                    <input type="text" id="custom-link-url" placeholder="e.g. # or /resources or https://..." class="h-8 w-full border border-neutral-300 bg-white px-2.5 text-xs text-neutral-900 focus:outline-none">
                                </div>
                                <div class="sm:col-span-3 flex items-end">
                                    <button type="button" id="btn-add-custom-link" class="h-8 w-full px-3 text-xs font-semibold uppercase tracking-wider bg-neutral-900 text-white hover:bg-neutral-800 transition-colors cursor-pointer">
                                        Add Link
                                    </button>
                                </div>
                            </div>
                        </details>
                    </div>

                    <!-- Drag & Drop Menu Tree List -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-neutral-700">Navbar Menu Tree</span>
                            <span class="text-[11px] text-neutral-400">Drag handles (<span class="font-mono">:::</span>) or use arrows to organize</span>
                        </div>

                        <div id="menu-tree-container" class="space-y-2.5">
                            <!-- Dynamically populated via JS -->
                        </div>
                    </div>

                    <!-- Hidden legacy inputs for test & backward compatibility -->
                    <div class="hidden">
                        <input type="checkbox" name="header_nav_home_enabled" id="legacy-nav-home" value="1" @checked($settings['header_nav_home_enabled'])>
                        <input type="checkbox" name="header_nav_courses_enabled" id="legacy-nav-courses" value="1" @checked($settings['header_nav_courses_enabled'])>
                        <input type="checkbox" name="header_nav_instructors_enabled" id="legacy-nav-instructors" value="1" @checked($settings['header_nav_instructors_enabled'])>
                        <input type="checkbox" name="header_nav_blog_enabled" id="legacy-nav-blog" value="1" @checked($settings['header_nav_blog_enabled'])>
                        <input type="checkbox" name="header_nav_membership_enabled" id="legacy-nav-membership" value="1" @checked($settings['header_nav_membership_enabled'])>
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
                            <div id="mini-nav-preview-links" class="hidden sm:flex items-center gap-2 text-[10px] font-medium text-neutral-700 truncate">
                                @foreach($menuItems as $m)
                                    @continue(!($m['is_active'] ?? true))
                                    @if(!empty($m['children']))
                                        <span class="inline-flex items-center gap-0.5 text-neutral-800 font-semibold">{{ $m['label'] }}<svg class="w-2.5 h-2.5 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></span>
                                    @else
                                        <span>{{ $m['label'] }}</span>
                                    @endif
                                @endforeach
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

<script>
(function() {
    let menu = @json($menuItems);
    const container = document.getElementById('menu-tree-container');
    const hiddenInput = document.getElementById('header_menu_items_input');
    const previewContainer = document.getElementById('mini-nav-preview-links');

    let draggedItem = null;
    let draggedParentIndex = null;
    let draggedChildIndex = null;

    function uid() {
        return 'm_' + Math.random().toString(36).substr(2, 9);
    }

    function syncState() {
        if (hiddenInput) {
            hiddenInput.value = JSON.stringify(menu);
        }

        // Sync legacy checkboxes for test & backward compatibility
        const legacyHome = document.getElementById('legacy-nav-home');
        const legacyCourses = document.getElementById('legacy-nav-courses');
        const legacyInstructors = document.getElementById('legacy-nav-instructors');
        const legacyBlog = document.getElementById('legacy-nav-blog');
        const legacyMembership = document.getElementById('legacy-nav-membership');

        const homeItem = menu.find(i => i.url === '/' || i.id === 'nav_home');
        if (legacyHome && homeItem) legacyHome.checked = (homeItem.is_active !== false);

        const coursesItem = menu.find(i => i.url === '/courses' || i.id === 'nav_courses');
        if (legacyCourses && coursesItem) legacyCourses.checked = (coursesItem.is_active !== false);

        const instructorsItem = menu.find(i => i.url === '/instructors' || i.id === 'nav_instructors');
        if (legacyInstructors && instructorsItem) legacyInstructors.checked = (instructorsItem.is_active !== false);

        const blogItem = menu.find(i => i.url === '/blog' || i.id === 'nav_blog');
        if (legacyBlog && blogItem) legacyBlog.checked = (blogItem.is_active !== false);

        const membershipItem = menu.find(i => i.url === '/membership' || i.id === 'nav_membership');
        if (legacyMembership && membershipItem) legacyMembership.checked = (membershipItem.is_active !== false);

        // Update mini preview
        if (previewContainer) {
            let html = '';
            menu.forEach(item => {
                if (item.is_active === false) return;
                const hasSubs = Array.isArray(item.children) && item.children.length > 0;
                if (hasSubs) {
                    html += `<span class="inline-flex items-center gap-0.5 text-neutral-800 font-semibold">${escapeHtml(item.label)}<svg class="w-2.5 h-2.5 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></span>`;
                } else {
                    html += `<span>${escapeHtml(item.label)}</span>`;
                }
            });
            previewContainer.innerHTML = html;
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function renderTree() {
        if (!container) return;
        container.innerHTML = '';

        if (!menu || menu.length === 0) {
            container.innerHTML = `
                <div class="p-8 text-center bg-neutral-50 border border-dashed border-neutral-300">
                    <p class="text-xs text-neutral-500 mb-2">No navigation links configured yet.</p>
                    <button type="button" onclick="resetToDefaults()" class="h-7 px-3 text-xs bg-neutral-900 text-white font-medium">Load Default Navigation</button>
                </div>
            `;
            syncState();
            return;
        }

        menu.forEach((item, pIndex) => {
            const hasSubs = Array.isArray(item.children) && item.children.length > 0;
            const card = document.createElement('div');
            card.className = 'menu-item-card border border-neutral-200 bg-white shadow-2xs transition-shadow';
            card.dataset.parentIndex = pIndex;

            card.innerHTML = `
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 p-3 bg-neutral-50/70 border-b border-neutral-100 handle-bar">
                    <div class="flex items-center gap-2.5 flex-1 min-w-0">
                        <span class="drag-handle cursor-grab active:cursor-grabbing p-1 text-neutral-400 hover:text-neutral-800 select-none" title="Drag to reorder">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-12a2 2 0 10.001 4.001A2 2 0 0013 2zm0 6a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-neutral-200 text-neutral-700">#${pIndex + 1}</span>
                        <div class="flex-1 min-w-0 grid grid-cols-1 sm:grid-cols-12 gap-2">
                            <input type="text" value="${escapeHtml(item.label)}" placeholder="Label" data-field="label" data-pindex="${pIndex}"
                                   class="sm:col-span-4 h-7 text-xs font-semibold px-2 bg-white border border-neutral-300 focus:border-neutral-900 focus:outline-none">
                            <input type="text" value="${escapeHtml(item.url)}" placeholder="URL (e.g. /courses or #)" data-field="url" data-pindex="${pIndex}"
                                   class="sm:col-span-5 h-7 text-xs font-mono px-2 bg-white border border-neutral-300 focus:border-neutral-900 focus:outline-none text-neutral-600">
                            <input type="text" value="${escapeHtml(item.badge || '')}" placeholder="Badge (opt)" data-field="badge" data-pindex="${pIndex}"
                                   class="sm:col-span-3 h-7 text-[11px] px-2 bg-white border border-neutral-300 focus:border-neutral-900 focus:outline-none text-emerald-700">
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 self-end sm:self-center shrink-0">
                        <!-- Target -->
                        <select data-field="target" data-pindex="${pIndex}" class="h-7 text-[10px] bg-white border border-neutral-300 px-1 focus:outline-none">
                            <option value="_self" ${item.target === '_self' || !item.target ? 'selected' : ''}>Same tab</option>
                            <option value="_blank" ${item.target === '_blank' ? 'selected' : ''}>New tab</option>
                        </select>

                        <!-- Visibility Toggle -->
                        <button type="button" onclick="toggleItemVisibility(${pIndex})" title="${item.is_active !== false ? 'Visible on navbar' : 'Hidden from navbar'}"
                                class="h-7 px-2 text-xs border border-neutral-300 ${item.is_active !== false ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-neutral-100 text-neutral-400'} cursor-pointer">
                            ${item.is_active !== false ? '👁 Active' : '✕ Hidden'}
                        </button>

                        <!-- Add Submenu Item -->
                        <button type="button" onclick="addSubmenuItem(${pIndex})" title="Add dropdown submenu under this item"
                                class="h-7 px-2 text-[10px] font-bold uppercase tracking-wider bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-800 transition-colors cursor-pointer">
                            + Submenu
                        </button>

                        <!-- Move Up / Down -->
                        <button type="button" onclick="moveItem(${pIndex}, -1)" ${pIndex === 0 ? 'disabled' : ''} class="h-7 w-6 text-xs border border-neutral-300 bg-white text-neutral-600 hover:text-black disabled:opacity-30 cursor-pointer" title="Move Up">&uarr;</button>
                        <button type="button" onclick="moveItem(${pIndex}, 1)" ${pIndex === menu.length - 1 ? 'disabled' : ''} class="h-7 w-6 text-xs border border-neutral-300 bg-white text-neutral-600 hover:text-black disabled:opacity-30 cursor-pointer" title="Move Down">&darr;</button>

                        <!-- Delete -->
                        <button type="button" onclick="deleteItem(${pIndex})" class="h-7 w-7 text-xs border border-neutral-300 bg-white text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors cursor-pointer" title="Delete menu item">
                            &times;
                        </button>
                    </div>
                </div>
            `;

            // Drag setup for parent card
            card.draggable = true;
            card.addEventListener('dragstart', (e) => {
                if (!e.target.closest('.drag-handle') && e.target !== card) {
                    // Only start drag if initiated on drag-handle or card itself
                }
                draggedItem = 'parent';
                draggedParentIndex = pIndex;
                draggedChildIndex = null;
                e.dataTransfer.effectAllowed = 'move';
                card.classList.add('opacity-40');
            });
            card.addEventListener('dragend', () => {
                card.classList.remove('opacity-40');
                document.querySelectorAll('.menu-item-card').forEach(c => c.classList.remove('border-neutral-900', 'bg-neutral-100'));
            });
            card.addEventListener('dragover', (e) => {
                e.preventDefault();
                if (draggedItem === 'parent' && draggedParentIndex !== pIndex) {
                    card.classList.add('border-neutral-900');
                }
            });
            card.addEventListener('dragleave', () => {
                card.classList.remove('border-neutral-900');
            });
            card.addEventListener('drop', (e) => {
                e.preventDefault();
                card.classList.remove('border-neutral-900');
                if (draggedItem === 'parent' && draggedParentIndex !== null && draggedParentIndex !== pIndex) {
                    const itemToMove = menu.splice(draggedParentIndex, 1)[0];
                    menu.splice(pIndex, 0, itemToMove);
                    renderTree();
                }
            });

            // Submenus container (children)
            if (hasSubs) {
                const subWrap = document.createElement('div');
                subWrap.className = 'p-3 pl-8 bg-neutral-50/40 space-y-2 border-t border-neutral-100';

                const subHeader = document.createElement('div');
                subHeader.className = 'flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1';
                subHeader.innerHTML = `<span>Dropdown Submenus (${item.children.length})</span>`;
                subWrap.appendChild(subHeader);

                item.children.forEach((child, cIndex) => {
                    const childRow = document.createElement('div');
                    childRow.className = 'submenu-row flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 p-2 bg-white border border-neutral-200 shadow-2xs text-xs';
                    childRow.draggable = true;

                    childRow.innerHTML = `
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <span class="sub-drag-handle cursor-grab active:cursor-grabbing p-0.5 text-neutral-400 hover:text-neutral-800" title="Drag submenu">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-12a2 2 0 10.001 4.001A2 2 0 0013 2zm0 6a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/></svg>
                            </span>
                            <span class="text-[9px] font-bold text-neutral-400">&rdsh;</span>
                            <input type="text" value="${escapeHtml(child.label)}" placeholder="Submenu Label" data-field="label" data-pindex="${pIndex}" data-cindex="${cIndex}"
                                   class="h-6 w-32 sm:w-40 px-2 text-xs font-medium border border-neutral-300 bg-white focus:outline-none">
                            <input type="text" value="${escapeHtml(child.url)}" placeholder="URL" data-field="url" data-pindex="${pIndex}" data-cindex="${cIndex}"
                                   class="h-6 flex-1 px-2 text-xs font-mono border border-neutral-300 bg-white focus:outline-none text-neutral-600">
                        </div>

                        <div class="flex items-center gap-1 shrink-0 self-end sm:self-center">
                            <!-- Outdent / Move to Top Level -->
                            <button type="button" onclick="outdentSubmenu(${pIndex}, ${cIndex})" class="h-6 px-1.5 text-[10px] border border-neutral-300 hover:bg-neutral-100 text-neutral-600 cursor-pointer" title="Move to top level as standalone item">
                                &larr; Top Level
                            </button>
                            <!-- Move Up/Down within parent -->
                            <button type="button" onclick="moveSubmenu(${pIndex}, ${cIndex}, -1)" ${cIndex === 0 ? 'disabled' : ''} class="h-6 w-5 text-[10px] border border-neutral-300 bg-white text-neutral-600 hover:text-black disabled:opacity-30 cursor-pointer">&uarr;</button>
                            <button type="button" onclick="moveSubmenu(${pIndex}, ${cIndex}, 1)" ${cIndex === item.children.length - 1 ? 'disabled' : ''} class="h-6 w-5 text-[10px] border border-neutral-300 bg-white text-neutral-600 hover:text-black disabled:opacity-30 cursor-pointer">&darr;</button>
                            <!-- Delete -->
                            <button type="button" onclick="deleteSubmenu(${pIndex}, ${cIndex})" class="h-6 w-6 text-xs text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors cursor-pointer" title="Delete submenu item">&times;</button>
                        </div>
                    `;

                    childRow.addEventListener('dragstart', (e) => {
                        e.stopPropagation();
                        draggedItem = 'child';
                        draggedParentIndex = pIndex;
                        draggedChildIndex = cIndex;
                        e.dataTransfer.effectAllowed = 'move';
                        childRow.classList.add('opacity-40');
                    });
                    childRow.addEventListener('dragend', (e) => {
                        e.stopPropagation();
                        childRow.classList.remove('opacity-40');
                    });
                    childRow.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        childRow.classList.add('border-neutral-900');
                    });
                    childRow.addEventListener('dragleave', (e) => {
                        e.stopPropagation();
                        childRow.classList.remove('border-neutral-900');
                    });
                    childRow.addEventListener('drop', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        childRow.classList.remove('border-neutral-900');
                        if (draggedItem === 'child' && draggedParentIndex === pIndex && draggedChildIndex !== cIndex) {
                            const movingChild = item.children.splice(draggedChildIndex, 1)[0];
                            item.children.splice(cIndex, 0, movingChild);
                            renderTree();
                        }
                    });

                    subWrap.appendChild(childRow);
                });

                card.appendChild(subWrap);
            }

            container.appendChild(card);
        });

        // Attach input change listeners
        container.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('input', function() {
                const field = this.dataset.field;
                const pIdx = parseInt(this.dataset.pindex);
                const cIdx = this.dataset.cindex !== undefined ? parseInt(this.dataset.cindex) : null;

                if (cIdx !== null) {
                    if (menu[pIdx] && menu[pIdx].children && menu[pIdx].children[cIdx]) {
                        menu[pIdx].children[cIdx][field] = this.value;
                    }
                } else {
                    if (menu[pIdx]) {
                        menu[pIdx][field] = this.value;
                    }
                }
                syncState();
            });
        });

        syncState();
    }

    // Global Action Helpers
    window.quickAddRoute = function(label, url, type = 'route', badge = '') {
        menu.push({
            id: uid(),
            label: label,
            url: url,
            type: type,
            target: '_self',
            badge: badge,
            is_active: true,
            children: []
        });
        renderTree();
    };

    window.addSubmenuItem = function(pIndex) {
        if (!menu[pIndex].children) menu[pIndex].children = [];
        menu[pIndex].children.push({
            id: uid(),
            label: 'New Submenu Item',
            url: '#',
            type: 'custom',
            target: '_self',
            badge: '',
            is_active: true
        });
        renderTree();
    };

    window.moveItem = function(index, dir) {
        const targetIndex = index + dir;
        if (targetIndex < 0 || targetIndex >= menu.length) return;
        const item = menu.splice(index, 1)[0];
        menu.splice(targetIndex, 0, item);
        renderTree();
    };

    window.moveSubmenu = function(pIndex, cIndex, dir) {
        const parent = menu[pIndex];
        if (!parent || !parent.children) return;
        const targetIndex = cIndex + dir;
        if (targetIndex < 0 || targetIndex >= parent.children.length) return;
        const child = parent.children.splice(cIndex, 1)[0];
        parent.children.splice(targetIndex, 0, child);
        renderTree();
    };

    window.outdentSubmenu = function(pIndex, cIndex) {
        const parent = menu[pIndex];
        if (!parent || !parent.children) return;
        const child = parent.children.splice(cIndex, 1)[0];
        // Insert right after parent at top level
        menu.splice(pIndex + 1, 0, {
            ...child,
            children: []
        });
        renderTree();
    };

    window.toggleItemVisibility = function(pIndex) {
        if (!menu[pIndex]) return;
        menu[pIndex].is_active = (menu[pIndex].is_active === false) ? true : false;
        renderTree();
    };

    window.deleteItem = function(pIndex) {
        if (!confirm('Are you sure you want to remove this navigation link?')) return;
        menu.splice(pIndex, 1);
        renderTree();
    };

    window.deleteSubmenu = function(pIndex, cIndex) {
        if (!menu[pIndex] || !menu[pIndex].children) return;
        menu[pIndex].children.splice(cIndex, 1);
        renderTree();
    };

    window.resetToDefaults = function() {
        if (!confirm('Reset navigation menu to default layout? Any custom additions will be restored to default.')) return;
        menu = @json(\App\Models\Setting::defaultHeaderMenu());
        renderTree();
    };

    const resetBtn = document.getElementById('btn-reset-menu');
    if (resetBtn) resetBtn.addEventListener('click', resetToDefaults);

    // Add selected page
    const addPageBtn = document.getElementById('btn-add-selected-page');
    const pagePicker = document.getElementById('page-picker-select');
    if (addPageBtn && pagePicker) {
        addPageBtn.addEventListener('click', function() {
            const selectedOpt = pagePicker.selectedOptions[0];
            if (!selectedOpt || !selectedOpt.value) {
                alert('Please select a custom page from the dropdown first.');
                return;
            }
            const title = selectedOpt.dataset.title;
            const url = selectedOpt.dataset.url;
            quickAddRoute(title, url, 'page');
            pagePicker.value = '';
        });
    }

    // Add custom link
    const addCustomBtn = document.getElementById('btn-add-custom-link');
    const customLabelIn = document.getElementById('custom-link-label');
    const customUrlIn = document.getElementById('custom-link-url');
    if (addCustomBtn && customLabelIn && customUrlIn) {
        addCustomBtn.addEventListener('click', function() {
            const label = (customLabelIn.value || '').trim();
            const url = (customUrlIn.value || '').trim();
            if (!label) {
                alert('Please enter a link title.');
                customLabelIn.focus();
                return;
            }
            quickAddRoute(label, url || '#', 'custom');
            customLabelIn.value = '';
            customUrlIn.value = '';
        });
    }

    // Initial render
    renderTree();
})();
</script>

</x-layouts.dashboard>
