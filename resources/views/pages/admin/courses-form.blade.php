<x-layouts.dashboard :title="($course->exists ? __('admin.courses.edit') : __('admin.courses.create')).' - Mono LMS'" active="courses" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] mb-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back to All Courses</span>
                </a>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">
                    {{ $course->exists ? __('admin.courses.edit') : __('admin.courses.create') }}
                </h1>
            </div>
        </div>

        <!-- Course Form -->
        <div class="bg-white border border-[#e7e7e7] p-6 sm:p-8">
            <form id="course-form" action="{{ $course->exists ? route('admin.courses.update', $course) : route('admin.courses.store') }}" method="POST" class="space-y-6">
                @csrf
                @if ($course->exists) @method('PUT') @endif

                @php
                    $languages = $languages ?? \App\Models\Language::active();
                    $defaultCode = $defaultCode ?? \App\Models\Language::defaultCode();
                    $transLangs = $languages->where('code', '!=', $defaultCode)->values();
                    $translatorSet = $translatorSet ?? (bool) app(\App\Services\AiTranslationService::class)->provider();
                    $generatorSet = $generatorSet ?? (bool) \App\Models\AiProvider::resolveConfigured('ai_generator_provider');
                    $imageSet = $imageSet ?? (bool) \App\Models\AiProvider::resolveConfigured('ai_image_provider');
                @endphp

                <!-- Language Tabs & AI Toolbar (Mono Market System) -->
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4 mb-6 border-b border-neutral-200 dark:border-neutral-800">
                    <!-- Language Tabs -->
                    <div class="flex flex-wrap items-center gap-1.5" role="tablist">
                        <button type="button"
                                data-course-lang-btn="default"
                                data-lang-panel="lang-panel-default"
                                role="tab"
                                aria-selected="true"
                                onclick="switchCourseLangTab('default')"
                                class="course-lang-btn is-active inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg border border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900 shadow-xs cursor-pointer transition-all">
                            <span>{{ $languages->firstWhere('code', $defaultCode)?->native_name ?? strtoupper($defaultCode) }}</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded bg-white/20 text-white dark:bg-neutral-900/10 dark:text-neutral-900">
                                {{ __('admin.courses.lang_default_badge') ?? 'Default' }}
                            </span>
                        </button>
                        @foreach ($transLangs as $lang)
                            <button type="button"
                                    data-course-lang-btn="{{ $lang->code }}"
                                    data-lang-panel="lang-panel-{{ $lang->code }}"
                                    role="tab"
                                    aria-selected="false"
                                    onclick="switchCourseLangTab('{{ $lang->code }}')"
                                    class="course-lang-btn inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-700 cursor-pointer transition-all">
                                <span>{{ $lang->native_name }}</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Right Toolbar: Translate + AI Write Buttons (h-9 consistent height) -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <x-admin.translation-toolbar :enabled="true" context="course" container="#course-form" :source="$defaultCode" />

                        <button type="button"
                                id="course-generate-ai"
                                onclick="openAIWriteModal()"
                                class="h-9 px-3.5 inline-flex items-center gap-2 text-xs font-bold rounded-lg border border-teal-600/30 bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 hover:bg-teal-600 hover:text-white transition-all cursor-pointer shadow-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L14.4 9.6L22 12L14.4 14.4L12 22L9.6 14.4L2 12L9.6 9.6L12 2Z"/>
                            </svg>
                            <span>+ Generate with AI</span>
                        </button>
                    </div>
                </div>

                <!-- Default Language Fields -->
                <div id="lang-panel-default" class="lang-panel space-y-5" role="tabpanel">
                    <!-- Course Title -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.title') }} *</label>
                        <input type="text" name="title" id="course-title-default" value="{{ old('title', $course->title) }}" required
                               data-translate-key="title" data-translate-locale="{{ $defaultCode }}"
                               class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                        @error('title')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- Subtitle / Short Description -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.subtitle') }}</label>
                        <input type="text" name="subtitle" id="course-subtitle-default" value="{{ old('subtitle', $course->subtitle) }}" placeholder="Brief tagline or hook for the course card"
                               data-translate-key="subtitle" data-translate-locale="{{ $defaultCode }}"
                               class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                    </div>

                    <!-- Full Description -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.description') }}</label>
                        <textarea name="description" id="course-desc-default" rows="6"
                                  data-translate-key="description" data-translate-locale="{{ $defaultCode }}"
                                  class="w-full border border-neutral-300 p-4 text-sm focus:outline-none focus:border-neutral-900">{{ old('description', $course->description) }}</textarea>
                    </div>
                </div>

                <!-- Other Language Panels -->
                @foreach ($transLangs as $lang)
                    <div id="lang-panel-{{ $lang->code }}" class="lang-panel hidden space-y-5" role="tabpanel" dir="{{ $lang->direction }}">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.title') }} · {{ $lang->native_name }}</label>
                            <input type="text" name="translations[{{ $lang->code }}][title]"
                                   value="{{ old("translations.{$lang->code}.title", $course->translationMap('title')[$lang->code] ?? '') }}"
                                   data-translate-key="title" data-translate-locale="{{ $lang->code }}"
                                   class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                            <p class="text-[11px] text-neutral-400">Blank falls back to default course title.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.subtitle') }} · {{ $lang->native_name }}</label>
                            <input type="text" name="translations[{{ $lang->code }}][subtitle]"
                                   value="{{ old("translations.{$lang->code}.subtitle", $course->translationMap('subtitle')[$lang->code] ?? '') }}"
                                   data-translate-key="subtitle" data-translate-locale="{{ $lang->code }}"
                                   class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                            <p class="text-[11px] text-neutral-400">Blank falls back to default course subtitle.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.description') }} · {{ $lang->native_name }}</label>
                            <textarea name="translations[{{ $lang->code }}][description]" rows="6"
                                      data-translate-key="description" data-translate-locale="{{ $lang->code }}"
                                      class="w-full border border-neutral-300 p-4 text-sm focus:outline-none focus:border-neutral-900">{{ old("translations.{$lang->code}.description", $course->translationMap('description')[$lang->code] ?? '') }}</textarea>
                            <p class="text-[11px] text-neutral-400">Blank falls back to default course description.</p>
                        </div>
                    </div>
                @endforeach

                <!-- Instructor Assignment, Category, Level & Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                    <!-- Instructor Assignment -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">Course Instructor *</label>
                        @php
                            $isAssignedToAdmin = $course->exists && $course->instructor && $course->instructor->email === ($currentAdmin->email ?? '');
                            $selectedInstructor = old('instructor_id', $isAssignedToAdmin ? 'self' : ($course->instructor_id ?? 'self'));
                        @endphp
                        <select name="instructor_id" required data-mselect class="h-11 w-full border border-neutral-300 px-3.5 text-sm focus:outline-none focus:border-neutral-900 bg-white font-medium">
                            <option value="self" @selected($selectedInstructor === 'self')>
                                ⭐ Myself ({{ $currentAdmin->name ?? 'Administrator' }})
                            </option>
                            <optgroup label="Registered Instructors">
                                @foreach ($instructors as $inst)
                                    <option value="{{ $inst->id }}" @selected($selectedInstructor == $inst->id)>
                                        {{ $inst->name }} ({{ $inst->email }})
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        <p class="text-[11px] text-neutral-400">Assign to yourself or any active instructor.</p>
                    </div>

                    <!-- Category -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.category') }}</label>
                        <x-select name="category_id"
                            :options="$categories->pluck('name', 'id')->prepend('— None —', '')"
                            :selected="old('category_id', $course->category_id)"
                            placeholder="— None —" />
                    </div>

                    <!-- Level -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.level') }}</label>
                        <x-select name="level"
                            :options="['beginner' => __('app.levels.beginner'), 'intermediate' => __('app.levels.intermediate'), 'advanced' => __('app.levels.advanced')]"
                            :selected="old('level', $course->level ?? 'beginner')"
                            :searchable="false" />
                    </div>

                    <!-- Course Type -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">Course Type *</label>
                        <x-select name="type"
                            :options="['recorded' => 'Pre-recorded', 'live' => 'Live Classes', 'combined' => 'Recorded + Live (Combined)']"
                            :selected="old('type', $course->type ?? 'recorded')"
                            :searchable="false" />
                        @error('type')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                        <p class="text-[11px] text-neutral-400">Live/Combined courses get a live-classes manager after saving.</p>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.price') }} (USD) *</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $course->price ?? 0) }}" required class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">💡 Set to 0 to make this course 100% free with all lessons automatically unlocked.</p>
                        @error('price')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.old_price') }} (USD / Strike-through)</label>
                        <input type="number" step="0.01" min="0" name="old_price" value="{{ old('old_price', $course->old_price) }}" placeholder="Leave blank if no sale discount" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                        @error('old_price')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Course Thumbnail Media Selector + AI Button -->
                <div id="course-media-wrap">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 block mb-1.5">Course Thumbnail / Hero Banner</label>
                    <x-media-selector
                        name="image"
                        :value="old('image', $course->image)"
                        shape="banner"
                        hint="Picked from media library or generated with AI. Recommended size: 1280×720 (16:9 ratio)."
                    >
                        @if ($imageSet)
                            <button type="button" id="course-ai-img-btn" class="action-btn h-9 px-4 inline-flex items-center gap-2 text-[11px] font-medium uppercase tracking-[0.14em] cursor-pointer">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.2 6.9L21 11.1l-6.8 2.2L12 20.2l-2.2-6.9L3 11.1l6.8-2.2L12 2z"/></svg>
                                <span>{{ __('admin.imagegen.button') ?? 'Generate with AI' }}</span>
                            </button>
                        @endif
                    </x-media-selector>
                </div>

                <!-- Visibility & Storefront Placement Flags -->
                <div class="p-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">Storefront Visibility &amp; Homepage Placement</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $course->is_published)) class="control mt-0.5">
                            <div>
                                <span class="text-xs font-semibold text-neutral-900 dark:text-neutral-100 block">{{ __('instructor.courses.fields.is_published') }}</span>
                                <span class="text-[11px] text-neutral-500 dark:text-neutral-400">Visible in public catalog</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $course->is_featured)) class="control mt-0.5">
                            <div>
                                <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 block">★ Featured Course</span>
                                <span class="text-[11px] text-neutral-500 dark:text-neutral-400">Featured Courses Grid on Homepage</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="is_trending" value="1" @checked(old('is_trending', $course->is_trending)) class="control mt-0.5">
                            <div>
                                <span class="text-xs font-semibold text-rose-700 dark:text-rose-400 block">🔥 Most Trending Course</span>
                                <span class="text-[11px] text-neutral-500 dark:text-neutral-400">Most Trending Section on Homepage</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-3 border-t border-neutral-200">
                    <button type="submit" class="action-btn action-btn-primary h-11 px-7 text-[11px] font-medium uppercase tracking-[0.14em]">
                        {{ __('app.common.save') }}
                    </button>
                    <a href="{{ route('admin.courses.index') }}" class="action-btn action-btn-ghost h-11 px-6 text-[11px] font-medium uppercase tracking-[0.14em]">
                        {{ __('app.common.cancel') }}
                    </a>
                </div>
            </form>
        </div>

        @if ($course->exists)
            @if ($course->hasRecordedContent())
                <!-- Lessons Manager -->
                <div class="bg-white border border-[#e7e7e7] p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
                        <div>
                            <h3 class="font-bold text-[#191919] text-base">{{ __('instructor.lessons.title') }}</h3>
                            <p class="text-xs text-neutral-400 mt-0.5">{{ $course->lessons->count() }} lessons · each plays a YouTube link or an uploaded video file</p>
                        </div>
                    </div>

                    <!-- Add Lesson Form -->
                    <form action="{{ route('admin.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-5 bg-neutral-50 dark:bg-neutral-900/50 p-6 sm:p-7 border border-neutral-200 dark:border-neutral-800 rounded-2xl" data-video-source-form>
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-[1fr_200px] gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">{{ __('instructor.lessons.fields.title') }} *</label>
                                <input type="text" name="title" required placeholder="e.g. 01. Course Overview & Introduction" class="h-11 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400 whitespace-nowrap">{{ __('instructor.lessons.fields.duration') }}</label>
                                <input type="number" name="duration_minutes" min="0" placeholder="15" class="h-11 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
                            </div>
                        </div>

                        @include('pages.admin.partials.lesson-video-fields', ['prefix' => 'add'])

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="action-btn action-btn-primary h-10 px-6 text-[11px] font-semibold uppercase tracking-[0.12em] whitespace-nowrap">
                                + {{ __('instructor.lessons.add') }}
                            </button>
                        </div>
                    </form>

                    <!-- Lessons List -->
                    <div class="divide-y divide-neutral-200 dark:divide-neutral-800 border border-neutral-200 dark:border-neutral-800 rounded-2xl overflow-hidden">
                        @forelse ($course->lessons as $lesson)
                            <div class="p-4 sm:p-5 bg-white dark:bg-neutral-900">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-8 h-8 rounded-lg bg-emerald-50 text-[#344c3d] dark:bg-emerald-950/60 dark:text-emerald-300 text-xs font-bold flex items-center justify-center shrink-0 border border-emerald-200/50 dark:border-emerald-800/50">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div class="min-w-0 space-y-0.5">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="font-semibold text-xs text-[#191919] dark:text-neutral-100 truncate">{{ $lesson->title }}</p>
                                                @if ($course->isFree())
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 12.75v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                        Free (Course is Free)
                                                    </span>
                                                @elseif ($lesson->isFree())
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 12.75v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                        Free Preview
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider rounded bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                        Locked
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-neutral-400 font-mono truncate max-w-sm">
                                                @if ($lesson->isUploadedVideo())
                                                    📹 Uploaded video file
                                                @elseif ($lesson->video_url)
                                                    ▶ {{ $lesson->video_url }}
                                                @else
                                                    No video yet
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2.5 shrink-0">
                                        @if ($lesson->duration_minutes)
                                            <span class="text-xs text-neutral-400 font-medium">{{ $lesson->duration_minutes }}m</span>
                                        @endif
                                        <button type="button" data-edit-toggle="lesson-edit-{{ $lesson->id }}" class="action-btn action-btn-ghost h-8 px-3 text-[10px] font-medium uppercase tracking-wider">Edit</button>
                                        <form action="{{ route('admin.courses.lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Remove this lesson?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-danger h-8 px-3 text-[10px] font-medium uppercase tracking-wider">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <form id="lesson-edit-{{ $lesson->id }}" hidden class="mt-4 border-t border-neutral-200 dark:border-neutral-800 pt-5 space-y-5 bg-neutral-50/80 dark:bg-neutral-900/60 p-5 sm:p-6 rounded-2xl border border-neutral-200 dark:border-neutral-800" action="{{ route('admin.courses.lessons.update', $lesson) }}" method="POST" enctype="multipart/form-data" data-video-source-form>
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 sm:grid-cols-[1fr_200px] gap-4">
                                        <div class="space-y-1.5">
                                            <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Title *</label>
                                            <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="h-11 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400 whitespace-nowrap">{{ __('instructor.lessons.fields.duration') }}</label>
                                            <input type="number" name="duration_minutes" min="0" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" class="h-11 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
                                        </div>
                                    </div>

                                    @include('pages.admin.partials.lesson-video-fields', ['prefix' => 'edit-'.$lesson->id, 'lesson' => $lesson])

                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="action-btn action-btn-primary h-9 px-5 text-[10px] font-semibold uppercase tracking-[0.12em]">Save Lesson</button>
                                    </div>
                                </form>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center text-xs text-neutral-400">
                                No lessons added to this course yet. Use the form above to add your first lecture.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if ($course->hasLiveContent())
                <!-- Live Classes Manager -->
                <div class="bg-white border border-[#e7e7e7] p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
                        <div>
                            <h3 class="font-bold text-[#191919] text-base">Live Classes</h3>
                            <p class="text-xs text-neutral-400 mt-0.5">{{ $course->liveClasses->count() }} scheduled sessions · students see a Join button once enrolled</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.live-classes.store', $course) }}" method="POST" class="space-y-3 bg-neutral-50 p-4 border border-neutral-200">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">Session Title *</label>
                                <input type="text" name="title" required class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-sm focus:outline-none focus:border-neutral-900">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">Starts At *</label>
                                <input type="datetime-local" name="starts_at" required class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-sm focus:outline-none focus:border-neutral-900">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">Platform</label>
                                <select name="meeting_platform" class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-sm focus:outline-none focus:border-neutral-900">
                                    <option value="zoom">Zoom</option>
                                    <option value="google_meet">Google Meet</option>
                                    <option value="jitsi">Jitsi Meet</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">Duration (mins)</label>
                                <input type="number" name="duration_minutes" min="5" value="60" class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-sm focus:outline-none focus:border-neutral-900">
                            </div>
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">Meeting URL *</label>
                                <input type="url" name="meeting_url" required placeholder="https://zoom.us/j/..." class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-sm focus:outline-none focus:border-neutral-900">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="action-btn action-btn-primary h-10 px-5 text-[10px] font-semibold uppercase tracking-[0.12em] whitespace-nowrap">+ Schedule Live Class</button>
                        </div>
                    </form>

                    <div class="divide-y divide-neutral-200 border border-neutral-200 overflow-hidden">
                        @forelse ($course->liveClasses as $live)
                            <div class="px-4 py-3.5 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-7 h-7 bg-emerald-50 text-emerald-700 text-xs font-bold flex items-center justify-center shrink-0 border border-emerald-200/50">▶</span>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-xs text-[#191919] truncate">{{ $live->title }}</p>
                                        <p class="text-[11px] text-neutral-400 truncate">{{ $live->starts_at->format('d M Y, h:i A') }} · {{ $live->platformLabel() }} · {{ $live->status }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('admin.live-classes.destroy', $live) }}" method="POST" onsubmit="return confirm('Delete this live class?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px]">Delete</button>
                                </form>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center text-xs text-neutral-400">No live classes scheduled yet.</div>
                        @endforelse
                    </div>
                </div>
            @endif
        @endif
    </div>

    <script>
        // Video source toggle: show YouTube URL input or the file picker.
        document.addEventListener('change', function (e) {
            if (! e.target.matches('input[name="video_source"]')) return;
            var form = e.target.closest('[data-video-source-form]');
            if (! form) return;

            var yt = form.querySelector('[data-video-youtube]');
            var up = form.querySelector('[data-video-upload]');
            if (! yt || ! up) return;

            var isUpload = e.target.value === 'upload';
            yt.classList.toggle('hidden', isUpload);
            up.classList.toggle('hidden', ! isUpload);
        });

        // Inline lesson edit forms
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-edit-toggle]');
            if (! btn) return;
            var form = document.getElementById(btn.getAttribute('data-edit-toggle'));
            if (form) {
                var isHidden = form.hasAttribute('hidden') || form.classList.contains('hidden') || form.style.display === 'none';
                if (isHidden) {
                    form.removeAttribute('hidden');
                    form.classList.remove('hidden');
                    form.style.display = 'block';
                } else {
                    form.setAttribute('hidden', '');
                    form.classList.add('hidden');
                    form.style.display = 'none';
                }
            }
        });

        // Course Language Tabs switching function
        window.switchCourseLangTab = function (code) {
            document.querySelectorAll('[data-course-lang-btn]').forEach(function (btn) {
                var isActive = (btn.dataset.courseLangBtn === code);
                btn.classList.toggle('is-active', isActive);
                btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                if (isActive) {
                    btn.className = 'course-lang-btn is-active inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg border border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900 shadow-xs cursor-pointer transition-all';
                } else {
                    btn.className = 'course-lang-btn inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-700 cursor-pointer transition-all';
                }
            });

            document.querySelectorAll('#course-form .lang-panel').forEach(function (p) {
                p.classList.add('hidden');
            });
            var target = document.getElementById(code === 'default' ? 'lang-panel-default' : 'lang-panel-' + code);
            if (target) target.classList.remove('hidden');

            var aiLangSel = document.getElementById('aiWriteLang');
            if (aiLangSel && code !== 'default') aiLangSel.value = code;
        };

        // Delegated AI Image Modal trigger for course thumbnail
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('#course-ai-img-btn');
            if (! btn) return;
            if (! window.aiImageModal) {
                alert('AI Image generation is not available. Please check AI settings.');
                return;
            }
            var title = document.getElementById('course-title-default') ? document.getElementById('course-title-default').value : '';
            var desc = document.getElementById('course-desc-default') ? document.getElementById('course-desc-default').value : '';
            window.aiImageModal.open({
                title: title,
                description: desc,
                style: {!! json_encode(__('admin.imagegen.style_course') ?: 'Professional modern online course thumbnail banner, educational, high detail, clean aesthetic, no text, no watermark') !!},
                size: '1792x1024',
                multiple: false,
                onApply: function (imgs) {
                    if (!imgs || !imgs.length) return;
                    var wrap = document.getElementById('course-media-wrap') || btn.closest('[data-media-selector]');
                    if (!wrap) return;
                    var input = wrap.querySelector('[data-media-input]');
                    var img = wrap.querySelector('[data-media-img]');
                    var ph = wrap.querySelector('[data-media-placeholder]');
                    var rm = wrap.querySelector('[data-media-remove]');
                    if (input) input.value = imgs[0].path;
                    if (img) { img.src = imgs[0].url; img.classList.remove('hidden'); }
                    if (ph) ph.classList.add('hidden');
                    if (rm) rm.classList.remove('hidden');
                }
            });
        });
    </script>

    @include('pages.admin.partials.ai-write-modal', ['languages' => $languages, 'defaultCode' => $defaultCode, 'type' => 'course'])
    </script>

</x-layouts.dashboard>
