<x-layouts.dashboard :title="'Learn: ' . $course->trans('title') . ' - Mono LMS'" active="enrolled-courses" userType="student">

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="min-w-0">
                <a href="{{ route('student.enrolled-courses') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    My Courses
                </a>
                <h1 class="text-lg sm:text-xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $course->trans('title') }}</h1>
                <p class="text-xs text-neutral-400">By {{ $course->instructor?->name }} · {{ $course->typeLabel() }} · {{ $enrollment?->progress ?? 0 }}% complete</p>
            </div>
            <div class="shrink-0 bg-white border border-[#e7e7e7] rounded-xl px-5 py-3 min-w-[180px]">
                <div class="flex items-center justify-between text-[11px] font-semibold text-neutral-500">
                    <span>Progress</span><span>{{ $enrollment?->progress ?? 0 }}%</span>
                </div>
                <div class="h-2 bg-neutral-100 rounded-full mt-1.5 overflow-hidden">
                    <div class="h-full bg-[#344c3d] rounded-full transition-all" style="width: {{ $enrollment?->progress ?? 0 }}%"></div>
                </div>
            </div>
        </div>

        @if ($isPreviewMode ?? false)
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 12.75v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    <span><strong>Free Preview Lesson:</strong> You are currently watching an unlocked sample lecture. Enroll in the full course to track progress and earn your certificate.</span>
                </span>
                <a href="{{ route('courses.show', $course->slug) }}" class="bg-[#344c3d] hover:bg-[#25372c] text-white px-4 py-2 rounded-lg font-bold shrink-0 text-center transition-colors">
                    Enroll Full Course
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-6 items-start">
            <!-- Player / Live Classes -->
            <div class="space-y-5">
                @if ($lesson && $lesson->hasVideo())
                    <div class="bg-black rounded-2xl overflow-hidden shadow-lg">
                        @if ($lesson->isUploadedVideo())
                            <video controls preload="metadata" class="w-full aspect-video" src="{{ $lesson->playerUrl() }}"></video>
                        @else
                            <div class="aspect-video">
                                <iframe src="{{ $lesson->playerUrl() }}" class="w-full h-full" title="{{ $lesson->title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h2 class="font-bold text-[#191919] text-base">{{ $lesson->title }}</h2>
                            <p class="text-xs text-neutral-400 mt-0.5">Lesson {{ $lesson->sort_order }} · {{ $lesson->duration_minutes }} min</p>
                        </div>
                        @php $isCompleted = in_array($lesson->id, $completedIds) @endphp
                        @if ($isCompleted)
                            <span class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">✓ Completed</span>
                        @else
                            <form action="{{ route('student.lessons.complete', $lesson) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-colors">Mark as Completed</button>
                            </form>
                        @endif
                    </div>
                @elseif ($lesson)
                    <div class="bg-white border border-[#e7e7e7] rounded-2xl p-10 text-center">
                        <h2 class="font-bold text-[#191919] text-base">{{ $lesson->title }}</h2>
                        <p class="text-sm text-neutral-400 mt-2">This lesson has no video attached yet — check back later.</p>
                    </div>
                @elseif ($hasRecordedContent)
                    <div class="bg-white border border-[#e7e7e7] rounded-2xl p-10 text-center">
                        <p class="text-sm text-neutral-400">No recorded lessons published in this course yet.</p>
                    </div>
                @endif

                {{-- Live classes section (live / combined) --}}
                @if ($hasLiveContent)
                    <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-[#e7e7e7] flex items-center justify-between">
                            <h3 class="font-bold text-[#191919] text-sm">Live Classes</h3>
                            <span class="text-[11px] text-neutral-400">{{ $liveClasses->count() }} total</span>
                        </div>
                        <div class="divide-y divide-[#e7e7e7]">
                            @forelse ($liveClasses as $live)
                                <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-semibold text-[#191919] truncate">{{ $live->title }}</p>
                                            @if ($live->quizzes->isNotEmpty())
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-purple-100 text-purple-800">
                                                    Quiz Available
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-neutral-400 mt-0.5">
                                            {{ $live->starts_at->format('D, d M Y · h:i A') }} · {{ $live->platformLabel() }} · {{ $live->duration_minutes }} min
                                            @if ($live->status !== 'scheduled')<span class="ml-1 font-semibold uppercase">{{ $live->status }}</span>@endif
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if ($live->quizzes->isNotEmpty())
                                            @php $liveQuiz = $live->quizzes->first(); @endphp
                                            <a href="{{ route('student.quizzes.take', [$course, $liveQuiz]) }}"
                                               class="inline-flex items-center gap-1.5 border border-purple-200 hover:border-purple-400 text-purple-700 bg-purple-50 text-xs font-bold px-3 py-2 rounded-lg transition-colors">
                                                Session Quiz
                                            </a>
                                        @endif
                                        @if ($live->isUpcoming())
                                            <a href="{{ route('student.live-classes.join', $live) }}" target="_blank" rel="noopener"
                                               class="inline-flex items-center gap-1.5 bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors">
                                                Join Class
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @elseif ($live->recording_url)
                                            <a href="{{ $live->recording_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 border border-[#e7e7e7] hover:border-[#344c3d] text-[#344c3d] text-xs font-bold px-4 py-2 rounded-lg transition-colors">Watch Recording</a>
                                        @else
                                            <span class="text-[11px] text-neutral-400">{{ ucfirst($live->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="px-5 py-8 text-center text-xs text-neutral-400">No live classes scheduled yet.</p>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- Course Quizzes & Live Assessments -->
                @if (isset($quizzes) && $quizzes->isNotEmpty())
                    <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-[#e7e7e7] flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-[#191919] text-sm">{{ $course->hasLiveContent() ? 'Quizzes & Live Assessments' : 'Quizzes & Course Assessments' }}</h3>
                                <p class="text-[11px] text-neutral-400 mt-0.5">Test your understanding to earn course certification</p>
                            </div>
                            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded border border-emerald-200">
                                {{ $quizzes->count() }} Total
                            </span>
                        </div>
                        <div class="divide-y divide-[#e7e7e7]">
                            @foreach ($quizzes as $quizItem)
                                @php
                                    $studentUser = auth('student')->user();
                                    $best = $studentUser ? $quizItem->bestAttemptFor($studentUser) : null;
                                    $canTake = $studentUser ? $quizItem->canAttempt($studentUser) : false;
                                    $attemptsCount = $studentUser ? $quizItem->attemptsCount($studentUser) : 0;
                                @endphp
                                <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/50 transition-colors">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-semibold text-[#191919] truncate">{{ $quizItem->trans('title') }}</p>
                                            @if ($quizItem->liveClass)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-purple-100 text-purple-800">
                                                    Live Class: {{ $quizItem->liveClass->title }}
                                                </span>
                                            @elseif ($course->hasLiveContent())
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold uppercase rounded bg-blue-100 text-blue-800">
                                                    {{ $course->isLive() ? 'Live Assessment' : 'Course Quiz' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold uppercase rounded bg-emerald-100 text-emerald-800">
                                                    Course Assessment
                                                </span>
                                            @endif
                                            @if ($best && $best->is_passed)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-800">
                                                    Passed ({{ $best->formattedScore() }}) ✓
                                                </span>
                                            @elseif ($attemptsCount > 0)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded bg-rose-100 text-rose-800">
                                                    Attempted ({{ $best->formattedScore() }})
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-neutral-400 mt-0.5">
                                            {{ $quizItem->questions_count }} Questions · {{ $quizItem->time_limit_minutes > 0 ? $quizItem->time_limit_minutes.' min' : 'Untimed' }} · Passing: {{ $quizItem->passing_percentage }}% · Attempts: {{ $attemptsCount }}/{{ $quizItem->max_attempts ?: '∞' }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if ($best)
                                            <a href="{{ route('student.quizzes.review', $best) }}"
                                               class="action-btn action-btn-ghost h-9 px-3.5 text-[10px] font-bold uppercase tracking-wider">
                                                Review
                                            </a>
                                        @endif
                                        @if ($canTake)
                                            <a href="{{ route('student.quizzes.take', [$course, $quizItem]) }}"
                                               class="action-btn action-btn-primary h-9 px-4 text-[10px] font-bold uppercase tracking-wider">
                                                {{ $attemptsCount > 0 ? 'Retake Quiz' : 'Take Quiz' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar: lesson list -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#e7e7e7] flex items-center justify-between">
                    <h3 class="font-bold text-[#191919] text-sm">Course Content</h3>
                    <span class="text-[11px] text-neutral-400">{{ count($completedIds) }}/{{ $lessons->count() }} lessons</span>
                </div>
                <div class="max-h-[560px] overflow-y-auto divide-y divide-[#f0f0f0]">
                    @forelse ($lessons as $l)
                        <a href="{{ route('student.courses.learn', ['course' => $course, 'lesson' => $l->id]) }}"
                           class="px-4 py-3 flex items-center gap-3 transition-colors {{ ($lesson?->id === $l->id) ? 'bg-[#f8fafb] border-l-2 border-[#344c3d]' : 'hover:bg-[#f8fafb]' }}">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0
                                {{ in_array($l->id, $completedIds) ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-100 text-neutral-500' }}">
                                @if (in_array($l->id, $completedIds))✓@else{{ $l->sort_order }}@endif
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="flex items-center gap-1.5 justify-between">
                                    <span class="text-[13px] font-medium text-[#191919] truncate">{{ $l->title }}</span>
                                    @if ($l->isFree())
                                        <span class="text-[9px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 shrink-0">Free Preview</span>
                                    @endif
                                </span>
                                <span class="block text-[11px] text-neutral-400">{{ $l->duration_minutes }} min @if($l->isUploadedVideo()) · 📹 uploaded @elseif($l->video_url) · ▶ youtube @endif</span>
                            </span>
                        </a>
                    @empty
                        <p class="px-4 py-8 text-center text-xs text-neutral-400">No lessons yet.</p>
                    @endforelse
                </div>
                @if ($hasLiveContent)
                    <div class="px-5 py-3 border-t border-[#e7e7e7] bg-[#f8fafb] flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-neutral-500">Live classes</span>
                        <span class="text-[11px] font-bold text-emerald-700">{{ $upcomingClasses->count() }} upcoming</span>
                    </div>
                @endif
                @if (isset($quizzes) && $quizzes->isNotEmpty())
                    <div class="px-5 py-3 border-t border-[#e7e7e7] bg-[#f8fafb] flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-neutral-500">Quizzes & Tests</span>
                        <span class="text-[11px] font-bold text-[#344c3d]">{{ $quizzes->count() }} available</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layouts.dashboard>
