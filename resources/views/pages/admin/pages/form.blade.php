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

                        <!-- 5. RICH HTML CONTENT BODY (Shown for Legal, Standard Custom, and About Story) -->
                        <div class="mt-6 pt-5 border-t border-slate-100 dark:border-neutral-800" id="content-body-container">
                            <label for="page_content" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-neutral-300 mb-1.5">
                                Main Content Body (HTML / Structured Text)
                            </label>
                            <p class="text-[11px] text-slate-500 dark:text-neutral-400 mb-2">
                                Use HTML tags (<code>&lt;h3&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a&gt;</code>) for formatted paragraphs and clauses.
                            </p>
                            <textarea id="page_content"
                                      name="content"
                                      rows="12"
                                      placeholder="Enter page body content or HTML..."
                                      class="w-full px-4 py-3 text-xs font-mono bg-slate-50 dark:bg-neutral-800/60 border border-slate-300 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white leading-relaxed focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">{{ old('content', $page->content) }}</textarea>
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
    </script>

</x-layouts.dashboard>
