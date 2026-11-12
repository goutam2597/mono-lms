@php
    $userType = $userType ?? 'admin';
    $assignments = $course->assignments()
        ->withCount([
            'submissions as total_submissions',
            'submissions as graded_submissions' => fn ($q) => $q->where('status', \App\Models\AssignmentSubmission::STATUS_GRADED),
            'submissions as pending_submissions' => fn ($q) => $q->whereIn('status', [
                \App\Models\AssignmentSubmission::STATUS_SUBMITTED,
                \App\Models\AssignmentSubmission::STATUS_RESUBMITTED,
                \App\Models\AssignmentSubmission::STATUS_RESUBMISSION_REQUESTED,
            ]),
        ])
        ->get();
    $liveClassesWithAttendance = $course->liveClasses()->with(['attendances.student'])->get();
@endphp

<!-- Assignments & Homework Manager (Ostad Cohort-Based Learning) -->
<div class="bg-white border border-[#e7e7e7] p-6 sm:p-8 space-y-6">
    <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
        <div>
            <h3 class="font-bold text-[#191919] text-base">Assignments & Practical Projects</h3>
            <p class="text-xs text-neutral-400 mt-0.5">{{ $assignments->count() }} assignments · hands-on projects, submissions, instructor evaluation, and resubmissions</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-[#344c3d]/10 text-[#344c3d] border border-[#344c3d]/20 text-xs font-semibold">
            Cohort Assessment Engine
        </span>
    </div>

    <!-- Add Assignment Form -->
    <form action="{{ route($userType . '.assignments.store', $course) }}" method="POST" class="space-y-4 bg-neutral-50 dark:bg-neutral-900/50 p-6 sm:p-7 border border-neutral-200 dark:border-neutral-800 rounded-2xl">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="space-y-1.5 sm:col-span-2">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Assignment Title *</label>
                <input type="text" name="title" required placeholder="e.g. Build an E-Commerce API or React Portfolio Project" class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Total Points *</label>
                <input type="number" name="total_points" min="1" max="1000" value="100" required class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Pass Points *</label>
                <input type="number" name="pass_points" min="1" max="1000" value="50" required class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5 sm:col-span-2">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Submission Deadline</label>
                <input type="datetime-local" name="due_date" class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Status</label>
                <select name="status" class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
                    <option value="published" selected>Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <div class="space-y-1.5 flex items-center pt-5">
                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 dark:text-neutral-300">
                    <input type="checkbox" name="allow_resubmission" value="1" checked class="w-4 h-4 rounded border-neutral-300 text-[#344c3d] focus:ring-0">
                    <span>Allow Retest / Resubmission</span>
                </label>
            </div>

            <div class="space-y-1.5 sm:col-span-4">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Instructions & Guidelines</label>
                <textarea name="description" rows="3" placeholder="Provide problem statement, required deliverables, evaluation rubrics, and submission guidelines..." class="w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-3 text-xs rounded-lg focus:outline-none focus:border-neutral-900"></textarea>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-5 py-2.5 bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-bold rounded-xl transition-colors shadow-sm">
                + Create Assignment
            </button>
        </div>
    </form>

    <!-- Assignment List -->
    <div class="space-y-3">
        @forelse ($assignments as $assignment)
            <div x-data="{ editing: false }" class="border border-[#e7e7e7] rounded-xl p-4 sm:p-5 bg-white space-y-3 hover:border-neutral-300 transition-colors">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="font-bold text-sm text-slate-900 truncate">{{ $assignment->title }}</h4>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $assignment->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-neutral-100 text-neutral-600' }}">
                                {{ ucfirst($assignment->status) }}
                            </span>
                            @if ($assignment->allow_resubmission)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                    Retest Allowed
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-neutral-400 mt-1">
                            Total: {{ $assignment->total_points }} pts · Pass: {{ $assignment->pass_points }} pts ·
                            Due: {{ $assignment->due_date ? $assignment->due_date->format('M d, Y h:i A') : 'No deadline' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Submission Badge / Link -->
                        <a href="{{ route($userType . '.assignments.submissions', $assignment) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#344c3d]/10 hover:bg-[#344c3d]/20 text-[#344c3d] transition-colors">
                            <span>Submissions ({{ $assignment->total_submissions }})</span>
                            @if ($assignment->pending_submissions > 0)
                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            @endif
                        </a>
                        <button type="button" @click="editing = !editing" class="p-1.5 text-neutral-400 hover:text-neutral-700 transition-colors" title="Edit Assignment">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <form action="{{ route($userType . '.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Delete this assignment and all submissions?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-neutral-400 hover:text-rose-600 transition-colors" title="Delete Assignment">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Edit Assignment Form Accordion -->
                <form x-show="editing" x-cloak action="{{ route($userType . '.assignments.update', $assignment) }}" method="POST" class="pt-3 border-t border-neutral-100 space-y-3 bg-neutral-50/70 p-4 rounded-xl">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="sm:col-span-2">
                            <label class="text-[10px] font-bold uppercase text-neutral-500">Title</label>
                            <input type="text" name="title" value="{{ $assignment->title }}" required class="h-9 w-full text-xs border border-neutral-300 rounded px-2.5 bg-white">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase text-neutral-500">Total Points</label>
                            <input type="number" name="total_points" value="{{ $assignment->total_points }}" required class="h-9 w-full text-xs border border-neutral-300 rounded px-2.5 bg-white">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase text-neutral-500">Pass Points</label>
                            <input type="number" name="pass_points" value="{{ $assignment->pass_points }}" required class="h-9 w-full text-xs border border-neutral-300 rounded px-2.5 bg-white">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-[10px] font-bold uppercase text-neutral-500">Due Date</label>
                            <input type="datetime-local" name="due_date" value="{{ $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '' }}" class="h-9 w-full text-xs border border-neutral-300 rounded px-2.5 bg-white">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase text-neutral-500">Status</label>
                            <select name="status" class="h-9 w-full text-xs border border-neutral-300 rounded px-2.5 bg-white">
                                <option value="published" @selected($assignment->status === 'published')>Published</option>
                                <option value="draft" @selected($assignment->status === 'draft')>Draft</option>
                            </select>
                        </div>
                        <div class="flex items-center pt-4">
                            <label class="inline-flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="allow_resubmission" value="1" @checked($assignment->allow_resubmission) class="w-3.5 h-3.5 rounded text-[#344c3d]">
                                <span>Allow Retest</span>
                            </label>
                        </div>
                        <div class="sm:col-span-4">
                            <label class="text-[10px] font-bold uppercase text-neutral-500">Description</label>
                            <textarea name="description" rows="2" class="w-full text-xs border border-neutral-300 rounded p-2 bg-white">{{ $assignment->description }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="editing = false" class="px-3 py-1.5 text-xs text-neutral-500 hover:text-neutral-700">Cancel</button>
                        <button type="submit" class="px-4 py-1.5 bg-[#344c3d] text-white text-xs font-bold rounded-lg hover:bg-[#283a2f]">Save Changes</button>
                    </div>
                </form>
            </div>
        @empty
            <p class="text-xs text-neutral-400 py-4 text-center">No assignments created yet for this course.</p>
        @endforelse
    </div>

    <!-- Live Class Attendance Roster (Ostad Live Learning Feature) -->
    @if ($liveClassesWithAttendance->isNotEmpty())
        <div class="pt-6 border-t border-neutral-200 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-sm text-[#191919]">Live Class Attendance Tracking</h4>
                    <p class="text-xs text-neutral-400">Automatic attendance logs recorded when students join live Zoom / Meet sessions</p>
                </div>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded border border-emerald-200">
                    Live Session Roster
                </span>
            </div>

            <div class="divide-y divide-neutral-100 border border-[#e7e7e7] rounded-xl overflow-hidden bg-white">
                @foreach ($liveClassesWithAttendance as $liveSession)
                    <div x-data="{ showRoster: false }" class="p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-xs text-slate-900">{{ $liveSession->title }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $liveSession->classTypeBadgeClasses() }}">
                                        {{ $liveSession->classTypeLabel() }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-neutral-400 mt-0.5">
                                    {{ $liveSession->starts_at->format('M d, Y h:i A') }} · {{ $liveSession->platformLabel() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-700">
                                    {{ $liveSession->attendances->count() }} Attendee{{ $liveSession->attendances->count() === 1 ? '' : 's' }}
                                </span>
                                @if ($liveSession->attendances->isNotEmpty())
                                    <button type="button" @click="showRoster = !showRoster" class="text-xs font-semibold text-[#344c3d] hover:underline">
                                        <span x-text="showRoster ? 'Hide Students' : 'View Students'"></span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Attendee Students List -->
                        <div x-show="showRoster" x-cloak class="mt-3 pt-3 border-t border-neutral-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($liveSession->attendances as $attendance)
                                    <div class="flex items-center gap-2 p-2 rounded-lg bg-neutral-50 text-xs">
                                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px]">
                                            {{ strtoupper(substr($attendance->student?->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1 truncate">
                                            <div class="font-medium text-slate-800 truncate">{{ $attendance->student?->name }}</div>
                                            <div class="text-[10px] text-neutral-400">{{ $attendance->joined_at?->format('h:i A') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
