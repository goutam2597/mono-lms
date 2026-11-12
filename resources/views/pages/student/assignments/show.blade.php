<x-layouts.dashboard :title="'Assignment: ' . $assignment->title . ' - Mono LMS'" active="enrolled-courses" userType="student">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="min-w-0">
                <a href="{{ route('student.courses.learn', $course) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Course
                </a>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $assignment->title }}</h1>
                <p class="text-xs text-neutral-400 mt-0.5">Course: {{ $course->trans('title') }} · By {{ $course->instructor?->name }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#344c3d]/10 text-[#344c3d]">
                    🎯 {{ $assignment->total_points }} Total Points
                </span>
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                    Pass: {{ $assignment->pass_points }} pts
                </span>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left 2 cols: Instructions & Description -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-sm">
                    <h2 class="text-base font-bold text-[#191919] mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#344c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Assignment Guidelines & Tasks
                    </h2>
                    <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $assignment->description ?: 'No detailed instructions provided. Please submit your work following the instructor guidelines.' }}
                    </div>
                </div>

                <!-- Existing Submission / Feedback details if available -->
                @if ($submission)
                    <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-[#e7e7e7] pb-3">
                            <h3 class="text-sm font-bold text-[#191919]">Your Submitted Solution</h3>
                            <span class="text-xs text-neutral-400">Submitted on {{ $submission->submitted_at?->format('d M Y, h:i A') }}</span>
                        </div>

                        @if ($submission->external_link)
                            <div class="flex items-center gap-2 text-xs">
                                <span class="font-semibold text-slate-600">Project / Code Link:</span>
                                <a href="{{ $submission->external_link }}" target="_blank" rel="noopener" class="text-primary-600 hover:underline font-medium break-all flex items-center gap-1">
                                    {{ $submission->external_link }}
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        @endif

                        @if ($submission->file_path)
                            <div class="flex items-center gap-2 text-xs">
                                <span class="font-semibold text-slate-600">Attached File:</span>
                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-neutral-100 hover:bg-neutral-200 text-slate-800 font-medium transition-colors">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download Attachment
                                </a>
                            </div>
                        @endif

                        @if ($submission->submission_content)
                            <div class="space-y-1">
                                <span class="text-xs font-semibold text-slate-600">Submission Notes:</span>
                                <div class="bg-neutral-50 p-3 rounded-xl text-xs text-slate-700 whitespace-pre-line border border-neutral-100">
                                    {{ $submission->submission_content }}
                                </div>
                            </div>
                        @endif

                        <!-- Instructor Grading & Feedback Box -->
                        @if ($submission->isGraded())
                            <div class="p-4 rounded-xl {{ $submission->isPassed() ? 'bg-emerald-50 border border-emerald-200' : 'bg-rose-50 border border-rose-200' }} mt-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold uppercase tracking-wider {{ $submission->isPassed() ? 'text-emerald-800' : 'text-rose-800' }}">
                                            Grade: {{ $submission->score }} / {{ $assignment->total_points }} pts
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $submission->isPassed() ? 'bg-emerald-200 text-emerald-900' : 'bg-rose-200 text-rose-900' }}">
                                            {{ $submission->isPassed() ? 'PASSED ✓' : 'NEEDS REVISION' }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-neutral-500">Graded {{ $submission->graded_at?->diffForHumans() }}</span>
                                </div>
                                @if ($submission->instructor_feedback)
                                    <div class="text-xs text-slate-700 pt-1">
                                        <span class="font-bold">Instructor Feedback:</span>
                                        <p class="mt-0.5 italic">“{{ $submission->instructor_feedback }}”</p>
                                    </div>
                                @endif
                            </div>
                        @elseif ($submission->status === 'resubmission_requested')
                            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs mt-4">
                                <strong>Resubmission Request Pending:</strong> Your instructor has been notified to allow a retest / resubmission.
                                @if ($submission->resubmission_note)
                                    <p class="mt-1 italic">Reason: "{{ $submission->resubmission_note }}"</p>
                                @endif
                            </div>
                        @else
                            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs mt-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Awaiting evaluation by your instructor.</span>
                            </div>
                        @endif

                        <!-- Retest / Resubmission Request Button (Ostad workflow) -->
                        @if ($assignment->allow_resubmission && $submission->status !== 'resubmission_requested')
                            <div x-data="{ open: false }" class="pt-3 border-t border-[#e7e7e7]">
                                <button type="button" @click="open = !open" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 hover:text-amber-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Request Retest / Resubmission
                                </button>
                                <form x-show="open" x-cloak action="{{ route('student.assignments.resubmit-request', [$course, $assignment]) }}" method="POST" class="mt-3 space-y-2 bg-amber-50/50 p-4 rounded-xl border border-amber-200">
                                    @csrf
                                    <label class="block text-xs font-semibold text-slate-700">Explain why you need a resubmission / retake:</label>
                                    <textarea name="resubmission_note" rows="2" required placeholder="e.g. I updated the bug in my GitHub repository..." class="w-full text-xs rounded-lg border border-neutral-200 p-2.5 focus:border-[#344c3d] focus:ring-0"></textarea>
                                    <button type="submit" class="px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs rounded-lg transition-colors">
                                        Send Retest Request
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right col: Deadline & Submission Form -->
            <div class="space-y-6">
                <!-- Meta card -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400">Assignment Details</h3>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-[#f0f0f0]">
                            <span class="text-neutral-500">Total Marks:</span>
                            <span class="font-bold text-slate-900">{{ $assignment->total_points }} pts</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-[#f0f0f0]">
                            <span class="text-neutral-500">Pass Marks:</span>
                            <span class="font-bold text-emerald-700">{{ $assignment->pass_points }} pts</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-[#f0f0f0]">
                            <span class="text-neutral-500">Due Date:</span>
                            <span class="font-bold {{ $assignment->isPastDue() ? 'text-rose-600' : 'text-slate-900' }}">
                                {{ $assignment->due_date ? $assignment->due_date->format('d M Y, h:i A') : 'No deadline' }}
                            </span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-[#f0f0f0]">
                            <span class="text-neutral-500">Resubmission:</span>
                            <span class="font-bold {{ $assignment->allow_resubmission ? 'text-emerald-700' : 'text-neutral-400' }}">
                                {{ $assignment->allow_resubmission ? 'Allowed' : 'Single Attempt' }}
                            </span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-neutral-500">Your Status:</span>
                            @if (! $submission)
                                <span class="font-bold text-neutral-500">Not Submitted</span>
                            @elseif ($submission->isGraded())
                                <span class="font-bold {{ $submission->isPassed() ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $submission->isPassed() ? 'Passed' : 'Needs Revision' }}
                                </span>
                            @else
                                <span class="font-bold text-blue-600">Under Review</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Submit / Resubmit Form -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-5 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-[#191919]">
                        {{ $submission ? 'Update / Resubmit Work' : 'Submit Assignment' }}
                    </h3>
                    <form action="{{ route('student.assignments.submit', [$course, $assignment]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Project Link (GitHub / Drive / Figma / Live URL)</label>
                            <input type="url" name="external_link" value="{{ old('external_link', $submission?->external_link) }}" placeholder="https://github.com/username/project" class="w-full text-xs rounded-xl border border-neutral-200 px-3 py-2.5 focus:border-[#344c3d] focus:ring-0">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Attach File (ZIP / PDF / DOCX, max 20MB)</label>
                            <input type="file" name="file" class="w-full text-xs text-neutral-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#344c3d]/10 file:text-[#344c3d] hover:file:bg-[#344c3d]/20">
                            @if ($submission?->file_path)
                                <p class="text-[11px] text-neutral-400 mt-1">Leave blank to keep existing file.</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Notes / Explanation</label>
                            <textarea name="submission_content" rows="4" placeholder="Describe your solution, any setup steps, or notes for your instructor..." class="w-full text-xs rounded-xl border border-neutral-200 p-3 focus:border-[#344c3d] focus:ring-0">{{ old('submission_content', $submission?->submission_content) }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-[#344c3d] hover:bg-[#283a2f] text-white font-bold text-xs rounded-xl transition-colors shadow-sm">
                            {{ $submission ? 'Save & Resubmit Work' : 'Submit Assignment' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
