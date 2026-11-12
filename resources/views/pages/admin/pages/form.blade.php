<x-layouts.dashboard :title="($page->exists ? 'Edit '.$page->title : 'Create New Page').' - Mono LMS'" :active="($page->exists && $page->system_key) ? 'page_'.$page->system_key : 'pages'" userType="admin">

    @php
        $isEdit = $page->exists;
        $currentTemplate = old('template', $page->template ?? 'standard');
        $publicUrl = $page->exists ? url($page->slug) : null;
    @endphp

    <form method="POST" action="{{ $isEdit ? route('admin.pages.update', $page) : route('admin.pages.store') }}" id="page-form">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <!-- Top Sticky Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-neutral-800">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.pages.index') }}" class="p-2 rounded-lg bg-white dark:bg-neutral-800 border border-slate-200 dark:border-neutral-700 text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors" title="Back to Pages">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ $isEdit ? 'Edit Page: '.$page->title : 'Create New Page' }}
                            </h1>
                            @if ($page->is_system)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 dark:bg-neutral-800 dark:text-neutral-300">System</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 dark:text-neutral-400 mt-0.5">
                            {{ $isEdit ? 'Configure content, layout, SEO, and permalink for this page.' : 'Create a new static or custom content page.' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    @if ($isEdit && $publicUrl)
                        <a href="{{ $publicUrl }}"
                           target="_blank"
                           class="action-btn action-btn-ghost text-xs flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            <span>View Live</span>
                        </a>
                    @endif
                    <button type="submit" class="action-btn action-btn-primary text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        <span>{{ $isEdit ? 'Save Changes' : 'Create Page' }}</span>
                    </button>
                </div>
            </div>

            <!-- Flash & Error Alerts -->
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
                    <p class="font-bold">Please correct the following errors:</p>
                    <ul class="list-disc list-inside mt-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Main Column (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Basic Information Card -->
                    <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl p-5 sm:p-6 shadow-xs">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <span>General Information</span>
                        </h2>

                        <div class="space-y-4">
                            <!-- Page Title -->
                            <div>
                                <label for="page_title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                    Page Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="page_title"
                                       name="title"
                                       value="{{ old('title', $page->title) }}"
                                       required
                                       placeholder="e.g. About Us"
                                       class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-neutral-800/60 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">
                            </div>

                            <!-- Permalink / URL Configuration Card -->
                            <div class="bg-slate-50 dark:bg-neutral-900/60 border border-slate-200 dark:border-neutral-800 rounded-xl p-4">
                                <label for="page_slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">
                                    Permalink / URL Slug <span class="text-red-500">*</span>
                                </label>
                                <p class="text-[11px] text-slate-500 dark:text-neutral-400 mb-2.5">
                                    Customize the exact public web address for this page. Must contain only letters, numbers, dashes, and underscores.
                                </p>

                                <div class="flex items-center rounded-lg border border-slate-300 dark:border-neutral-700 bg-white dark:bg-[#1a1a1a] overflow-hidden focus-within:ring-1 focus-within:ring-[var(--theme-primary,#344c3d)]">
                                    <span class="px-3 py-2 text-xs font-mono text-slate-400 dark:text-neutral-500 bg-slate-100 dark:bg-neutral-800 border-r border-slate-300 dark:border-neutral-700 select-none">
                                        {{ url('/') }}/
                                    </span>
                                    <input type="text"
                                           id="page_slug"
                                           name="slug"
                                           value="{{ old('slug', $page->slug) }}"
                                           required
                                           pattern="[a-zA-Z0-9\-_]+"
                                           placeholder="e.g. about"
                                           class="flex-1 px-3 py-2 text-xs font-mono text-slate-900 dark:text-white bg-transparent border-0 focus:outline-none">
                                </div>

                                <div class="mt-2.5 flex items-center justify-between text-xs text-slate-500 dark:text-neutral-400">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        Live URL: <code id="live-url-preview" class="font-mono text-slate-700 dark:text-neutral-300 font-semibold">{{ url('/') }}/{{ old('slug', $page->slug ?: 'page-slug') }}</code>
                                    </span>
                                    @if ($isEdit && $publicUrl)
                                        <a href="{{ $publicUrl }}" target="_blank" class="text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline flex items-center gap-1 text-[11px] font-semibold">
                                            <span>Open Page</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Eyebrow / Badge -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                        Eyebrow / Category Badge
                                    </label>
                                    <input type="text"
                                           name="settings[eyebrow]"
                                           value="{{ old('settings.eyebrow', $page->setting('eyebrow')) }}"
                                           placeholder="e.g. Our Purpose & Story"
                                           class="w-full px-4 py-2 text-sm bg-slate-50 dark:bg-neutral-800/60 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">
                                </div>

                                <!-- Subtitle -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                        Subtitle / Lead Text
                                    </label>
                                    <input type="text"
                                           name="settings[subtitle]"
                                           value="{{ old('settings.subtitle', $page->setting('subtitle')) }}"
                                           placeholder="Brief page intro displayed under the main title"
                                           class="w-full px-4 py-2 text-sm bg-slate-50 dark:bg-neutral-800/60 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Template Specific Content Editor -->
                    <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-neutral-800">
                            <div>
                                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">
                                    Template Content & Layout
                                </h2>
                                <p class="text-xs text-slate-500 dark:text-neutral-400 mt-0.5">
                                    Specific sections for template: <strong class="uppercase text-slate-700 dark:text-neutral-200" id="template-label">{{ $currentTemplate }}</strong>
                                </p>
                            </div>
                        </div>

                        <!-- 1. ABOUT TEMPLATE FIELDS -->
                        <div id="template-section-about" class="space-y-6 {{ $currentTemplate === 'about' ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-slate-50 dark:bg-neutral-900/60 p-4 rounded-xl border border-slate-200 dark:border-neutral-800">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Mission Card Title</label>
                                    <input type="text" name="settings[mission_title]" value="{{ old('settings.mission_title', $page->setting('mission_title', 'Our Mission')) }}" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg mb-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Mission Statement</label>
                                    <textarea name="settings[mission]" rows="3" class="w-full px-3 py-2 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">{{ old('settings.mission', $page->setting('mission')) }}</textarea>
                                </div>

                                <div class="bg-slate-50 dark:bg-neutral-900/60 p-4 rounded-xl border border-slate-200 dark:border-neutral-800">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Vision Card Title</label>
                                    <input type="text" name="settings[vision_title]" value="{{ old('settings.vision_title', $page->setting('vision_title', 'Our Vision')) }}" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg mb-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Vision Statement</label>
                                    <textarea name="settings[vision]" rows="3" class="w-full px-3 py-2 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">{{ old('settings.vision', $page->setting('vision')) }}</textarea>
                                </div>
                            </div>

                            <!-- Platform Story -->
                            <div class="bg-slate-50 dark:bg-neutral-900/60 p-4 rounded-xl border border-slate-200 dark:border-neutral-800 space-y-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300">Story Section Heading</label>
                                <input type="text" name="settings[story_title]" value="{{ old('settings.story_title', $page->setting('story_title')) }}" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300">Paragraph 1</label>
                                <textarea name="settings[story_p1]" rows="2" class="w-full px-3 py-2 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">{{ old('settings.story_p1', $page->setting('story_p1')) }}</textarea>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300">Paragraph 2</label>
                                <textarea name="settings[story_p2]" rows="2" class="w-full px-3 py-2 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">{{ old('settings.story_p2', $page->setting('story_p2')) }}</textarea>
                            </div>

                            <!-- 4 Key Stats -->
                            <div class="border border-slate-200 dark:border-neutral-800 rounded-xl p-4">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-3">4 Key Statistics (Value & Label)</h3>
                                @php
                                    $stats = $page->setting('stats', [
                                        ['value' => '25K+', 'label' => 'Active Learners'],
                                        ['value' => '1,200+', 'label' => 'Certified Courses'],
                                        ['value' => '480+', 'label' => 'Industry Mentors'],
                                        ['value' => '98.5%', 'label' => 'Satisfaction Rate'],
                                    ]);
                                @endphp
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="bg-slate-50 dark:bg-neutral-900/60 p-2.5 rounded-lg border border-slate-200 dark:border-neutral-800">
                                            <label class="text-[10px] font-bold uppercase text-slate-400">Stat #{{ $i + 1 }} Value</label>
                                            <input type="text" name="settings[stats][{{ $i }}][value]" value="{{ old("settings.stats.{$i}.value", $stats[$i]['value'] ?? '') }}" placeholder="e.g. 25K+" class="w-full px-2.5 py-1 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded mt-1 mb-1.5 font-bold">
                                            <label class="text-[10px] font-bold uppercase text-slate-400">Stat #{{ $i + 1 }} Label</label>
                                            <input type="text" name="settings[stats][{{ $i }}][label]" value="{{ old("settings.stats.{$i}.label", $stats[$i]['label'] ?? '') }}" placeholder="e.g. Active Learners" class="w-full px-2.5 py-1 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded mt-1">
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- 2. CONTACT TEMPLATE FIELDS -->
                        <div id="template-section-contact" class="space-y-4 {{ $currentTemplate === 'contact' ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Support Email</label>
                                    <input type="email" name="settings[email]" value="{{ old('settings.email', $page->setting('email', 'support@mono-lms.com')) }}" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Direct Hotline Phone</label>
                                    <input type="text" name="settings[phone]" value="{{ old('settings.phone', $page->setting('phone', '+1 (458) 877-7787')) }}" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Physical Address</label>
                                    <textarea name="settings[address]" rows="2" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">{{ old('settings.address', $page->setting('address', '1442 Crosswind Drive, Madisonville, KY 42431')) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Operating Hours</label>
                                    <textarea name="settings[office_hours]" rows="2" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">{{ old('settings.office_hours', $page->setting('office_hours', 'Mon – Fri: 9:00 AM – 6:00 PM EST')) }}</textarea>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Google Maps Embed URL (iframe src)</label>
                                <input type="url" name="settings[map_embed_url]" value="{{ old('settings.map_embed_url', $page->setting('map_embed_url')) }}" placeholder="https://www.google.com/maps/embed?..." class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">
                            </div>

                            <div class="p-4 bg-slate-50 dark:bg-neutral-900/60 border border-slate-200 dark:border-neutral-800 rounded-xl space-y-3">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="form_enabled" name="settings[form_enabled]" value="1" {{ old('settings.form_enabled', $page->setting('form_enabled', true)) ? 'checked' : '' }} class="w-4 h-4 rounded text-[var(--theme-primary,#344c3d)]">
                                    <label for="form_enabled" class="text-xs font-bold text-slate-800 dark:text-neutral-200">Enable Inbound Visitor Contact Form</label>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 dark:text-neutral-400 mb-1">Form Heading</label>
                                        <input type="text" name="settings[form_heading]" value="{{ old('settings.form_heading', $page->setting('form_heading', 'Send Us a Message')) }}" class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 dark:text-neutral-400 mb-1">Form Subtitle</label>
                                        <input type="text" name="settings[form_subtitle]" value="{{ old('settings.form_subtitle', $page->setting('form_subtitle', 'Fill out the form below and our coordinators will reply within 24 business hours.')) }}" class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded">
                                    </div>
                                </div>
                                <div class="pt-2 flex items-center justify-between text-xs text-slate-500">
                                    <span>Submissions arrive directly in the Admin Messages Inbox.</span>
                                    <a href="{{ route('admin.messages.index') }}" class="text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 font-semibold underline">View Inbound Messages &rarr;</a>
                                </div>
                            </div>
                        </div>

                        <!-- 3. PRIVACY & TNC TEMPLATE FIELDS -->
                        <div id="template-section-legal" class="space-y-4 {{ in_array($currentTemplate, ['privacy', 'tnc']) ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Effective / Revision Date</label>
                                    <input type="text" name="settings[effective_date]" value="{{ old('settings.effective_date', $page->setting('effective_date', date('F 1, Y'))) }}" placeholder="e.g. November 1, 2026" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1">Legal / DPO Contact Email</label>
                                    <input type="email" name="settings[dpo_email]" value="{{ old('settings.dpo_email', $page->setting('dpo_email', 'legal@mono-lms.com')) }}" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <!-- 4. INSTRUCTORS DIRECTORY TEMPLATE FIELDS -->
                        <div id="template-section-instructors" class="space-y-4 {{ $currentTemplate === 'instructors' ? '' : 'hidden' }}">
                            <div class="bg-slate-50 dark:bg-neutral-900/60 p-4 rounded-xl border border-slate-200 dark:border-neutral-800 space-y-3">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Bottom "Teach with Us" CTA Banner</h3>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="cta_banner_enabled" name="settings[cta_banner_enabled]" value="1" {{ old('settings.cta_banner_enabled', $page->setting('cta_banner_enabled', true)) ? 'checked' : '' }} class="w-4 h-4 rounded text-[var(--theme-primary,#344c3d)]">
                                    <label for="cta_banner_enabled" class="text-xs font-bold text-slate-800 dark:text-neutral-200">Show Bottom Call to Action Banner</label>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 dark:text-neutral-400 mb-1">CTA Heading</label>
                                        <input type="text" name="settings[cta_heading]" value="{{ old('settings.cta_heading', $page->setting('cta_heading', 'Are you passionate about teaching? Share your mastery.')) }}" class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 dark:text-neutral-400 mb-1">Button Text</label>
                                        <input type="text" name="settings[cta_button_text]" value="{{ old('settings.cta_button_text', $page->setting('cta_button_text', 'Become an Instructor')) }}" class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 dark:text-neutral-400 mb-1">CTA Subtitle</label>
                                    <textarea name="settings[cta_subtitle]" rows="2" class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded">{{ old('settings.cta_subtitle', $page->setting('cta_subtitle', 'Join our faculty of world-class instructors. Create interactive courses, mentor ambitious students, and earn recurring revenue.')) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 dark:text-neutral-400 mb-1">Button URL</label>
                                    <input type="text" name="settings[cta_button_url]" value="{{ old('settings.cta_button_url', $page->setting('cta_button_url', '/instructor/register')) }}" class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded">
                                </div>
                            </div>
                        </div>

                        <!-- 5. VISUAL DRAG & DROP PAGE BUILDER (No-Code Block Canvas) -->
                        <div class="mt-6 pt-5 border-t border-slate-100 dark:border-neutral-800" id="content-body-container">
                            <input type="hidden" name="settings_blocks_json" id="settings_blocks_json" value="{{ json_encode(old('settings.blocks', $page->setting('blocks', []))) }}">

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-200 dark:border-neutral-800">
                                <div>
                                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                                        <span>🎨 Visual Page Designer</span>
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">No-Code</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-neutral-400 mt-0.5">
                                        Build beautiful pages with pre-designed drag &amp; drop section blocks. No HTML or coding required.
                                    </p>
                                </div>

                                <!-- Editor Mode Toggle -->
                                <div class="inline-flex rounded-lg border border-slate-300 dark:border-neutral-700 p-0.5 bg-slate-100 dark:bg-neutral-800">
                                    <button type="button" id="tab-btn-visual" onclick="setBuilderTab('visual')"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors bg-white dark:bg-neutral-700 text-slate-900 dark:text-white shadow-xs">
                                        🎨 Visual Blocks
                                    </button>
                                    <button type="button" id="tab-btn-code" onclick="setBuilderTab('code')"
                                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors text-slate-600 dark:text-neutral-400 hover:text-slate-900">
                                        💻 Raw HTML
                                    </button>
                                </div>
                            </div>

                            <!-- Visual Blocks Panel -->
                            <div id="builder-panel-visual" class="space-y-4">
                                <!-- Top Toolbar -->
                                <div class="flex flex-wrap items-center justify-between gap-2.5 p-3.5 bg-slate-50 dark:bg-neutral-900/60 rounded-xl border border-slate-200 dark:border-neutral-800">
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="openBlockPicker()" class="action-btn action-btn-primary text-xs flex items-center gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                            <span>+ Add Section Block</span>
                                        </button>
                                        <button type="button" onclick="loadStarterTemplate()" class="action-btn action-btn-ghost text-xs border border-slate-300 dark:border-neutral-700 cursor-pointer">
                                            Load Starter Template
                                        </button>
                                    </div>

                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <span id="block-counter-badge" class="font-bold text-slate-800 dark:text-neutral-200">0 Sections</span>
                                        <span>&bull;</span>
                                        <button type="button" onclick="clearAllBlocks()" class="text-red-500 hover:underline cursor-pointer">Clear All</button>
                                    </div>
                                </div>

                                <!-- Dynamic Blocks Canvas -->
                                <div id="page-blocks-canvas" class="space-y-4 min-h-[160px]">
                                    <!-- Rendered dynamically by JavaScript -->
                                </div>

                                <!-- Bottom Quick Add Prompter -->
                                <div class="text-center py-6 border-2 border-dashed border-slate-200 dark:border-neutral-800 rounded-2xl bg-white dark:bg-neutral-900/30 hover:border-slate-400 transition-colors">
                                    <p class="text-xs text-slate-500 dark:text-neutral-400 mb-2">Want to add another section to this page?</p>
                                    <button type="button" onclick="openBlockPicker()" class="action-btn action-btn-primary text-xs inline-flex items-center gap-1.5 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        <span>+ Add Section Block</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Raw HTML Code Panel (Optional for advanced coders) -->
                            <div id="builder-panel-code" class="hidden space-y-2">
                                <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                    <span>Compiled HTML source (auto-synchronized with Visual Blocks):</span>
                                    <span class="font-mono text-[11px] text-slate-400">Read &amp; edit raw tags</span>
                                </div>
                                <textarea id="page_content"
                                          name="content"
                                          rows="14"
                                          placeholder="Enter page body content or HTML..."
                                          class="w-full px-4 py-3 text-xs font-mono bg-slate-50 dark:bg-neutral-800/60 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white leading-relaxed focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">{{ old('content', $page->content) }}</textarea>
                            </div>
                        </div>

                        <!-- Visual Block Picker Modal -->
                        <div id="block-picker-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
                            <div class="bg-white dark:bg-neutral-900 border border-slate-200 dark:border-neutral-800 rounded-2xl max-w-3xl w-full p-6 shadow-2xl max-h-[85vh] flex flex-col">
                                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-neutral-800">
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Choose a Section Block to Add</h3>
                                        <p class="text-xs text-slate-500 mt-0.5">Select any pre-designed section. You can customize text, images, and layout after adding.</p>
                                    </div>
                                    <button type="button" onclick="closeBlockPicker()" class="p-1 text-slate-400 hover:text-slate-800 dark:hover:text-white text-xl leading-none cursor-pointer">&times;</button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 py-5 overflow-y-auto pr-1">
                                    <!-- 1. Hero -->
                                    <button type="button" onclick="addBlock('hero')" class="p-4 rounded-xl border border-slate-200 dark:border-neutral-800 hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 text-left transition-all group cursor-pointer flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 flex items-center justify-center font-bold text-lg mb-2">🌟</div>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-700">Hero Header Banner</h4>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">High-impact headline banner with CTA buttons and optional hero graphic.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 mt-3 inline-block">+ Add Hero</span>
                                    </button>

                                    <!-- 2. Features Grid -->
                                    <button type="button" onclick="addBlock('features_grid')" class="p-4 rounded-xl border border-slate-200 dark:border-neutral-800 hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 text-left transition-all group cursor-pointer flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="w-9 h-9 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-800 dark:text-indigo-300 flex items-center justify-center font-bold text-lg mb-2">⚡</div>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-700">Features Grid</h4>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">Multi-column cards with icons, highlights, and benefit descriptions.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 mt-3 inline-block">+ Add Features</span>
                                    </button>

                                    <!-- 3. Split Content -->
                                    <button type="button" onclick="addBlock('split_content')" class="p-4 rounded-xl border border-slate-200 dark:border-neutral-800 hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 text-left transition-all group cursor-pointer flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-800 dark:text-blue-300 flex items-center justify-center font-bold text-lg mb-2">📖</div>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-700">Split Text &amp; Image</h4>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">Side-by-side narrative with checkmark bullet points and photo.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 mt-3 inline-block">+ Add Split Story</span>
                                    </button>

                                    <!-- 4. Stats Counter -->
                                    <button type="button" onclick="addBlock('stats')" class="p-4 rounded-xl border border-slate-200 dark:border-neutral-800 hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 text-left transition-all group cursor-pointer flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-300 flex items-center justify-center font-bold text-lg mb-2">📊</div>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-700">Key Statistics</h4>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">Large numbers highlighting students, course metrics, and satisfaction.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 mt-3 inline-block">+ Add Stats</span>
                                    </button>

                                    <!-- 5. FAQ Accordion -->
                                    <button type="button" onclick="addBlock('faq')" class="p-4 rounded-xl border border-slate-200 dark:border-neutral-800 hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 text-left transition-all group cursor-pointer flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 flex items-center justify-center font-bold text-lg mb-2">❓</div>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-700">FAQ Accordions</h4>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">Interactive click-to-expand questions and answers for visitors.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 mt-3 inline-block">+ Add FAQ</span>
                                    </button>

                                    <!-- 6. Call to Action Banner -->
                                    <button type="button" onclick="addBlock('cta_banner')" class="p-4 rounded-xl border border-slate-200 dark:border-neutral-800 hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 text-left transition-all group cursor-pointer flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 flex items-center justify-center font-bold text-lg mb-2">🚀</div>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-700">Call To Action Banner</h4>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">High-contrast conversion strip with bold prompt and action button.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 mt-3 inline-block">+ Add CTA</span>
                                    </button>

                                    <!-- 7. Testimonial -->
                                    <button type="button" onclick="addBlock('testimonial')" class="p-4 rounded-xl border border-slate-200 dark:border-neutral-800 hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 text-left transition-all group cursor-pointer flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="w-9 h-9 rounded-lg bg-rose-100 dark:bg-rose-900/60 text-rose-800 dark:text-rose-300 flex items-center justify-center font-bold text-lg mb-2">💬</div>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-700">Testimonial Quote</h4>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">Social proof quote card with 5 gold stars and author title.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 mt-3 inline-block">+ Add Testimonial</span>
                                    </button>

                                    <!-- 8. Video Showcase -->
                                    <button type="button" onclick="addBlock('video_embed')" class="p-4 rounded-xl border border-slate-200 dark:border-neutral-800 hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 text-left transition-all group cursor-pointer flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="w-9 h-9 rounded-lg bg-red-100 dark:bg-red-900/60 text-red-800 dark:text-red-300 flex items-center justify-center font-bold text-lg mb-2">🎥</div>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-700">Video Embed</h4>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">Embedded responsive video player for YouTube, Vimeo, or tutorials.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 mt-3 inline-block">+ Add Video</span>
                                    </button>

                                    <!-- 9. Rich Text -->
                                    <button type="button" onclick="addBlock('rich_text')" class="p-4 rounded-xl border border-slate-200 dark:border-neutral-800 hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 text-left transition-all group cursor-pointer flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-neutral-800 text-slate-800 dark:text-neutral-300 flex items-center justify-center font-bold text-lg mb-2">📝</div>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-700">Formatted Text Article</h4>
                                            <p class="text-[11px] text-slate-500 leading-relaxed">Structured text article paragraphs, headers, and bulleted content.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 mt-3 inline-block">+ Add Article</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO & Meta Configuration Card -->
                    <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl p-5 sm:p-6 shadow-xs">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">
                            Search Engine Optimization (SEO) & Social Sharing
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label for="meta_title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                    Meta Title (Browser Tab & Search Snippet)
                                </label>
                                <input type="text"
                                       id="meta_title"
                                       name="meta_title"
                                       value="{{ old('meta_title', $page->meta_title) }}"
                                       placeholder="e.g. About Us - Mono LMS"
                                       class="w-full px-4 py-2 text-sm bg-slate-50 dark:bg-neutral-800/60 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">
                            </div>

                            <div>
                                <label for="meta_description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                    Meta Description (Search Results Summary)
                                </label>
                                <textarea id="meta_description"
                                          name="meta_description"
                                          rows="3"
                                          placeholder="A compelling 1-2 sentence summary of this page for search engines..."
                                          class="w-full px-4 py-2 text-sm bg-slate-50 dark:bg-neutral-800/60 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">{{ old('meta_description', $page->meta_description) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                        Meta Keywords (Comma separated)
                                    </label>
                                    <input type="text"
                                           name="meta_keywords"
                                           value="{{ old('meta_keywords', $page->meta_keywords) }}"
                                           placeholder="online courses, education, about"
                                           class="w-full px-4 py-2 text-sm bg-slate-50 dark:bg-neutral-800/60 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                        OpenGraph Social Image URL
                                    </label>
                                    <input type="text"
                                           name="og_image"
                                           value="{{ old('og_image', $page->og_image) }}"
                                           placeholder="https://... or /images/banner.jpg"
                                           class="w-full px-4 py-2 text-sm bg-slate-50 dark:bg-neutral-800/60 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar Column (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Publishing & Status Card -->
                    <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl p-5 shadow-xs space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-neutral-800">
                            Publishing Status
                        </h3>

                        <!-- Status Selector -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                Visibility Status
                            </label>
                            <select name="status" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white font-medium focus:outline-none">
                                <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>🟢 Published (Publicly Accessible)</option>
                                <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>🟡 Draft (Admin Preview Only)</option>
                            </select>
                        </div>

                        <!-- Template Selector -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                Page Template
                            </label>
                            <select id="template_selector" name="template" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white font-medium focus:outline-none">
                                <option value="about" {{ $currentTemplate === 'about' ? 'selected' : '' }}>About Us Template</option>
                                <option value="contact" {{ $currentTemplate === 'contact' ? 'selected' : '' }}>Contact Us Template</option>
                                <option value="privacy" {{ $currentTemplate === 'privacy' ? 'selected' : '' }}>Privacy Policy Template</option>
                                <option value="tnc" {{ $currentTemplate === 'tnc' ? 'selected' : '' }}>Terms & Conditions Template</option>
                                <option value="instructors" {{ $currentTemplate === 'instructors' ? 'selected' : '' }}>Instructors Directory Template</option>
                                <option value="standard" {{ $currentTemplate === 'standard' ? 'selected' : '' }}>Standard / Custom Article</option>
                            </select>
                        </div>

                        @if ($isEdit)
                            <div class="pt-3 border-t border-slate-100 dark:border-neutral-800/80 text-xs text-slate-500 dark:text-neutral-400 space-y-1.5">
                                <div class="flex justify-between">
                                    <span>Created:</span>
                                    <span class="font-medium text-slate-700 dark:text-neutral-300">{{ $page->created_at?->format('M d, Y · h:i A') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Last Modified:</span>
                                    <span class="font-medium text-slate-700 dark:text-neutral-300">{{ $page->updated_at?->format('M d, Y · h:i A') }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="pt-3">
                            <button type="submit" class="w-full action-btn action-btn-primary text-xs justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                <span>{{ $isEdit ? 'Save Changes' : 'Create Page' }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Quick Navigation Links -->
                    <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl p-5 shadow-xs space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white pb-2 border-b border-slate-100 dark:border-neutral-800">
                            Quick System Pages
                        </h3>
                        <div class="space-y-1 text-xs">
                            <a href="{{ route('admin.pages.edit-by-slug', 'about') }}" class="flex items-center justify-between px-2.5 py-1.5 rounded hover:bg-slate-50 dark:hover:bg-neutral-800 text-slate-700 dark:text-neutral-300">
                                <span>About Us</span>
                                <code class="text-[10px] text-slate-400">/about</code>
                            </a>
                            <a href="{{ route('admin.pages.edit-by-slug', 'contact') }}" class="flex items-center justify-between px-2.5 py-1.5 rounded hover:bg-slate-50 dark:hover:bg-neutral-800 text-slate-700 dark:text-neutral-300">
                                <span>Contact Us</span>
                                <code class="text-[10px] text-slate-400">/contact</code>
                            </a>
                            <a href="{{ route('admin.pages.edit-by-slug', 'privacy') }}" class="flex items-center justify-between px-2.5 py-1.5 rounded hover:bg-slate-50 dark:hover:bg-neutral-800 text-slate-700 dark:text-neutral-300">
                                <span>Privacy Policy</span>
                                <code class="text-[10px] text-slate-400">/privacy</code>
                            </a>
                            <a href="{{ route('admin.pages.edit-by-slug', 'tnc') }}" class="flex items-center justify-between px-2.5 py-1.5 rounded hover:bg-slate-50 dark:hover:bg-neutral-800 text-slate-700 dark:text-neutral-300">
                                <span>Terms & Conditions</span>
                                <code class="text-[10px] text-slate-400">/terms</code>
                            </a>
                            <a href="{{ route('admin.pages.edit-by-slug', 'instructors') }}" class="flex items-center justify-between px-2.5 py-1.5 rounded hover:bg-slate-50 dark:hover:bg-neutral-800 text-slate-700 dark:text-neutral-300">
                                <span>Instructors Directory</span>
                                <code class="text-[10px] text-slate-400">/instructors</code>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        (function() {
            var titleInput = document.getElementById('page_title');
            var slugInput = document.getElementById('page_slug');
            var previewEl = document.getElementById('live-url-preview');
            var templateSelect = document.getElementById('template_selector');
            var templateLabel = document.getElementById('template-label');
            var baseUrl = @json(url('/'));
            var isNew = @json(! $isEdit);

            function updatePreview() {
                var cleanSlug = (slugInput.value || '').trim();
                if (previewEl) {
                    previewEl.textContent = baseUrl + '/' + (cleanSlug || 'page-slug');
                }
            }

            if (isNew && titleInput && slugInput) {
                titleInput.addEventListener('input', function() {
                    if (!slugInput.dataset.manual) {
                        var slug = titleInput.value.toLowerCase()
                            .replace(/[^a-z0-9\-_\s]/g, '')
                            .replace(/\s+/g, '-');
                        slugInput.value = slug;
                        updatePreview();
                    }
                });

                slugInput.addEventListener('input', function() {
                    slugInput.dataset.manual = '1';
                    updatePreview();
                });
            } else if (slugInput) {
                slugInput.addEventListener('input', updatePreview);
            }

            function syncTemplateUI(tpl) {
                if (templateLabel) templateLabel.textContent = tpl;
                var sections = {
                    about: document.getElementById('template-section-about'),
                    contact: document.getElementById('template-section-contact'),
                    legal: document.getElementById('template-section-legal'),
                    instructors: document.getElementById('template-section-instructors')
                };

                if (sections.about) sections.about.classList.toggle('hidden', tpl !== 'about');
                if (sections.contact) sections.contact.classList.toggle('hidden', tpl !== 'contact');
                if (sections.legal) sections.legal.classList.toggle('hidden', tpl !== 'privacy' && tpl !== 'tnc');
                if (sections.instructors) sections.instructors.classList.toggle('hidden', tpl !== 'instructors');
            }

            if (templateSelect) {
                templateSelect.addEventListener('change', function() {
                    syncTemplateUI(this.value);
                });
            }
        })();

        // ==========================================
        // Visual Drag & Drop Page Builder System
        // ==========================================
        (function() {
            var rawBlocks = @json(old('settings.blocks', $page->setting('blocks', [])));
            var blocks = Array.isArray(rawBlocks) ? rawBlocks : [];
            var isNew = @json(! $isEdit);
            var isStandard = @json($currentTemplate === 'standard');

            var canvas = document.getElementById('page-blocks-canvas');
            var jsonInput = document.getElementById('settings_blocks_json');
            var htmlTextarea = document.getElementById('page_content');
            var counterBadge = document.getElementById('block-counter-badge');
            var pickerModal = document.getElementById('block-picker-modal');

            var draggedBlockIndex = null;

            function uid() {
                return 'blk_' + Math.random().toString(36).substr(2, 9);
            }

            function escapeHtml(str) {
                if (!str) return '';
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            // Starter landing page template for newcomers
            function defaultStarterBlocks() {
                return [
                    {
                        id: uid(),
                        type: 'hero',
                        collapsed: false,
                        data: {
                            eyebrow: 'Welcome to Mono LMS',
                            title: 'Master New Skills with World-Class Mentors',
                            subtitle: 'Explore high-demand certified courses, join live cohort classes, and transform your career at your own pace.',
                            btn_primary_label: 'Explore Courses',
                            btn_primary_url: '/courses',
                            btn_secondary_label: 'Meet Mentors',
                            btn_secondary_url: '/instructors',
                            bg_theme: 'dark',
                            image: ''
                        }
                    },
                    {
                        id: uid(),
                        type: 'features_grid',
                        collapsed: false,
                        data: {
                            eyebrow: 'Why Choose Us',
                            title: 'Designed for Real Career Growth',
                            subtitle: 'Everything you need to learn, build practical projects, and get certified.',
                            columns: 3,
                            items: [
                                { icon: 'trophy', title: 'Accredited Certificates', description: 'Earn verifiable digital certificates to showcase on LinkedIn and resumes.' },
                                { icon: 'users', title: '1-on-1 Mentorship', description: 'Get direct feedback and code reviews from industry leaders.' },
                                { icon: 'rocket', title: 'Interactive Projects', description: 'Build portfolio-ready applications with hands-on practice quizzes.' }
                            ]
                        }
                    },
                    {
                        id: uid(),
                        type: 'split_content',
                        collapsed: false,
                        data: {
                            eyebrow: 'Collaborative Learning',
                            title: 'Learn at Your Own Pace, Anywhere in the World',
                            description: 'Our platform combines flexible video lessons with live interactive classrooms and dedicated student community support.',
                            image: '',
                            image_position: 'right',
                            points: [
                                'Lifetime access to all enrolled course materials and updates',
                                'Direct instructor messaging and community discussion forums',
                                'Downloadable source code, slides, and cheat sheets'
                            ],
                            btn_label: 'View All Programs',
                            btn_url: '/courses'
                        }
                    },
                    {
                        id: uid(),
                        type: 'stats',
                        collapsed: false,
                        data: {
                            title: 'Our Global Impact in Numbers',
                            items: [
                                { value: '25,000+', label: 'Enrolled Learners' },
                                { value: '450+', label: 'Verified Courses' },
                                { value: '98.5%', label: 'Completion Rate' },
                                { value: '50+', label: 'Global Mentors' }
                            ]
                        }
                    },
                    {
                        id: uid(),
                        type: 'faq',
                        collapsed: false,
                        data: {
                            title: 'Frequently Asked Questions',
                            subtitle: 'Have questions? Here are quick answers to the most common queries.',
                            items: [
                                { question: 'How do I access course materials after enrolling?', answer: 'Immediately upon completing your checkout, your enrolled courses appear in your Student Dashboard with instant lifetime streaming access.' },
                                { question: 'Do you offer certificates of completion?', answer: 'Yes! Every completed course generates a verifiable digital certificate with unique QR code verification.' },
                                { question: 'Can I request a refund if I am not satisfied?', answer: 'We offer a 30-day money-back guarantee on all eligible standard courses.' }
                            ]
                        }
                    },
                    {
                        id: uid(),
                        type: 'cta_banner',
                        collapsed: false,
                        data: {
                            eyebrow: 'Start Your Journey',
                            title: 'Ready to Level Up Your Professional Skills?',
                            subtitle: 'Join over 25,000 students already building their future with Mono LMS.',
                            btn_label: 'Get Started Today',
                            btn_url: '/register'
                        }
                    }
                ];
            }

            // Auto-load starter template on brand new standard page
            if (isNew && isStandard && blocks.length === 0) {
                blocks = defaultStarterBlocks();
            }

            function compileHtml() {
                var htmlParts = [];
                blocks.forEach(function(blk) {
                    var t = blk.type;
                    var d = blk.data || {};

                    if (t === 'hero') {
                        htmlParts.push('<div class="block-hero my-8 p-8 sm:p-12 bg-slate-900 text-white rounded-2xl text-center"><h1 class="text-3xl sm:text-5xl font-extrabold mb-4">' + escapeHtml(d.title) + '</h1>' + (d.subtitle ? '<p class="text-slate-300 text-base sm:text-lg max-w-2xl mx-auto mb-6">' + escapeHtml(d.subtitle) + '</p>' : '') + (d.btn_primary_label ? '<a href="' + escapeHtml(d.btn_primary_url || '#') + '" class="inline-block px-6 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm">' + escapeHtml(d.btn_primary_label) + '</a>' : '') + '</div>');
                    } else if (t === 'features_grid') {
                        var items = (d.items || []).map(function(it) {
                            return '<div class="p-6 rounded-xl border border-slate-200 bg-white shadow-xs"><h3 class="text-lg font-bold text-slate-900 mb-2">' + escapeHtml(it.title) + '</h3><p class="text-sm text-slate-600 leading-relaxed">' + escapeHtml(it.description) + '</p></div>';
                        }).join('');
                        htmlParts.push('<div class="block-features my-12"><h2 class="text-2xl sm:text-3xl font-bold text-slate-900 text-center mb-8">' + escapeHtml(d.title) + '</h2><div class="grid grid-cols-1 sm:grid-cols-3 gap-6">' + items + '</div></div>');
                    } else if (t === 'faq') {
                        var faqs = (d.items || []).map(function(f) {
                            return '<details class="group border border-slate-200 rounded-xl p-4 bg-slate-50 mb-3"><summary class="font-bold text-sm text-slate-900 cursor-pointer">' + escapeHtml(f.question) + '</summary><p class="text-xs text-slate-600 mt-2">' + escapeHtml(f.answer) + '</p></details>';
                        }).join('');
                        htmlParts.push('<div class="block-faq my-12"><h2 class="text-2xl font-bold text-slate-900 text-center mb-6">' + escapeHtml(d.title) + '</h2><div class="max-w-3xl mx-auto">' + faqs + '</div></div>');
                    } else if (t === 'cta_banner') {
                        htmlParts.push('<div class="block-cta my-12 p-8 rounded-2xl bg-[#344c3d] text-white text-center"><h2 class="text-2xl font-bold mb-3">' + escapeHtml(d.title) + '</h2>' + (d.subtitle ? '<p class="text-slate-200 text-sm mb-4">' + escapeHtml(d.subtitle) + '</p>' : '') + '<a href="' + escapeHtml(d.btn_url || '#') + '" class="inline-block px-6 py-2.5 rounded bg-white text-[#344c3d] font-bold text-xs">' + escapeHtml(d.btn_label) + '</a></div>');
                    } else if (t === 'rich_text') {
                        htmlParts.push('<div class="block-rich my-8">' + (d.body || '') + '</div>');
                    }
                });
                return htmlParts.join('\n\n');
            }

            function syncPageState() {
                if (jsonInput) jsonInput.value = JSON.stringify(blocks);
                if (counterBadge) counterBadge.textContent = blocks.length + ' Section' + (blocks.length === 1 ? '' : 's');

                // Compile into raw html textarea if user is not in code mode
                if (htmlTextarea && document.getElementById('builder-panel-code').classList.contains('hidden')) {
                    var compiled = compileHtml();
                    if (compiled) htmlTextarea.value = compiled;
                }
            }

            function blockMeta(type) {
                switch(type) {
                    case 'hero': return { name: 'Hero Header Banner', icon: '🌟', color: 'emerald' };
                    case 'features_grid': return { name: 'Features Grid', icon: '⚡', color: 'indigo' };
                    case 'split_content': return { name: 'Split Text & Image', icon: '📖', color: 'blue' };
                    case 'stats': return { name: 'Key Statistics', icon: '📊', color: 'purple' };
                    case 'faq': return { name: 'FAQ Accordions', icon: '❓', color: 'amber' };
                    case 'cta_banner': return { name: 'Call To Action Banner', icon: '🚀', color: 'emerald' };
                    case 'testimonial': return { name: 'Testimonial Quote', icon: '💬', color: 'rose' };
                    case 'video_embed': return { name: 'Video Embed', icon: '🎥', color: 'red' };
                    case 'rich_text': return { name: 'Formatted Text Article', icon: '📝', color: 'slate' };
                    default: return { name: 'Section Block', icon: '📦', color: 'slate' };
                }
            }

            function renderBlocks() {
                if (!canvas) return;
                canvas.innerHTML = '';

                if (blocks.length === 0) {
                    canvas.innerHTML = `
                        <div class="p-8 text-center bg-slate-50 dark:bg-neutral-900/40 rounded-2xl border-2 border-dashed border-slate-200 dark:border-neutral-800">
                            <p class="text-sm font-semibold text-slate-700 dark:text-neutral-300">Your page is currently empty</p>
                            <p class="text-xs text-slate-500 mt-1 mb-4">Click below to add your first visual section, or load the starter template.</p>
                            <div class="flex items-center justify-center gap-3">
                                <button type="button" onclick="openBlockPicker()" class="action-btn action-btn-primary text-xs">+ Add Section Block</button>
                                <button type="button" onclick="loadStarterTemplate()" class="action-btn action-btn-ghost text-xs border border-slate-300">Load Starter Template</button>
                            </div>
                        </div>
                    `;
                    syncPageState();
                    return;
                }

                blocks.forEach(function(blk, idx) {
                    var meta = blockMeta(blk.type);
                    var d = blk.data || {};
                    var card = document.createElement('div');
                    card.className = 'block-card border border-slate-200 dark:border-neutral-800 rounded-2xl bg-white dark:bg-[#111111] shadow-2xs overflow-hidden transition-all';
                    card.draggable = true;
                    card.dataset.index = idx;

                    var isCollapsed = blk.collapsed === true;

                    var cardHeader = `
                        <div class="flex items-center justify-between gap-3 p-3.5 bg-slate-50 dark:bg-neutral-900/60 border-b border-slate-100 dark:border-neutral-800 select-none">
                            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                <span class="drag-handle cursor-grab active:cursor-grabbing p-1 text-slate-400 hover:text-slate-700" title="Drag to reorder section">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-12a2 2 0 10.001 4.001A2 2 0 0013 2zm0 6a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/></svg>
                                </span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-200 dark:bg-neutral-800 text-slate-700 dark:text-neutral-300 font-mono">#${idx + 1}</span>
                                <span class="text-base">${meta.icon}</span>
                                <span class="text-xs font-bold text-slate-900 dark:text-white truncate">${meta.name}</span>
                                <span class="text-[11px] text-slate-400 font-normal truncate hidden sm:inline">&mdash; "${escapeHtml(d.title || d.eyebrow || 'Untitled')}"</span>
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0">
                                <!-- Collapse Toggle -->
                                <button type="button" onclick="toggleCollapse(${idx})" class="h-7 w-7 text-xs border border-slate-300 dark:border-neutral-700 rounded bg-white dark:bg-neutral-800 text-slate-600 dark:text-neutral-400 cursor-pointer" title="${isCollapsed ? 'Expand' : 'Collapse'}">
                                    ${isCollapsed ? '▸' : '▾'}
                                </button>
                                <!-- Move Up/Down -->
                                <button type="button" onclick="moveBlock(${idx}, -1)" ${idx === 0 ? 'disabled' : ''} class="h-7 w-7 text-xs border border-slate-300 dark:border-neutral-700 rounded bg-white dark:bg-neutral-800 text-slate-600 dark:text-neutral-400 disabled:opacity-30 cursor-pointer" title="Move Up">&uarr;</button>
                                <button type="button" onclick="moveBlock(${idx}, 1)" ${idx === blocks.length - 1 ? 'disabled' : ''} class="h-7 w-7 text-xs border border-slate-300 dark:border-neutral-700 rounded bg-white dark:bg-neutral-800 text-slate-600 dark:text-neutral-400 disabled:opacity-30 cursor-pointer" title="Move Down">&darr;</button>
                                <!-- Duplicate -->
                                <button type="button" onclick="duplicateBlock(${idx})" class="h-7 px-2 text-[10px] font-bold uppercase border border-slate-300 dark:border-neutral-700 rounded bg-white dark:bg-neutral-800 text-slate-700 dark:text-neutral-300 cursor-pointer" title="Duplicate block">❐ Duplicate</button>
                                <!-- Delete -->
                                <button type="button" onclick="deleteBlock(${idx})" class="h-7 w-7 text-xs border border-slate-300 dark:border-neutral-700 rounded bg-white dark:bg-neutral-800 text-red-500 hover:bg-red-50 cursor-pointer" title="Delete block">&times;</button>
                            </div>
                        </div>
                    `;

                    var bodyContent = '';
                    if (!isCollapsed) {
                        bodyContent = `<div class="p-5 space-y-4">` + renderBlockForm(blk, idx) + `</div>`;
                    }

                    card.innerHTML = cardHeader + bodyContent;

                    // Drag event listeners for reordering
                    card.addEventListener('dragstart', function(e) {
                        draggedBlockIndex = idx;
                        e.dataTransfer.effectAllowed = 'move';
                        card.classList.add('opacity-40');
                    });
                    card.addEventListener('dragend', function() {
                        card.classList.remove('opacity-40');
                        document.querySelectorAll('.block-card').forEach(function(c) { c.classList.remove('border-emerald-600'); });
                    });
                    card.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        if (draggedBlockIndex !== idx) card.classList.add('border-emerald-600');
                    });
                    card.addEventListener('dragleave', function() {
                        card.classList.remove('border-emerald-600');
                    });
                    card.addEventListener('drop', function(e) {
                        e.preventDefault();
                        card.classList.remove('border-emerald-600');
                        if (draggedBlockIndex !== null && draggedBlockIndex !== idx) {
                            var moving = blocks.splice(draggedBlockIndex, 1)[0];
                            blocks.splice(idx, 0, moving);
                            renderBlocks();
                        }
                    });

                    canvas.appendChild(card);
                });

                // Attach change listeners to form inputs
                canvas.querySelectorAll('input, select, textarea').forEach(function(inp) {
                    inp.addEventListener('input', function() {
                        var bIdx = parseInt(this.dataset.bidx);
                        var f = this.dataset.field;
                        var itIdx = this.dataset.itemidx !== undefined ? parseInt(this.dataset.itemidx) : null;
                        var ptIdx = this.dataset.ptidx !== undefined ? parseInt(this.dataset.ptidx) : null;

                        if (itIdx !== null) {
                            if (blocks[bIdx] && blocks[bIdx].data && blocks[bIdx].data.items && blocks[bIdx].data.items[itIdx]) {
                                blocks[bIdx].data.items[itIdx][f] = this.value;
                            }
                        } else if (ptIdx !== null) {
                            if (blocks[bIdx] && blocks[bIdx].data && blocks[bIdx].data.points) {
                                blocks[bIdx].data.points[ptIdx] = this.value;
                            }
                        } else {
                            if (blocks[bIdx] && blocks[bIdx].data) {
                                blocks[bIdx].data[f] = this.value;
                            }
                        }
                        syncPageState();
                    });
                });

                syncPageState();
            }

            function renderBlockForm(blk, bIdx) {
                var t = blk.type;
                var d = blk.data || {};

                switch(t) {
                    case 'hero':
                        return `
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Eyebrow / Badge Text</label>
                                    <input type="text" value="${escapeHtml(d.eyebrow)}" placeholder="e.g. Welcome to Mono LMS" data-bidx="${bIdx}" data-field="eyebrow" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Background Theme</label>
                                    <select data-bidx="${bIdx}" data-field="bg_theme" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg font-medium">
                                        <option value="dark" ${d.bg_theme === 'dark' ? 'selected' : ''}>Signature Midnight Dark</option>
                                        <option value="forest" ${d.bg_theme === 'forest' ? 'selected' : ''}>Forest Emerald Accent</option>
                                        <option value="light" ${d.bg_theme === 'light' ? 'selected' : ''}>Clean Crisp Light</option>
                                        <option value="gradient" ${d.bg_theme === 'gradient' ? 'selected' : ''}>Deep Gradient</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Hero Headline Title</label>
                                <input type="text" value="${escapeHtml(d.title)}" placeholder="Main Catchy Title" data-bidx="${bIdx}" data-field="title" class="w-full px-3 py-2 text-sm font-bold bg-slate-50 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Subtitle / Supporting Message</label>
                                <textarea rows="2" data-bidx="${bIdx}" data-field="subtitle" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg">${escapeHtml(d.subtitle)}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Primary Button Label</label>
                                    <input type="text" value="${escapeHtml(d.btn_primary_label)}" placeholder="e.g. Explore Courses" data-bidx="${bIdx}" data-field="btn_primary_label" class="w-full px-2.5 py-1 text-xs bg-white border border-slate-300 rounded">
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mt-2 mb-1">Primary Button URL</label>
                                    <input type="text" value="${escapeHtml(d.btn_primary_url)}" placeholder="/courses" data-bidx="${bIdx}" data-field="btn_primary_url" class="w-full px-2.5 py-1 text-xs font-mono bg-white border border-slate-300 rounded">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Secondary Button Label (Optional)</label>
                                    <input type="text" value="${escapeHtml(d.btn_secondary_label)}" placeholder="e.g. Meet Mentors" data-bidx="${bIdx}" data-field="btn_secondary_label" class="w-full px-2.5 py-1 text-xs bg-white border border-slate-300 rounded">
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mt-2 mb-1">Secondary Button URL</label>
                                    <input type="text" value="${escapeHtml(d.btn_secondary_url)}" placeholder="/instructors" data-bidx="${bIdx}" data-field="btn_secondary_url" class="w-full px-2.5 py-1 text-xs font-mono bg-white border border-slate-300 rounded">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Hero Image URL (Optional graphic)</label>
                                <input type="text" value="${escapeHtml(d.image)}" placeholder="https://... or /images/hero.jpg" data-bidx="${bIdx}" data-field="image" class="w-full px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-300 rounded-lg">
                            </div>
                        `;

                    case 'features_grid':
                        var fHtml = (d.items || []).map(function(item, itIdx) {
                            return `
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2 relative">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <label class="text-[10px] font-bold text-slate-500">Icon:</label>
                                            <select data-bidx="${bIdx}" data-itemidx="${itIdx}" data-field="icon" class="text-[10px] border border-slate-300 rounded px-1 py-0.5 bg-white">
                                                <option value="trophy" ${item.icon === 'trophy' ? 'selected' : ''}>🏆 Trophy</option>
                                                <option value="book" ${item.icon === 'book' ? 'selected' : ''}>📚 Book</option>
                                                <option value="shield" ${item.icon === 'shield' ? 'selected' : ''}>🛡️ Shield</option>
                                                <option value="users" ${item.icon === 'users' ? 'selected' : ''}>👥 Community</option>
                                                <option value="rocket" ${item.icon === 'rocket' ? 'selected' : ''}>🚀 Rocket</option>
                                                <option value="zap" ${item.icon === 'zap' ? 'selected' : ''}>⚡ Fast</option>
                                                <option value="heart" ${item.icon === 'heart' ? 'selected' : ''}>❤️ Heart</option>
                                            </select>
                                        </div>
                                        <button type="button" onclick="deleteSubItem(${bIdx}, 'items', ${itIdx})" class="text-xs text-red-500 hover:text-red-700">&times; Remove</button>
                                    </div>
                                    <input type="text" value="${escapeHtml(item.title)}" placeholder="Feature Title" data-bidx="${bIdx}" data-itemidx="${itIdx}" data-field="title" class="w-full px-2.5 py-1 text-xs font-bold bg-white border border-slate-300 rounded">
                                    <textarea rows="2" placeholder="Description" data-bidx="${bIdx}" data-itemidx="${itIdx}" data-field="description" class="w-full px-2.5 py-1 text-xs bg-white border border-slate-300 rounded">${escapeHtml(item.description)}</textarea>
                                </div>
                            `;
                        }).join('');

                        return `
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Section Title</label>
                                    <input type="text" value="${escapeHtml(d.title)}" placeholder="Key Features" data-bidx="${bIdx}" data-field="title" class="w-full px-3 py-1.5 text-xs font-bold bg-slate-50 border border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Columns Layout</label>
                                    <select data-bidx="${bIdx}" data-field="columns" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg font-medium">
                                        <option value="2" ${d.columns == 2 ? 'selected' : ''}>2 Columns (Wide)</option>
                                        <option value="3" ${d.columns == 3 || !d.columns ? 'selected' : ''}>3 Columns (Standard)</option>
                                        <option value="4" ${d.columns == 4 ? 'selected' : ''}>4 Columns (Compact)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-2">
                                <span class="text-xs font-bold text-slate-800">Feature Cards (${(d.items || []).length})</span>
                                <button type="button" onclick="addFeatureItem(${bIdx})" class="h-6 px-2 text-[10px] font-bold uppercase bg-neutral-900 text-white rounded cursor-pointer">+ Add Feature</button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">${fHtml}</div>
                        `;

                    case 'split_content':
                        var ptsHtml = (d.points || []).map(function(pt, ptIdx) {
                            return `
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-emerald-600 font-bold">✓</span>
                                    <input type="text" value="${escapeHtml(pt)}" placeholder="Bullet point text" data-bidx="${bIdx}" data-ptidx="${ptIdx}" class="flex-1 px-2.5 py-1 text-xs bg-white border border-slate-300 rounded">
                                    <button type="button" onclick="deletePoint(${bIdx}, ${ptIdx})" class="text-red-500 text-xs px-1">&times;</button>
                                </div>
                            `;
                        }).join('');

                        return `
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Section Title</label>
                                    <input type="text" value="${escapeHtml(d.title)}" placeholder="Story Headline" data-bidx="${bIdx}" data-field="title" class="w-full px-3 py-1.5 text-xs font-bold bg-slate-50 border border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Image Side</label>
                                    <select data-bidx="${bIdx}" data-field="image_position" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg font-medium">
                                        <option value="right" ${d.image_position === 'right' ? 'selected' : ''}>Image on Right</option>
                                        <option value="left" ${d.image_position === 'left' ? 'selected' : ''}>Image on Left</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Story Paragraph Description</label>
                                <textarea rows="3" data-bidx="${bIdx}" data-field="description" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg">${escapeHtml(d.description)}</textarea>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800">Checkmark Bullet Points</span>
                                    <button type="button" onclick="addPoint(${bIdx})" class="h-6 px-2 text-[10px] font-bold uppercase bg-neutral-900 text-white rounded cursor-pointer">+ Add Point</button>
                                </div>
                                <div class="space-y-1.5">${ptsHtml}</div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Image URL</label>
                                    <input type="text" value="${escapeHtml(d.image)}" placeholder="https://..." data-bidx="${bIdx}" data-field="image" class="w-full px-2.5 py-1 text-xs font-mono bg-slate-50 border border-slate-300 rounded">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Button Label (Optional)</label>
                                    <input type="text" value="${escapeHtml(d.btn_label)}" placeholder="e.g. Learn More" data-bidx="${bIdx}" data-field="btn_label" class="w-full px-2.5 py-1 text-xs bg-slate-50 border border-slate-300 rounded">
                                </div>
                            </div>
                        `;

                    case 'faq':
                        var faqHtml = (d.items || []).map(function(item, itIdx) {
                            return `
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase">Question #${itIdx + 1}</span>
                                        <button type="button" onclick="deleteSubItem(${bIdx}, 'items', ${itIdx})" class="text-xs text-red-500">&times; Delete</button>
                                    </div>
                                    <input type="text" value="${escapeHtml(item.question)}" placeholder="Frequently Asked Question" data-bidx="${bIdx}" data-itemidx="${itIdx}" data-field="question" class="w-full px-2.5 py-1 text-xs font-bold bg-white border border-slate-300 rounded">
                                    <textarea rows="2" placeholder="Answer text..." data-bidx="${bIdx}" data-itemidx="${itIdx}" data-field="answer" class="w-full px-2.5 py-1 text-xs bg-white border border-slate-300 rounded">${escapeHtml(item.answer)}</textarea>
                                </div>
                            `;
                        }).join('');

                        return `
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Section Title</label>
                                <input type="text" value="${escapeHtml(d.title)}" placeholder="Frequently Asked Questions" data-bidx="${bIdx}" data-field="title" class="w-full px-3 py-1.5 text-xs font-bold bg-slate-50 border border-slate-300 rounded-lg">
                            </div>
                            <div class="flex items-center justify-between pt-2">
                                <span class="text-xs font-bold text-slate-800">Q&amp;A Pairs (${(d.items || []).length})</span>
                                <button type="button" onclick="addFaqItem(${bIdx})" class="h-6 px-2 text-[10px] font-bold uppercase bg-neutral-900 text-white rounded cursor-pointer">+ Add Question</button>
                            </div>
                            <div class="space-y-2.5">${faqHtml}</div>
                        `;

                    case 'cta_banner':
                        return `
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">CTA Headline</label>
                                <input type="text" value="${escapeHtml(d.title)}" placeholder="Ready to Get Started?" data-bidx="${bIdx}" data-field="title" class="w-full px-3 py-1.5 text-xs font-bold bg-slate-50 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Subtitle Message</label>
                                <textarea rows="2" data-bidx="${bIdx}" data-field="subtitle" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg">${escapeHtml(d.subtitle)}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Button Text</label>
                                    <input type="text" value="${escapeHtml(d.btn_label)}" placeholder="e.g. Join Now" data-bidx="${bIdx}" data-field="btn_label" class="w-full px-2.5 py-1 text-xs bg-slate-50 border border-slate-300 rounded">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Button Target Link</label>
                                    <input type="text" value="${escapeHtml(d.btn_url)}" placeholder="/register" data-bidx="${bIdx}" data-field="btn_url" class="w-full px-2.5 py-1 text-xs font-mono bg-slate-50 border border-slate-300 rounded">
                                </div>
                            </div>
                        `;

                    case 'stats':
                        var sHtml = (d.items || []).map(function(item, itIdx) {
                            return `
                                <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-200 space-y-1">
                                    <div class="flex justify-between text-[10px] text-slate-400 font-bold uppercase">
                                        <span>Stat #${itIdx + 1}</span>
                                        <button type="button" onclick="deleteSubItem(${bIdx}, 'items', ${itIdx})" class="text-red-500">&times;</button>
                                    </div>
                                    <input type="text" value="${escapeHtml(item.value)}" placeholder="e.g. 25K+" data-bidx="${bIdx}" data-itemidx="${itIdx}" data-field="value" class="w-full px-2 py-0.5 text-xs font-extrabold bg-white border border-slate-300 rounded">
                                    <input type="text" value="${escapeHtml(item.label)}" placeholder="Label" data-bidx="${bIdx}" data-itemidx="${itIdx}" data-field="label" class="w-full px-2 py-0.5 text-xs bg-white border border-slate-300 rounded">
                                </div>
                            `;
                        }).join('');

                        return `
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Heading Title</label>
                                <input type="text" value="${escapeHtml(d.title)}" placeholder="Metrics" data-bidx="${bIdx}" data-field="title" class="w-full px-3 py-1.5 text-xs font-bold bg-slate-50 border border-slate-300 rounded-lg">
                            </div>
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-xs font-bold text-slate-800">Stats Cards</span>
                                <button type="button" onclick="addStatItem(${bIdx})" class="h-6 px-2 text-[10px] font-bold uppercase bg-neutral-900 text-white rounded cursor-pointer">+ Add Stat</button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">${sHtml}</div>
                        `;

                    case 'testimonial':
                        return `
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Quote Statement</label>
                                <textarea rows="3" data-bidx="${bIdx}" data-field="quote" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg">${escapeHtml(d.quote)}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Student / Author Name</label>
                                    <input type="text" value="${escapeHtml(d.author_name)}" placeholder="Jane Doe" data-bidx="${bIdx}" data-field="author_name" class="w-full px-2.5 py-1 text-xs bg-slate-50 border border-slate-300 rounded">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Author Role / Credentials</label>
                                    <input type="text" value="${escapeHtml(d.author_role)}" placeholder="Full-Stack Graduate" data-bidx="${bIdx}" data-field="author_role" class="w-full px-2.5 py-1 text-xs bg-slate-50 border border-slate-300 rounded">
                                </div>
                            </div>
                        `;

                    case 'rich_text':
                        return `
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Section Heading</label>
                                <input type="text" value="${escapeHtml(d.title)}" placeholder="Article Heading" data-bidx="${bIdx}" data-field="title" class="w-full px-3 py-1.5 text-xs font-bold bg-slate-50 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Body Text Content</label>
                                <textarea rows="6" data-bidx="${bIdx}" data-field="body" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg font-mono">${escapeHtml(d.body)}</textarea>
                            </div>
                        `;

                    case 'video_embed':
                        return `
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Heading Title</label>
                                <input type="text" value="${escapeHtml(d.title)}" placeholder="Watch Tutorial" data-bidx="${bIdx}" data-field="title" class="w-full px-3 py-1.5 text-xs font-bold bg-slate-50 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Video Embed URL (YouTube/Vimeo embed)</label>
                                <input type="text" value="${escapeHtml(d.video_url)}" placeholder="https://www.youtube.com/embed/..." data-bidx="${bIdx}" data-field="video_url" class="w-full px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-300 rounded-lg">
                            </div>
                        `;

                    default:
                        return `<p class="text-xs text-slate-400">Configure block data.</p>`;
                }
            }

            // Global Actions on Window
            window.openBlockPicker = function() {
                if (pickerModal) pickerModal.classList.remove('hidden');
            };

            window.closeBlockPicker = function() {
                if (pickerModal) pickerModal.classList.add('hidden');
            };

            window.addBlock = function(type) {
                closeBlockPicker();
                var newBlk = { id: uid(), type: type, collapsed: false, data: {} };

                if (type === 'hero') {
                    newBlk.data = { eyebrow: 'Special Announcement', title: 'New Program Launched', subtitle: 'Explore high-demand certified courses and transform your career.', btn_primary_label: 'Get Started', btn_primary_url: '/courses', bg_theme: 'dark' };
                } else if (type === 'features_grid') {
                    newBlk.data = { title: 'Program Highlights', columns: 3, items: [
                        { icon: 'trophy', title: 'Accredited Curriculum', description: 'Industry-standard hands-on training with career certificates.' },
                        { icon: 'book', title: 'Comprehensive Lessons', description: 'Video modules with interactive quizzes and live coding tasks.' },
                        { icon: 'shield', title: 'Lifetime Support', description: '24/7 student mentor access and peer learning circles.' }
                    ]};
                } else if (type === 'split_content') {
                    newBlk.data = { title: 'Empowering Learners Worldwide', description: 'Build portfolio-ready projects and master high-demand modern skills.', image_position: 'right', points: ['Verified digital credential upon graduation', 'Self-paced flexible schedule'] };
                } else if (type === 'faq') {
                    newBlk.data = { title: 'Frequently Asked Questions', items: [
                        { question: 'What prerequisites are required?', answer: 'No prior coding experience is required. We start from foundational fundamentals.' },
                        { question: 'Will I receive a certificate?', answer: 'Yes, a verified certificate is automatically issued upon course completion.' }
                    ]};
                } else if (type === 'cta_banner') {
                    newBlk.data = { title: 'Take the Next Step in Your Education', subtitle: 'Join thousands of ambitious students worldwide today.', btn_label: 'Enroll Now', btn_url: '/register' };
                } else if (type === 'stats') {
                    newBlk.data = { title: 'Our Track Record', items: [
                        { value: '15,000+', label: 'Graduates' },
                        { value: '99%', label: 'Positive Reviews' }
                    ]};
                } else if (type === 'testimonial') {
                    newBlk.data = { quote: 'The interactive quizzes and mentor reviews helped me land my dream software developer role.', author_name: 'David Miller', author_role: 'Alumni Graduate' };
                } else if (type === 'rich_text') {
                    newBlk.data = { title: 'Detailed Overview', body: '<p>Write your detailed structured article paragraphs here.</p>' };
                } else if (type === 'video_embed') {
                    newBlk.data = { title: 'Introduction Video', video_url: 'https://www.youtube.com/embed/dQw4w9WgXcQ' };
                }

                blocks.push(newBlk);
                renderBlocks();
            };

            window.moveBlock = function(idx, dir) {
                var target = idx + dir;
                if (target < 0 || target >= blocks.length) return;
                var item = blocks.splice(idx, 1)[0];
                blocks.splice(target, 0, item);
                renderBlocks();
            };

            window.duplicateBlock = function(idx) {
                var copy = JSON.parse(JSON.stringify(blocks[idx]));
                copy.id = uid();
                blocks.splice(idx + 1, 0, copy);
                renderBlocks();
            };

            window.deleteBlock = function(idx) {
                if (!confirm('Are you sure you want to delete this section block?')) return;
                blocks.splice(idx, 1);
                renderBlocks();
            };

            window.toggleCollapse = function(idx) {
                if (blocks[idx]) {
                    blocks[idx].collapsed = !blocks[idx].collapsed;
                    renderBlocks();
                }
            };

            window.addFeatureItem = function(bIdx) {
                if (!blocks[bIdx].data.items) blocks[bIdx].data.items = [];
                blocks[bIdx].data.items.push({ icon: 'trophy', title: 'New Feature', description: 'Feature description' });
                renderBlocks();
            };

            window.addPoint = function(bIdx) {
                if (!blocks[bIdx].data.points) blocks[bIdx].data.points = [];
                blocks[bIdx].data.points.push('New key highlight point');
                renderBlocks();
            };

            window.deletePoint = function(bIdx, ptIdx) {
                blocks[bIdx].data.points.splice(ptIdx, 1);
                renderBlocks();
            };

            window.addFaqItem = function(bIdx) {
                if (!blocks[bIdx].data.items) blocks[bIdx].data.items = [];
                blocks[bIdx].data.items.push({ question: 'New Question?', answer: 'Answer details here.' });
                renderBlocks();
            };

            window.addStatItem = function(bIdx) {
                if (!blocks[bIdx].data.items) blocks[bIdx].data.items = [];
                blocks[bIdx].data.items.push({ value: '100+', label: 'New Metric' });
                renderBlocks();
            };

            window.deleteSubItem = function(bIdx, arrayKey, itemIdx) {
                if (blocks[bIdx].data[arrayKey]) {
                    blocks[bIdx].data[arrayKey].splice(itemIdx, 1);
                    renderBlocks();
                }
            };

            window.loadStarterTemplate = function() {
                if (blocks.length > 0 && !confirm('Replace current sections with the complete Starter Landing Page Template?')) return;
                blocks = defaultStarterBlocks();
                renderBlocks();
            };

            window.clearAllBlocks = function() {
                if (!confirm('Remove all section blocks from this page?')) return;
                blocks = [];
                renderBlocks();
            };

            window.setBuilderTab = function(tab) {
                var visPanel = document.getElementById('builder-panel-visual');
                var codePanel = document.getElementById('builder-panel-code');
                var btnVis = document.getElementById('tab-btn-visual');
                var btnCode = document.getElementById('tab-btn-code');

                if (tab === 'visual') {
                    visPanel.classList.remove('hidden');
                    codePanel.classList.add('hidden');
                    btnVis.classList.add('bg-white', 'text-slate-900', 'shadow-xs');
                    btnVis.classList.remove('text-slate-600');
                    btnCode.classList.remove('bg-white', 'text-slate-900', 'shadow-xs');
                    btnCode.classList.add('text-slate-600');
                } else {
                    syncPageState();
                    visPanel.classList.add('hidden');
                    codePanel.classList.remove('hidden');
                    btnCode.classList.add('bg-white', 'text-slate-900', 'shadow-xs');
                    btnCode.classList.remove('text-slate-600');
                    btnVis.classList.remove('bg-white', 'text-slate-900', 'shadow-xs');
                    btnVis.classList.add('text-slate-600');
                }
            };

            // Modal overlay click to close
            if (pickerModal) {
                pickerModal.addEventListener('click', function(e) {
                    if (e.target === pickerModal) closeBlockPicker();
                });
            }

            // Initial Render
            renderBlocks();
        })();
    </script>

</x-layouts.dashboard>
