<x-layouts.dashboard title="Home Page Editor - Mono LMS" active="homepage" userType="admin">

    <div class="space-y-6">

        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] bg-[#344C3D]/10 text-[#344C3D] border border-[#344C3D]/20">
                        Storefront Appearance
                    </span>
                    <span class="text-xs text-slate-400">·</span>
                    <span class="text-xs text-slate-500 font-mono">13 Configurable Sections</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1">Home Page Editor</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Customize storefront sections, visibility toggles, headlines, counters, promotional copy, and media assets.</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('home') }}" target="_blank" class="action-btn action-btn-ghost">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>View Live Page</span>
                </a>
                <button type="submit" form="homepage-form" class="action-btn action-btn-primary">
                    <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Changes</span>
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-xs sm:text-sm font-medium flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Form & Section Navigation Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Sticky Section Nav Sidebar -->
            <div class="lg:col-span-4 xl:col-span-3 sticky top-6 space-y-4">
                <div class="bg-white border border-slate-200 p-4 shadow-xs">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400 px-3 py-2">
                        Sections Menu
                    </h3>
                    <nav class="space-y-1 text-xs font-medium" id="sections-quicknav">
                        @php
                            $navItems = [
                                ['id' => 'hero', 'num' => '01', 'name' => 'Hero Banner'],
                                ['id' => 'stats', 'num' => '02', 'name' => 'Statistics Ribbon'],
                                ['id' => 'categories', 'num' => '03', 'name' => 'Top Categories'],
                                ['id' => 'featured_courses', 'num' => '04', 'name' => 'Featured Courses'],
                                ['id' => 'banner_transform', 'num' => '05', 'name' => 'Transform Banner'],
                                ['id' => 'goals', 'num' => '06', 'name' => 'Goals & Impact'],
                                ['id' => 'accelerate', 'num' => '07', 'name' => 'Cloud Showcases'],
                                ['id' => 'trending_courses', 'num' => '08', 'name' => 'Trending Courses'],
                                ['id' => 'instructors', 'num' => '09', 'name' => 'Instructors Section'],
                                ['id' => 'banner_instructor', 'num' => '10', 'name' => 'Become Instructor'],
                                ['id' => 'partners', 'num' => '11', 'name' => 'Partner Brands'],
                                ['id' => 'testimonials', 'num' => '12', 'name' => 'Testimonials'],
                                ['id' => 'blog', 'num' => '13', 'name' => 'News & Blog'],
                            ];
                        @endphp

                        @foreach ($navItems as $nav)
                            @php
                                $isEnabled = !empty($sections[$nav['id']]['enabled']);
                            @endphp
                            <a href="#section-{{ $nav['id'] }}"
                               class="group flex items-center justify-between px-3 py-2 text-slate-600 hover:text-[#344C3D] hover:bg-slate-50 transition-colors"
                               data-nav-target="{{ $nav['id'] }}">
                                <span class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] text-slate-400 group-hover:text-[#344C3D]">{{ $nav['num'] }}</span>
                                    <span class="truncate">{{ $nav['name'] }}</span>
                                </span>
                                <span class="w-2 h-2 shrink-0 {{ $isEnabled ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                      data-nav-dot="{{ $nav['id'] }}"
                                      title="{{ $isEnabled ? 'Enabled' : 'Hidden' }}"></span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- Live Storefront Quick Link Box -->
                <div class="bg-[#344C3D] border border-[#25372C] p-4 text-white shadow-xs space-y-2">
                    <p class="text-[10px] font-bold text-emerald-200 uppercase tracking-[0.14em]">Fast Live Preview</p>
                    <p class="text-xs text-white/80 leading-relaxed">Changes save instantly to the database and reflect immediately on your live storefront.</p>
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-emerald-300 hover:text-white font-semibold pt-1">
                        <span>Open homepage</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- Sections Form Content -->
            <div class="lg:col-span-8 xl:col-span-9 space-y-6">

                <form id="homepage-form" method="POST" action="{{ route('admin.homepage.update') }}" class="space-y-6 text-xs">
                    @csrf
                    @method('PUT')

                    <!-- 01. HERO SECTION -->
                    <div id="section-hero" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">01</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Hero Section</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Main banner, orbital avatar visual, headline, subtitle, and search prompt.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[hero][enabled]" value="0">
                                <input type="checkbox" name="sections[hero][enabled]" value="1" class="sr-only peer" @checked($sections['hero']['enabled'] ?? true) data-toggle-section="hero">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="hero">{{ ($sections['hero']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Badge Tagline (Pill)</label>
                                <input type="text" name="sections[hero][badge]" value="{{ $sections['hero']['badge'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Title Line 1</label>
                                    <input type="text" name="sections[hero][title_line1]" value="{{ $sections['hero']['title_line1'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Title Highlight (Green)</label>
                                    <input type="text" name="sections[hero][title_highlight]" value="{{ $sections['hero']['title_highlight'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Title Line 2</label>
                                    <input type="text" name="sections[hero][title_line2]" value="{{ $sections['hero']['title_line2'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Subtitle / Supporting Description</label>
                                <textarea name="sections[hero][subtitle]" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">{{ $sections['hero']['subtitle'] ?? '' }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Rating Label</label>
                                    <input type="text" name="sections[hero][rating_label]" value="{{ $sections['hero']['rating_label'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Students Count Social Proof</label>
                                    <input type="text" name="sections[hero][students_count_text]" value="{{ $sections['hero']['students_count_text'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                            </div>

                            <!-- Hero Orbital Central Avatar Image -->
                            <div class="pt-2 border-t border-slate-100">
                                <label class="block font-semibold text-slate-700 mb-1.5">Hero Central Avatar (Orbital Centerpiece)</label>
                                <p class="text-[11px] text-slate-400 mb-2">Select a portrait photograph to display in the glowing concentric orbital track.</p>
                                <x-media-selector
                                    name="sections[hero][avatar_image]"
                                    :value="$sections['hero']['avatar_image'] ?? ''"
                                    shape="circle"
                                    defaultUrl="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80"
                                    buttonText="Choose Avatar from Media"
                                />
                            </div>

                            <!-- Floating Badges around orbit -->
                            <div class="pt-3 border-t border-slate-100 space-y-2">
                                <label class="block font-semibold text-slate-700">Orbital Floating Badges</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="p-3 bg-slate-50 border border-slate-200 space-y-2">
                                        <p class="font-medium text-slate-600">Badge 1 (Top Left)</p>
                                        <input type="text" name="sections[hero][badge_students_count]" value="{{ $sections['hero']['badge_students_count'] ?? '' }}" placeholder="Count (e.g. 25K+)" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                        <input type="text" name="sections[hero][badge_students_label]" value="{{ $sections['hero']['badge_students_label'] ?? '' }}" placeholder="Label (e.g. Enrolled)" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                    <div class="p-3 bg-slate-50 border border-slate-200 space-y-2">
                                        <p class="font-medium text-slate-600">Badge 2 (Top Right)</p>
                                        <input type="text" name="sections[hero][badge_courses_count]" value="{{ $sections['hero']['badge_courses_count'] ?? '' }}" placeholder="Count (e.g. 1,200+)" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                        <input type="text" name="sections[hero][badge_courses_label]" value="{{ $sections['hero']['badge_courses_label'] ?? '' }}" placeholder="Label (e.g. Courses)" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                    <div class="p-3 bg-slate-50 border border-slate-200 space-y-2">
                                        <p class="font-medium text-slate-600">Badge 3 (Bottom Left)</p>
                                        <input type="text" name="sections[hero][badge_tutors_count]" value="{{ $sections['hero']['badge_tutors_count'] ?? '' }}" placeholder="Count (e.g. 480+)" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                        <input type="text" name="sections[hero][badge_tutors_label]" value="{{ $sections['hero']['badge_tutors_label'] ?? '' }}" placeholder="Label (e.g. Tutors)" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 02. FOUR QUICK STATISTICS RIBBON -->
                    <div id="section-stats" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">02</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Statistics Ribbon</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Four quick counter cards displayed horizontally under the hero section.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[stats][enabled]" value="0">
                                <input type="checkbox" name="sections[stats][enabled]" value="1" class="sr-only peer" @checked($sections['stats']['enabled'] ?? true) data-toggle-section="stats">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="stats">{{ ($sections['stats']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="space-y-4">
                            <!-- Toggle for dynamic database computation -->
                            <div class="bg-slate-50 border border-slate-200 p-4 flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-800">Auto-calculate from platform database</p>
                                    <p class="text-[11px] text-slate-500">When checked, Student, Course, and Instructor counts are fetched live from your database. If unchecked, custom values below are shown.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                    <input type="hidden" name="sections[stats][use_dynamic_counts]" value="0">
                                    <input type="checkbox" name="sections[stats][use_dynamic_counts]" value="1" class="sr-only peer" @checked($sections['stats']['use_dynamic_counts'] ?? true)>
                                    <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                                <div class="p-3 bg-white border border-slate-200 space-y-2">
                                    <p class="font-semibold text-slate-700">Stat Card 1</p>
                                    <input type="text" name="sections[stats][stat1_value]" value="{{ $sections['stats']['stat1_value'] ?? '' }}" placeholder="Value (e.g. 25K+)" class="w-full px-2.5 py-1.5 border border-slate-200 text-xs">
                                    <input type="text" name="sections[stats][stat1_label]" value="{{ $sections['stats']['stat1_label'] ?? '' }}" placeholder="Label" class="w-full px-2.5 py-1.5 border border-slate-200 text-xs">
                                </div>
                                <div class="p-3 bg-white border border-slate-200 space-y-2">
                                    <p class="font-semibold text-slate-700">Stat Card 2</p>
                                    <input type="text" name="sections[stats][stat2_value]" value="{{ $sections['stats']['stat2_value'] ?? '' }}" placeholder="Value (e.g. 1,200+)" class="w-full px-2.5 py-1.5 border border-slate-200 text-xs">
                                    <input type="text" name="sections[stats][stat2_label]" value="{{ $sections['stats']['stat2_label'] ?? '' }}" placeholder="Label" class="w-full px-2.5 py-1.5 border border-slate-200 text-xs">
                                </div>
                                <div class="p-3 bg-white border border-slate-200 space-y-2">
                                    <p class="font-semibold text-slate-700">Stat Card 3</p>
                                    <input type="text" name="sections[stats][stat3_value]" value="{{ $sections['stats']['stat3_value'] ?? '' }}" placeholder="Value (e.g. 480+)" class="w-full px-2.5 py-1.5 border border-slate-200 text-xs">
                                    <input type="text" name="sections[stats][stat3_label]" value="{{ $sections['stats']['stat3_label'] ?? '' }}" placeholder="Label" class="w-full px-2.5 py-1.5 border border-slate-200 text-xs">
                                </div>
                                <div class="p-3 bg-white border border-slate-200 space-y-2">
                                    <p class="font-semibold text-slate-700">Stat Card 4</p>
                                    <input type="text" name="sections[stats][stat4_value]" value="{{ $sections['stats']['stat4_value'] ?? '' }}" placeholder="Value (e.g. 98.5%)" class="w-full px-2.5 py-1.5 border border-slate-200 text-xs">
                                    <input type="text" name="sections[stats][stat4_label]" value="{{ $sections['stats']['stat4_label'] ?? '' }}" placeholder="Label" class="w-full px-2.5 py-1.5 border border-slate-200 text-xs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 03. TOP CATEGORIES / SKILLS -->
                    <div id="section-categories" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">03</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Top Categories / Skills</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Section introducing categories and career pathways.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[categories][enabled]" value="0">
                                <input type="checkbox" name="sections[categories][enabled]" value="1" class="sr-only peer" @checked($sections['categories']['enabled'] ?? true) data-toggle-section="categories">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="categories">{{ ($sections['categories']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Eyebrow / Overline</label>
                                <input type="text" name="sections[categories][eyebrow]" value="{{ $sections['categories']['eyebrow'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Max Categories to Display</label>
                                <input type="number" min="1" max="24" name="sections[categories][limit]" value="{{ $sections['categories']['limit'] ?? 8 }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Section Heading</label>
                                <input type="text" name="sections[categories][heading]" value="{{ $sections['categories']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Subtitle / Subtext</label>
                                <textarea name="sections[categories][subtitle]" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">{{ $sections['categories']['subtitle'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 04. FEATURED COURSES 8-GRID -->
                    <div id="section-featured_courses" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">04</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Featured Courses Grid</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Highlighted courses marked as featured.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[featured_courses][enabled]" value="0">
                                <input type="checkbox" name="sections[featured_courses][enabled]" value="1" class="sr-only peer" @checked($sections['featured_courses']['enabled'] ?? true) data-toggle-section="featured_courses">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="featured_courses">{{ ($sections['featured_courses']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Eyebrow</label>
                                <input type="text" name="sections[featured_courses][eyebrow]" value="{{ $sections['featured_courses']['eyebrow'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Button Text</label>
                                <input type="text" name="sections[featured_courses][button_text]" value="{{ $sections['featured_courses']['button_text'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Max Items (Limit)</label>
                                <input type="number" min="1" max="24" name="sections[featured_courses][limit]" value="{{ $sections['featured_courses']['limit'] ?? 8 }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block font-semibold text-slate-700 mb-1">Section Heading</label>
                                <input type="text" name="sections[featured_courses][heading]" value="{{ $sections['featured_courses']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block font-semibold text-slate-700 mb-1">Subtitle</label>
                                <textarea name="sections[featured_courses][subtitle]" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">{{ $sections['featured_courses']['subtitle'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- Featured Courses Assignment Box -->
                        <div class="p-4 bg-slate-50 border border-slate-200 space-y-3">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                        <span class="text-amber-500">★</span>
                                        <span>Currently Featured Courses ({{ isset($featuredCourses) ? $featuredCourses->count() : 0 }})</span>
                                    </h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Courses marked as "Featured" display in this homepage grid. If none are explicitly marked, latest published courses display as fallback.</p>
                                </div>
                                <a href="{{ route('admin.courses.index', ['status' => 'featured']) }}" target="_blank" class="text-[11px] font-semibold text-[#344C3D] hover:underline flex items-center gap-1">
                                    <span>Manage in Courses Manager</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>

                            @if (isset($featuredCourses) && $featuredCourses->isNotEmpty())
                                <div class="divide-y divide-slate-200 bg-white border border-slate-200">
                                    @foreach ($featuredCourses as $fCourse)
                                        <div class="px-3.5 py-2.5 flex items-center justify-between gap-3 text-xs">
                                            <div class="min-w-0 flex-1">
                                                <p class="font-semibold text-slate-800 truncate">{{ $fCourse->title }}</p>
                                                <p class="text-[11px] text-slate-500 truncate">{{ $fCourse->instructor?->name ?? 'Instructor' }} · ${{ number_format($fCourse->price, 2) }}</p>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <a href="{{ route('admin.courses.edit', $fCourse) }}" target="_blank" class="px-2 py-1 text-[11px] text-slate-600 hover:text-slate-900 border border-slate-200">Edit</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-3 bg-white border border-dashed border-slate-300 text-center text-[11px] text-slate-500">
                                    No courses are specifically marked as Featured yet. The homepage will fallback to displaying latest published courses.
                                    <a href="{{ route('admin.courses.index') }}" class="text-[#344C3D] font-semibold underline ml-1">Go to Courses to feature courses</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 05. TRANSFORM ACCESS TO EDUCATION BANNER -->
                    <div id="section-banner_transform" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">05</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Transform Access Banner</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">High-contrast promotional callout banner.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[banner_transform][enabled]" value="0">
                                <input type="checkbox" name="sections[banner_transform][enabled]" value="1" class="sr-only peer" @checked($sections['banner_transform']['enabled'] ?? true) data-toggle-section="banner_transform">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="banner_transform">{{ ($sections['banner_transform']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Banner Headline</label>
                                <input type="text" name="sections[banner_transform][heading]" value="{{ $sections['banner_transform']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Description / Copy</label>
                                <textarea name="sections[banner_transform][description]" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">{{ $sections['banner_transform']['description'] ?? '' }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Button Text</label>
                                    <input type="text" name="sections[banner_transform][button_text]" value="{{ $sections['banner_transform']['button_text'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Button Destination URL</label>
                                    <input type="text" name="sections[banner_transform][button_url]" value="{{ $sections['banner_transform']['button_url'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 06. ACHIEVE GOALS WITH MONOLMS -->
                    <div id="section-goals" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">06</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Goals & Impact Metrics</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Split-row metrics highlighting global student reach, course library, and country footprint.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[goals][enabled]" value="0">
                                <input type="checkbox" name="sections[goals][enabled]" value="1" class="sr-only peer" @checked($sections['goals']['enabled'] ?? true) data-toggle-section="goals">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="goals">{{ ($sections['goals']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Section Heading</label>
                                <input type="text" name="sections[goals][heading]" value="{{ $sections['goals']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                                <div class="p-3 bg-slate-50 border border-slate-200 space-y-2">
                                    <p class="font-semibold text-slate-700">Metric 1 (Purple)</p>
                                    <input type="text" name="sections[goals][stat1_value]" value="{{ $sections['goals']['stat1_value'] ?? '' }}" placeholder="Number (e.g. 253,085)" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    <input type="text" name="sections[goals][stat1_label]" value="{{ $sections['goals']['stat1_label'] ?? '' }}" placeholder="Label" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                </div>
                                <div class="p-3 bg-slate-50 border border-slate-200 space-y-2">
                                    <p class="font-semibold text-slate-700">Metric 2 (Cyan)</p>
                                    <input type="text" name="sections[goals][stat2_value]" value="{{ $sections['goals']['stat2_value'] ?? '' }}" placeholder="Number (e.g. 1,205)" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    <input type="text" name="sections[goals][stat2_label]" value="{{ $sections['goals']['stat2_label'] ?? '' }}" placeholder="Label" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                </div>
                                <div class="p-3 bg-slate-50 border border-slate-200 space-y-2">
                                    <p class="font-semibold text-slate-700">Metric 3 (Green)</p>
                                    <input type="text" name="sections[goals][stat3_value]" value="{{ $sections['goals']['stat3_value'] ?? '' }}" placeholder="Number (e.g. 56)" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    <input type="text" name="sections[goals][stat3_label]" value="{{ $sections['goals']['stat3_label'] ?? '' }}" placeholder="Label" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 07. ACCELERATE CLOUD SUCCESS (3 SHOWCASES) -->
                    <div id="section-accelerate" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">07</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Accelerate Cloud Success (Showcases)</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Dark section featuring 3 alternating deep-dive showcases with layered imagery.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[accelerate][enabled]" value="0">
                                <input type="checkbox" name="sections[accelerate][enabled]" value="1" class="sr-only peer" @checked($sections['accelerate']['enabled'] ?? true) data-toggle-section="accelerate">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="accelerate">{{ ($sections['accelerate']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Section Main Heading</label>
                                    <input type="text" name="sections[accelerate][heading]" value="{{ $sections['accelerate']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Section Description</label>
                                    <textarea name="sections[accelerate][description]" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">{{ $sections['accelerate']['description'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- Showcase 1 -->
                            <div class="p-4 bg-slate-50 border border-slate-200 space-y-3">
                                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-none bg-amber-400"></span>
                                    <span>Showcase 1 (Trophy Card)</span>
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Title</label>
                                        <input type="text" name="sections[accelerate][showcase1_title]" value="{{ $sections['accelerate']['showcase1_title'] ?? '' }}" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Description</label>
                                        <input type="text" name="sections[accelerate][showcase1_desc]" value="{{ $sections['accelerate']['showcase1_desc'] ?? '' }}" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                    <x-media-selector
                                        name="sections[accelerate][showcase1_image1]"
                                        :value="$sections['accelerate']['showcase1_image1'] ?? ''"
                                        label="Primary Visual (Upper Card)"
                                        shape="wide"
                                        defaultUrl="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80"
                                    />
                                    <x-media-selector
                                        name="sections[accelerate][showcase1_image2]"
                                        :value="$sections['accelerate']['showcase1_image2'] ?? ''"
                                        label="Secondary Visual (Lower Card)"
                                        shape="wide"
                                        defaultUrl="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=700&auto=format&fit=crop&q=80"
                                    />
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    <div class="flex gap-2">
                                        <input type="text" name="sections[accelerate][showcase1_metric1_val]" value="{{ $sections['accelerate']['showcase1_metric1_val'] ?? '' }}" placeholder="Metric 1 Value (e.g. 1,500+)" class="w-1/2 px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                        <input type="text" name="sections[accelerate][showcase1_metric1_lbl]" value="{{ $sections['accelerate']['showcase1_metric1_lbl'] ?? '' }}" placeholder="Metric 1 Label" class="w-1/2 px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="text" name="sections[accelerate][showcase1_metric2_val]" value="{{ $sections['accelerate']['showcase1_metric2_val'] ?? '' }}" placeholder="Metric 2 Value (e.g. 95%)" class="w-1/2 px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                        <input type="text" name="sections[accelerate][showcase1_metric2_lbl]" value="{{ $sections['accelerate']['showcase1_metric2_lbl'] ?? '' }}" placeholder="Metric 2 Label" class="w-1/2 px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                </div>
                            </div>

                            <!-- Showcase 2 -->
                            <div class="p-4 bg-slate-50 border border-slate-200 space-y-3">
                                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-none bg-cyan-400"></span>
                                    <span>Showcase 2 (Certification Badge)</span>
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Title</label>
                                        <input type="text" name="sections[accelerate][showcase2_title]" value="{{ $sections['accelerate']['showcase2_title'] ?? '' }}" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Description</label>
                                        <input type="text" name="sections[accelerate][showcase2_desc]" value="{{ $sections['accelerate']['showcase2_desc'] ?? '' }}" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                    <x-media-selector
                                        name="sections[accelerate][showcase2_image1]"
                                        :value="$sections['accelerate']['showcase2_image1'] ?? ''"
                                        label="Primary Visual"
                                        shape="wide"
                                        defaultUrl="https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80"
                                    />
                                    <x-media-selector
                                        name="sections[accelerate][showcase2_image2]"
                                        :value="$sections['accelerate']['showcase2_image2'] ?? ''"
                                        label="Secondary Visual"
                                        shape="wide"
                                        defaultUrl="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=700&auto=format&fit=crop&q=80"
                                    />
                                </div>
                            </div>

                            <!-- Showcase 3 -->
                            <div class="p-4 bg-slate-50 border border-slate-200 space-y-3">
                                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-none bg-emerald-400"></span>
                                    <span>Showcase 3 (Enterprise Governance)</span>
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Title</label>
                                        <input type="text" name="sections[accelerate][showcase3_title]" value="{{ $sections['accelerate']['showcase3_title'] ?? '' }}" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Description</label>
                                        <input type="text" name="sections[accelerate][showcase3_desc]" value="{{ $sections['accelerate']['showcase3_desc'] ?? '' }}" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                    <x-media-selector
                                        name="sections[accelerate][showcase3_image1]"
                                        :value="$sections['accelerate']['showcase3_image1'] ?? ''"
                                        label="Primary Visual"
                                        shape="wide"
                                        defaultUrl="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80"
                                    />
                                    <x-media-selector
                                        name="sections[accelerate][showcase3_image2]"
                                        :value="$sections['accelerate']['showcase3_image2'] ?? ''"
                                        label="Secondary Visual"
                                        shape="wide"
                                        defaultUrl="https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=700&auto=format&fit=crop&q=80"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 08. MOST TRENDING COURSES -->
                    <div id="section-trending_courses" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">08</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Trending Courses</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Courses receiving peak enrollment traffic this week.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[trending_courses][enabled]" value="0">
                                <input type="checkbox" name="sections[trending_courses][enabled]" value="1" class="sr-only peer" @checked($sections['trending_courses']['enabled'] ?? true) data-toggle-section="trending_courses">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="trending_courses">{{ ($sections['trending_courses']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Eyebrow</label>
                                <input type="text" name="sections[trending_courses][eyebrow]" value="{{ $sections['trending_courses']['eyebrow'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Max Courses to Display (Limit)</label>
                                <input type="number" min="1" max="12" name="sections[trending_courses][limit]" value="{{ $sections['trending_courses']['limit'] ?? 8 }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Heading</label>
                                <input type="text" name="sections[trending_courses][heading]" value="{{ $sections['trending_courses']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Subtitle</label>
                                <textarea name="sections[trending_courses][subtitle]" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">{{ $sections['trending_courses']['subtitle'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- Trending Courses Assignment Box -->
                        <div class="p-4 bg-slate-50 border border-slate-200 space-y-3">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                        <span class="text-rose-500">🔥</span>
                                        <span>Currently Most Trending Courses ({{ isset($trendingCourses) ? $trendingCourses->count() : 0 }})</span>
                                    </h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Courses marked as "Most Trending" display in this featured strip. If none are explicitly marked, highest enrolled courses display as fallback.</p>
                                </div>
                                <a href="{{ route('admin.courses.index', ['status' => 'trending']) }}" target="_blank" class="text-[11px] font-semibold text-[#344C3D] hover:underline flex items-center gap-1">
                                    <span>Manage in Courses Manager</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>

                            @if (isset($trendingCourses) && $trendingCourses->isNotEmpty())
                                <div class="divide-y divide-slate-200 bg-white border border-slate-200">
                                    @foreach ($trendingCourses as $tCourse)
                                        <div class="px-3.5 py-2.5 flex items-center justify-between gap-3 text-xs">
                                            <div class="min-w-0 flex-1">
                                                <p class="font-semibold text-slate-800 truncate">{{ $tCourse->title }}</p>
                                                <p class="text-[11px] text-slate-500 truncate">{{ $tCourse->instructor?->name ?? 'Instructor' }} · ${{ number_format($tCourse->price, 2) }}</p>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <a href="{{ route('admin.courses.edit', $tCourse) }}" target="_blank" class="px-2 py-1 text-[11px] text-slate-600 hover:text-slate-900 border border-slate-200">Edit</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-3 bg-white border border-dashed border-slate-300 text-center text-[11px] text-slate-500">
                                    No courses are specifically marked as Trending yet. The homepage will fallback to displaying top enrolled courses.
                                    <a href="{{ route('admin.courses.index') }}" class="text-[#344C3D] font-semibold underline ml-1">Go to Courses to mark trending courses</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 09. REAL INSTRUCTORS -->
                    <div id="section-instructors" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">09</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Instructors Showcase</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Spotlight on active verified teaching faculty.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[instructors][enabled]" value="0">
                                <input type="checkbox" name="sections[instructors][enabled]" value="1" class="sr-only peer" @checked($sections['instructors']['enabled'] ?? true) data-toggle-section="instructors">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="instructors">{{ ($sections['instructors']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Eyebrow</label>
                                <input type="text" name="sections[instructors][eyebrow]" value="{{ $sections['instructors']['eyebrow'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Button Text</label>
                                <input type="text" name="sections[instructors][button_text]" value="{{ $sections['instructors']['button_text'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Heading</label>
                                <input type="text" name="sections[instructors][heading]" value="{{ $sections['instructors']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Subtitle</label>
                                <textarea name="sections[instructors][subtitle]" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">{{ $sections['instructors']['subtitle'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 10. BECOME AN INSTRUCTOR BANNER -->
                    <div id="section-banner_instructor" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">10</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Become An Instructor CTA Banner</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Instructor recruitment banner linking to instructor onboard or portal.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[banner_instructor][enabled]" value="0">
                                <input type="checkbox" name="sections[banner_instructor][enabled]" value="1" class="sr-only peer" @checked($sections['banner_instructor']['enabled'] ?? true) data-toggle-section="banner_instructor">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="banner_instructor">{{ ($sections['banner_instructor']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Headline</label>
                                <input type="text" name="sections[banner_instructor][heading]" value="{{ $sections['banner_instructor']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Description</label>
                                <textarea name="sections[banner_instructor][description]" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">{{ $sections['banner_instructor']['description'] ?? '' }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Button Text</label>
                                    <input type="text" name="sections[banner_instructor][button_text]" value="{{ $sections['banner_instructor']['button_text'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Button URL</label>
                                    <input type="text" name="sections[banner_instructor][button_url]" value="{{ $sections['banner_instructor']['button_url'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 11. PARTNER BRANDS & UNIVERSITIES -->
                    <div id="section-partners" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">11</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Partner Universities & Companies</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Logo ribbon displaying organizational trust marks and enterprise clients.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[partners][enabled]" value="0">
                                <input type="checkbox" name="sections[partners][enabled]" value="1" class="sr-only peer" @checked($sections['partners']['enabled'] ?? true) data-toggle-section="partners">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="partners">{{ ($sections['partners']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block font-semibold text-slate-700 mb-1">Section Heading</label>
                                    <input type="text" name="sections[partners][heading]" value="{{ $sections['partners']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Button Text</label>
                                    <input type="text" name="sections[partners][button_text]" value="{{ $sections['partners']['button_text'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-2">Partner Brand Names</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @for ($i = 0; $i < 6; $i++)
                                        <div class="p-2.5 bg-slate-50 border border-slate-200">
                                            <input type="text" name="sections[partners][partners_list][{{ $i }}][name]" value="{{ $sections['partners']['partners_list'][$i]['name'] ?? '' }}" placeholder="Partner {{ $i+1 }} Name" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-xs font-medium">
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 12. TESTIMONIALS -->
                    <div id="section-testimonials" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">12</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Student Testimonials</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Dotted world map background with featured review quote.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[testimonials][enabled]" value="0">
                                <input type="checkbox" name="sections[testimonials][enabled]" value="1" class="sr-only peer" @checked($sections['testimonials']['enabled'] ?? true) data-toggle-section="testimonials">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="testimonials">{{ ($sections['testimonials']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Headline</label>
                                    <input type="text" name="sections[testimonials][heading]" value="{{ $sections['testimonials']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Subtitle</label>
                                    <input type="text" name="sections[testimonials][subtitle]" value="{{ $sections['testimonials']['subtitle'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Featured Review Quote</label>
                                <textarea name="sections[testimonials][quote]" rows="3" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">{{ $sections['testimonials']['quote'] ?? '' }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Reviewer Name</label>
                                    <input type="text" name="sections[testimonials][author_name]" value="{{ $sections['testimonials']['author_name'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Reviewer Course / Title</label>
                                    <input type="text" name="sections[testimonials][author_role]" value="{{ $sections['testimonials']['author_role'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Rating (e.g. 4.8)</label>
                                    <input type="text" name="sections[testimonials][author_rating]" value="{{ $sections['testimonials']['author_rating'] ?? '4.5' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100">
                                <x-media-selector
                                    name="sections[testimonials][author_image]"
                                    :value="$sections['testimonials']['author_image'] ?? ''"
                                    label="Reviewer Profile Photo"
                                    shape="circle"
                                    defaultUrl="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- 13. LATEST NEWS & BLOG -->
                    <div id="section-blog" class="bg-white border border-slate-200 p-6 shadow-xs space-y-5 scroll-mt-6">
                        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold text-[#344C3D] bg-[#344C3D]/10 px-2 py-0.5 border border-[#344C3D]/20">13</span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Latest News & Events</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Editorial stories and announcements from the blog.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="hidden" name="sections[blog][enabled]" value="0">
                                <input type="checkbox" name="sections[blog][enabled]" value="1" class="sr-only peer" @checked($sections['blog']['enabled'] ?? true) data-toggle-section="blog">
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-none peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-none after:h-4 after:w-4 after:transition-all peer-checked:bg-[#344C3D]"></div>
                                <span class="ms-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700 peer-checked:text-[#344C3D]" data-status-label="blog">{{ ($sections['blog']['enabled'] ?? true) ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Section Heading</label>
                                <input type="text" name="sections[blog][heading]" value="{{ $sections['blog']['heading'] ?? '' }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Max Articles</label>
                                <input type="number" min="1" max="12" name="sections[blog][limit]" value="{{ $sections['blog']['limit'] ?? 4 }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344C3D]">
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Floating Actions -->
                    <div class="sticky bottom-6 z-30 bg-white/95 backdrop-blur-md border border-slate-200 p-4 shadow-lg flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <span class="w-2.5 h-2.5 bg-emerald-500 animate-pulse"></span>
                            <span class="font-mono text-xs">All 13 homepage sections configurable</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="#homepage-form" class="action-btn action-btn-ghost">
                                Back to Top ↑
                            </a>
                            <button type="submit" class="action-btn action-btn-primary">
                                <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- Client Script for Interactive Live Status Toggles & Scroll Highlighting -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Live Status Label updates when toggling switches
            document.querySelectorAll('[data-toggle-section]').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    var sec = this.getAttribute('data-toggle-section');
                    var label = document.querySelector('[data-status-label="' + sec + '"]');
                    var dot = document.querySelector('[data-nav-dot="' + sec + '"]');
                    if (this.checked) {
                        if (label) {
                            label.textContent = 'Active';
                            label.classList.add('text-[#344C3D]');
                            label.classList.remove('text-slate-400');
                        }
                        if (dot) {
                            dot.classList.remove('bg-slate-300');
                            dot.classList.add('bg-emerald-500');
                            dot.setAttribute('title', 'Enabled');
                        }
                    } else {
                        if (label) {
                            label.textContent = 'Hidden';
                            label.classList.remove('text-[#344C3D]');
                            label.classList.add('text-slate-400');
                        }
                        if (dot) {
                            dot.classList.remove('bg-emerald-500');
                            dot.classList.add('bg-slate-300');
                            dot.setAttribute('title', 'Hidden');
                        }
                    }
                });
            });

            // Active section indicator in sidebar on scroll
            var navLinks = document.querySelectorAll('#sections-quicknav [data-nav-target]');
            var sectionCards = Array.from(navLinks).map(function (link) {
                return document.getElementById('section-' + link.getAttribute('data-nav-target'));
            }).filter(Boolean);

            window.addEventListener('scroll', function () {
                var scrollPos = window.scrollY + 140;
                var currentSecId = null;

                sectionCards.forEach(function (card) {
                    if (card.offsetTop <= scrollPos) {
                        currentSecId = card.id.replace('section-', '');
                    }
                });

                if (currentSecId) {
                    navLinks.forEach(function (link) {
                        if (link.getAttribute('data-nav-target') === currentSecId) {
                            link.classList.add('bg-emerald-50', 'text-[#344C3D]', 'font-bold');
                        } else {
                            link.classList.remove('bg-emerald-50', 'text-[#344C3D]', 'font-bold');
                        }
                    });
                }
            }, { passive: true });
        });
    </script>

</x-layouts.dashboard>