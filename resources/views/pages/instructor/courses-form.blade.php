<x-layouts.dashboard :title="($course->exists ? __('instructor.courses.edit') : __('instructor.courses.create')).' - Mono LMS'" active="courses" userType="instructor">

    <div class="space-y-6">
        <!-- Course Form -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8">
            <h3 class="font-bold text-[#191919] mb-6">{{ $course->exists ? __('instructor.courses.edit') : __('instructor.courses.create') }}</h3>

            <form id="inst-course-form" action="{{ $course->exists ? route('instructor.courses.update', $course) : route('instructor.courses.store') }}" method="POST" class="space-y-5">
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
                                data-inst-course-lang-btn="default"
                                data-translate-locale-tab="{{ $defaultCode }}"
                                data-lang-panel="inst-lang-panel-default"
                                role="tab"
                                aria-selected="true"
                                onclick="switchInstCourseLangTab('default')"
                                class="inst-course-lang-btn is-active inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg border border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900 shadow-xs cursor-pointer transition-all">
                            <span>{{ $languages->firstWhere('code', $defaultCode)?->native_name ?? strtoupper($defaultCode) }}</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded bg-white/20 text-white dark:bg-neutral-900/10 dark:text-neutral-900">
                                {{ __('admin.courses.lang_default_badge') ?? 'Default' }}
                            </span>
                        </button>
                        @foreach ($transLangs as $lang)
                            <button type="button"
                                    data-inst-course-lang-btn="{{ $lang->code }}"
                                    data-translate-locale-tab="{{ $lang->code }}"
                                    data-lang-panel="inst-lang-panel-{{ $lang->code }}"
                                    role="tab"
                                    aria-selected="false"
                                    onclick="switchInstCourseLangTab('{{ $lang->code }}')"
                                    class="inst-course-lang-btn inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-700 cursor-pointer transition-all">
                                <span>{{ $lang->native_name }}</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Right Toolbar: Translate + AI Write Buttons (h-9 consistent height) -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <x-admin.translation-toolbar :enabled="true" context="course" container="#inst-course-form" :source="$defaultCode" />

                        <button type="button"
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
                <div id="inst-lang-panel-default" class="lang-panel space-y-4" role="tabpanel">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.title') }} *</label>
                        <input type="text" name="title" id="inst-course-title-default" value="{{ old('title', $course->title) }}" required
                               data-translate-key="title" data-translate-locale="{{ $defaultCode }}"
                               class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        @error('title')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.subtitle') }}</label>
                        <input type="text" name="subtitle" id="inst-course-subtitle-default" value="{{ old('subtitle', $course->subtitle) }}"
                               data-translate-key="subtitle" data-translate-locale="{{ $defaultCode }}"
                               class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.description') }}</label>
                        <textarea name="description" id="inst-course-desc-default" rows="5"
                                  data-translate-key="description" data-translate-locale="{{ $defaultCode }}"
                                  class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">{{ old('description', $course->description) }}</textarea>
                    </div>
                </div>

                <!-- Other Language Panels -->
                @foreach ($transLangs as $lang)
                    <div id="inst-lang-panel-{{ $lang->code }}" class="lang-panel hidden space-y-4" role="tabpanel" dir="{{ $lang->direction }}">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.title') }} · {{ $lang->native_name }}</label>
                            <input type="text" name="translations[{{ $lang->code }}][title]"
                                   value="{{ old("translations.{$lang->code}.title", $course->translationMap('title')[$lang->code] ?? '') }}"
                                   data-translate-key="title" data-translate-locale="{{ $lang->code }}"
                                   class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                            <p class="text-[11px] text-gray-400">Blank falls back to default course title.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.subtitle') }} · {{ $lang->native_name }}</label>
                            <input type="text" name="translations[{{ $lang->code }}][subtitle]"
                                   value="{{ old("translations.{$lang->code}.subtitle", $course->translationMap('subtitle')[$lang->code] ?? '') }}"
                                   data-translate-key="subtitle" data-translate-locale="{{ $lang->code }}"
                                   class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                            <p class="text-[11px] text-gray-400">Blank falls back to default course subtitle.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.description') }} · {{ $lang->native_name }}</label>
                            <textarea name="translations[{{ $lang->code }}][description]" rows="5"
                                      data-translate-key="description" data-translate-locale="{{ $lang->code }}"
                                      class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">{{ old("translations.{$lang->code}.description", $course->translationMap('description')[$lang->code] ?? '') }}</textarea>
                            <p class="text-[11px] text-gray-400">Blank falls back to default course description.</p>
                        </div>
                    </div>
                @endforeach

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.category') }}</label>
                        <x-select name="category_id"
                            :options="$categories->pluck('name', 'id')->prepend('—', '')"
                            :selected="old('category_id', $course->category_id)"
                            placeholder="—" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.level') }}</label>
                        <x-select name="level"
                            :options="['beginner' => __('app.levels.beginner'), 'intermediate' => __('app.levels.intermediate'), 'advanced' => __('app.levels.advanced')]"
                            :selected="old('level', $course->level ?? 'beginner')"
                            :searchable="false" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">Course Type *</label>
                        <x-select name="type"
                            :options="['recorded' => 'Pre-recorded', 'live' => 'Live Classes', 'combined' => 'Recorded + Live (Combined)']"
                            :selected="old('type', $course->type ?? 'recorded')"
                            :searchable="false" />
                        @error('type')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5" id="inst-course-media-wrap">
                        <label class="text-xs font-semibold text-[#191919] block">{{ __('instructor.courses.fields.image') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="image" id="inst-course-image-input" value="{{ old('image', $course->image) }}" placeholder="https://..." class="h-9 min-w-0 flex-1 border border-[#e7e7e7] rounded-lg px-3.5 text-sm focus:outline-none focus:border-[#738a6e]">
                            @if ($imageSet)
                                <button type="button" id="inst-course-ai-img-btn" class="action-btn h-9 px-4 inline-flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-[0.14em] cursor-pointer shrink-0">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.2 6.9L21 11.1l-6.8 2.2L12 20.2l-2.2-6.9L3 11.1l6.8-2.2L12 2z"/></svg>
                                    <span>{{ __('admin.imagegen.button') ?? 'Generate with AI' }}</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <p class="text-[11px] text-gray-400">
                    <span class="font-semibold text-[#5d5d5d]">Pre-recorded</span> = students watch uploaded/YouTube lessons at their own pace ·
                    <span class="font-semibold text-[#5d5d5d]">Live</span> = scheduled live classes with meeting links ·
                    <span class="font-semibold text-[#5d5d5d]">Combined</span> = both (Ostad style).
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.price') }} *</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $course->price ?? 0) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">💡 Set to 0 to make this course 100% free with all lessons automatically unlocked.</p>
                        @error('price')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.old_price') }}</label>
                        <input type="number" step="0.01" min="0" name="old_price" value="{{ old('old_price', $course->old_price) }}" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        @error('old_price')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-6">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none text-sm text-[#191919]">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $course->is_published)) class="control mt-0.5">
                        {{ __('instructor.courses.fields.is_published') }}
                    </label>
                </div>

                @if (! $course->exists)
                    @include('pages.admin.partials.course-quizzes-builder-create')
                @endif

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">{{ __('app.common.save') }}</button>
                    <a href="{{ route('instructor.courses.index') }}" class="text-sm font-semibold text-[#5d5d5d] hover:text-[#191919] px-4 py-2.5">{{ __('app.common.cancel') }}</a>
                </div>
            </form>
        </div>

        @if ($course->exists && $course->hasRecordedContent())
            <!-- Lessons Manager (recorded / combined) -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8">
                <h3 class="font-bold text-[#191919] mb-1">{{ __('instructor.lessons.title') }} ({{ $course->lessons->count() }})</h3>
                <p class="text-xs text-gray-400 mb-6">Each lesson plays either a YouTube link or an uploaded video file.</p>

                <form action="{{ route('instructor.lessons.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-4 border border-[#e7e7e7] dark:border-neutral-800 rounded-xl p-5 bg-neutral-50/70 dark:bg-neutral-900/50" data-video-source-form>
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-[1fr_200px] gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919] dark:text-neutral-300">{{ __('instructor.lessons.fields.title') }} *</label>
                            <input type="text" name="title" required class="w-full border border-[#e7e7e7] dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919] dark:text-neutral-300 whitespace-nowrap">{{ __('instructor.lessons.fields.duration') }}</label>
                            <input type="number" name="duration_minutes" min="0" class="w-full border border-[#e7e7e7] dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        </div>
                    </div>

                    @include('pages.instructor.partials.lesson-video-fields', ['prefix' => 'add'])

                    <div class="flex justify-end pt-1">
                        <button type="submit" class="bg-[#08131e] hover:bg-[#1b2737] text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition-colors">+ {{ __('instructor.lessons.add') }}</button>
                    </div>
                </form>

                <div class="mt-6 divide-y divide-gray-100 dark:divide-neutral-800 border border-gray-100 dark:border-neutral-800 rounded-xl overflow-hidden">
                    @forelse ($course->lessons as $lesson)
                        <div class="p-4 bg-white dark:bg-neutral-900">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-8 h-8 rounded-full bg-[#392c7d]/10 text-[#392c7d] text-xs font-bold flex items-center justify-center shrink-0">{{ $lesson->sort_order }}</span>
                                    <div class="min-w-0 space-y-0.5">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-semibold text-[#191919] dark:text-neutral-100 truncate">{{ $lesson->title }}</p>
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
                                        <p class="text-[11px] text-gray-400 truncate">
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
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-xs text-gray-400">{{ $lesson->duration_minutes }} {{ __('app.common.minutes') }}</span>
                                    <button type="button" data-edit-toggle="lesson-edit-{{ $lesson->id }}" class="text-xs font-semibold text-[#344c3d] hover:underline">Edit</button>
                                    <form method="POST" action="{{ route('instructor.lessons.destroy', $lesson) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">{{ __('app.common.delete') }}</button>
                                    </form>
                                </div>
                            </div>

                            <form id="lesson-edit-{{ $lesson->id }}" hidden class="mt-4 border-t border-gray-100 dark:border-neutral-800 pt-5 space-y-4 bg-neutral-50/70 dark:bg-neutral-900/40 p-4 rounded-xl border border-neutral-200 dark:border-neutral-800" action="{{ route('instructor.lessons.update', $lesson) }}" method="POST" enctype="multipart/form-data" data-video-source-form>
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 sm:grid-cols-[1fr_200px] gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-semibold text-[#191919] dark:text-neutral-300">Title *</label>
                                        <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="w-full border border-[#e7e7e7] dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-semibold text-[#191919] dark:text-neutral-300 whitespace-nowrap">{{ __('instructor.lessons.fields.duration') }}</label>
                                        <input type="number" name="duration_minutes" min="0" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" class="w-full border border-[#e7e7e7] dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                                    </div>
                                </div>

                                @include('pages.instructor.partials.lesson-video-fields', [
                                    'prefix' => 'edit-'.$lesson->id,
                                    'lesson' => $lesson,
                                ])

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-semibold px-5 py-2 rounded-xl transition-colors">Save Lesson</button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <p class="px-4 py-8 text-center text-sm text-gray-400">{{ __('instructor.lessons.no_lessons') }}</p>
                    @endforelse
                </div>
            </div>
        @endif

        @if ($course->exists && $course->hasLiveContent())
            <!-- Live Classes Manager (live / combined) -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8">
                <h3 class="font-bold text-[#191919] mb-1">Live Classes ({{ $course->liveClasses->count() }})</h3>
                <p class="text-xs text-gray-400 mb-6">Schedule sessions with a Zoom / Google Meet / Jitsi link. Enrolled students get a Join button; add a recording link after the session.</p>

                <form action="{{ route('instructor.live-classes.store', $course) }}" method="POST" class="space-y-3 border border-[#e7e7e7] rounded-xl p-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919]">Session Title *</label>
                            <input type="text" name="title" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919]">Starts At *</label>
                            <input type="datetime-local" name="starts_at" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919]">Platform</label>
                            <x-select name="meeting_platform"
                                :options="['zoom' => 'Zoom', 'google_meet' => 'Google Meet', 'jitsi' => 'Jitsi Meet', 'other' => 'Other']"
                                selected="zoom" :searchable="false" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919]">Session Type</label>
                            <select name="class_type" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e] bg-white">
                                <option value="main">Live Main Class</option>
                                <option value="conceptual">Conceptual / Doubt Clearing</option>
                                <option value="scrum">Scrum & Progress Review</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#191919]">Duration (minutes)</label>
                            <input type="number" name="duration_minutes" min="5" value="60" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        </div>
                        <div class="space-y-1.5 sm:col-span-2">
                            <label class="text-xs font-semibold text-[#191919]">Meeting URL *</label>
                            <input type="url" name="meeting_url" required placeholder="https://zoom.us/j/..." class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                            @error('meeting_url')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#08131e] hover:bg-[#1b2737] text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition-colors">+ Schedule Live Class</button>
                    </div>
                </form>

                <div class="mt-6 divide-y divide-gray-100 border border-gray-100 rounded-xl overflow-hidden">
                    @forelse ($course->liveClasses as $live)
                        <div class="px-4 py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-7 h-7 rounded-full bg-emerald-500/10 text-emerald-700 text-xs font-bold flex items-center justify-center shrink-0">▶</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-[#191919] truncate">{{ $live->title }}</p>
                                    <p class="text-[11px] text-gray-400 truncate">{{ $live->starts_at->format('d M Y, h:i A') }} · {{ $live->platformLabel() }} · {{ $live->status }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs text-gray-400">{{ $live->duration_minutes }} min</span>
                                <form method="POST" action="{{ route('instructor.live-classes.destroy', $live) }}" onsubmit="return confirm('Delete this live class?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="px-4 py-8 text-center text-sm text-gray-400">No live classes scheduled yet.</p>
                    @endforelse
                </div>
            </div>
        @endif

        @if ($course->exists)
            <!-- Quizzes & Assessments Manager -->
            @include('pages.admin.partials.course-quizzes-manager', ['course' => $course, 'userType' => 'instructor'])

            <!-- Assignments & Cohort Engine (Ostad-style hands-on learning) -->
            @include('pages.admin.partials.course-assignments-manager', ['course' => $course, 'userType' => 'instructor'])
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
        window.switchInstCourseLangTab = function (code) {
            document.querySelectorAll('[data-inst-course-lang-btn]').forEach(function (btn) {
                var isActive = (btn.dataset.instCourseLangBtn === code);
                btn.classList.toggle('is-active', isActive);
                btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                if (isActive) {
                    btn.className = 'inst-course-lang-btn is-active inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold rounded-lg border border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900 shadow-xs cursor-pointer transition-all';
                } else {
                    btn.className = 'inst-course-lang-btn inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-700 cursor-pointer transition-all';
                }
            });

            document.querySelectorAll('#inst-course-form .lang-panel').forEach(function (p) {
                p.classList.add('hidden');
            });
            var target = document.getElementById(code === 'default' ? 'inst-lang-panel-default' : 'inst-lang-panel-' + code);
            if (target) target.classList.remove('hidden');

            var aiLangSel = document.getElementById('aiWriteLang');
            if (aiLangSel && code !== 'default') aiLangSel.value = code;

            var locale = (code === 'default') ? @json($defaultCode) : code;
            document.dispatchEvent(new CustomEvent('translate:locale-change', { detail: { locale: locale } }));
        };

        // Delegated AI Image Modal trigger for instructor course thumbnail
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('#inst-course-ai-img-btn');
            if (! btn) return;
            if (! window.aiImageModal) {
                alert('AI Image generation is not available. Please check AI settings.');
                return;
            }
            var title = document.getElementById('inst-course-title-default') ? document.getElementById('inst-course-title-default').value : '';
            var desc = document.getElementById('inst-course-desc-default') ? document.getElementById('inst-course-desc-default').value : '';
            window.aiImageModal.open({
                title: title,
                description: desc,
                style: {!! json_encode(__('admin.imagegen.style_course') ?: 'Cinematic educational course hero banner, professional lighting, inspiring, high resolution, 16:9 landscape aspect, no text, no watermark') !!},
                size: '1792x1024',
                multiple: false,
                onApply: function (imgs) {
                    if (!imgs || !imgs.length) return;
                    var input = document.getElementById('inst-course-image-input');
                    if (input) input.value = imgs[0].url;
                }
            });
        });
    </script>

    @include('pages.admin.partials.ai-write-modal', ['languages' => $languages, 'defaultCode' => $defaultCode, 'type' => 'course', 'generateUrl' => route('instructor.generate')])
    </script>

</x-layouts.dashboard>
