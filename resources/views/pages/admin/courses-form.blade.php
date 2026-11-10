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
            <form action="{{ $course->exists ? route('admin.courses.update', $course) : route('admin.courses.store') }}" method="POST" class="space-y-6">
                @csrf
                @if ($course->exists) @method('PUT') @endif

                <!-- Course Title -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.title') }} *</label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                    @error('title')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- Subtitle / Short Description -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.subtitle') }}</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $course->subtitle) }}" placeholder="Brief tagline or hook for the course card" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

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
                        @error('price')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.old_price') }} (USD / Strike-through)</label>
                        <input type="number" step="0.01" min="0" name="old_price" value="{{ old('old_price', $course->old_price) }}" placeholder="Leave blank if no sale discount" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                        @error('old_price')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Course Thumbnail Media Selector -->
                <div>
                    <x-media-selector
                        name="image"
                        :value="old('image', $course->image)"
                        label="Course Thumbnail / Hero Banner"
                        shape="banner"
                        hint="Picked from media library. Recommended size: 1280x720 (16:9 ratio)."
                    />
                </div>

                <!-- Full Description -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.courses.fields.description') }}</label>
                    <textarea name="description" rows="6" class="w-full border border-neutral-300 p-4 text-sm focus:outline-none focus:border-neutral-900">{{ old('description', $course->description) }}</textarea>
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
                    <form action="{{ route('admin.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-3 bg-neutral-50 p-4 border border-neutral-200" data-video-source-form>
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-[1fr_140px] gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.lessons.fields.title') }} *</label>
                                <input type="text" name="title" required placeholder="e.g. 01. Course Overview & Introduction" class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-sm focus:outline-none focus:border-neutral-900">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">{{ __('instructor.lessons.fields.duration') }} (mins)</label>
                                <input type="number" name="duration_minutes" min="0" placeholder="15" class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-sm focus:outline-none focus:border-neutral-900">
                            </div>
                        </div>

                        @include('pages.admin.partials.lesson-video-fields', ['prefix' => 'add'])

                        <div class="flex justify-end">
                            <button type="submit" class="action-btn action-btn-primary h-10 px-5 text-[10px] font-semibold uppercase tracking-[0.12em] whitespace-nowrap">
                                + {{ __('instructor.lessons.add') }}
                            </button>
                        </div>
                    </form>

                    <!-- Lessons List -->
                    <div class="divide-y divide-neutral-200 border border-neutral-200 overflow-hidden">
                        @forelse ($course->lessons as $lesson)
                            <div class="px-4 py-3.5">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 bg-emerald-50 text-[#344c3d] text-xs font-bold flex items-center justify-center shrink-0 border border-emerald-200/50">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-xs text-[#191919] truncate">{{ $lesson->title }}</p>
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
                                    <div class="flex items-center gap-3 shrink-0">
                                        @if ($lesson->duration_minutes)
                                            <span class="text-xs text-neutral-400 font-medium">{{ $lesson->duration_minutes }}m</span>
                                        @endif
                                        <button type="button" data-edit-toggle="lesson-edit-{{ $lesson->id }}" class="action-btn action-btn-ghost h-7 px-3 text-[10px]">Edit</button>
                                        <form action="{{ route('admin.courses.lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Remove this lesson?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px]">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <form id="lesson-edit-{{ $lesson->id }}" hidden class="mt-4 border-t border-neutral-200 pt-4" action="{{ route('admin.courses.lessons.update', $lesson) }}" method="POST" enctype="multipart/form-data" data-video-source-form>
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 sm:grid-cols-[1fr_140px] gap-3">
                                        <div class="space-y-1.5">
                                            <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">Title *</label>
                                            <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-sm focus:outline-none focus:border-neutral-900">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600">Duration (mins)</label>
                                            <input type="number" name="duration_minutes" min="0" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" class="h-10 w-full border border-neutral-300 bg-white px-3.5 text-sm focus:outline-none focus:border-neutral-900">
                                        </div>
                                    </div>

                                    @include('pages.admin.partials.lesson-video-fields', ['prefix' => 'edit-'.$lesson->id, 'lesson' => $lesson])

                                    <div class="flex justify-end mt-3">
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
            if (form) form.hidden = ! form.hidden;
        });
    </script>

</x-layouts.dashboard>
