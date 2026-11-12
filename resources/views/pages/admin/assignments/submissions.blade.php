<x-layouts.dashboard :title="'Submissions: ' . $assignment->title . ' - Mono LMS'" active="courses" userType="{{ auth('admin')->check() ? 'admin' : 'instructor' }}">
    @php
        $userType = auth('admin')->check() ? 'admin' : 'instructor';
        $backRoute = route($userType . '.courses.edit', $course);
    @endphp

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <a href="{{ $backRoute }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Course Settings
                </a>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $assignment->title }} - Submissions</h1>
                <p class="text-xs text-neutral-400 mt-0.5">Course: {{ $course->trans('title') }} · Total: {{ $assignment->total_points }} pts · Pass: {{ $assignment->pass_points }} pts</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl bg-[#344c3d]/10 text-[#344c3d] text-xs font-bold">
                    📝 {{ $submissions->total() }} Total Submissions
                </span>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Submissions Table -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-neutral-50 border-b border-[#e7e7e7] text-neutral-500 uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4 font-bold">Learner</th>
                            <th class="py-3 px-4 font-bold">Deliverables & Notes</th>
                            <th class="py-3 px-4 font-bold text-center">Status</th>
                            <th class="py-3 px-4 font-bold text-center">Score</th>
                            <th class="py-3 px-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0f0f0]">
                        @forelse ($submissions as $sub)
                            <tr x-data="{ grading: false }" class="hover:bg-neutral-50/50 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($sub->student?->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-900 truncate">{{ $sub->student?->name }}</div>
                                            <div class="text-[11px] text-neutral-400 truncate">{{ $sub->student?->email }}</div>
                                            <div class="text-[10px] text-neutral-400 mt-0.5">{{ $sub->submitted_at?->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 max-w-xs">
                                    <div class="space-y-1">
                                        @if ($sub->external_link)
                                            <a href="{{ $sub->external_link }}" target="_blank" rel="noopener" class="text-primary-600 hover:underline font-semibold flex items-center gap-1 truncate">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                {{ $sub->external_link }}
                                            </a>
                                        @endif
                                        @if ($sub->file_path)
                                            <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" download class="inline-flex items-center gap-1 text-[11px] text-slate-700 font-medium hover:text-[#344c3d]">
                                                <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Attached File
                                            </a>
                                        @endif
                                        @if ($sub->submission_content)
                                            <p class="text-[11px] text-neutral-600 line-clamp-2 italic">“{{ $sub->submission_content }}”</p>
                                        @endif
                                        @if ($sub->resubmission_note)
                                            <p class="text-[10px] text-amber-700 bg-amber-50 p-1.5 rounded border border-amber-200 mt-1">
                                                <strong>Retest Note:</strong> {{ $sub->resubmission_note }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    @if ($sub->status === 'graded')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $sub->isPassed() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $sub->isPassed() ? 'Passed ✓' : 'Revision' }}
                                        </span>
                                    @elseif ($sub->status === 'resubmission_requested')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                            Retest Requested
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">
                                            Submitted
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    @if ($sub->score !== null)
                                        <span class="font-extrabold text-sm text-slate-800">{{ $sub->score }}</span>
                                        <span class="text-neutral-400 text-[10px]">/ {{ $assignment->total_points }}</span>
                                    @else
                                        <span class="text-neutral-400 italic">Not graded</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" @click="grading = !grading" class="px-3 py-1.5 bg-[#344c3d] hover:bg-[#283a2f] text-white font-bold text-[11px] rounded-lg transition-colors shadow-sm">
                                        {{ $sub->isGraded() ? 'Re-grade' : 'Grade / Feedback' }}
                                    </button>

                                    <!-- Grading Drawer / Form Modal -->
                                    <div x-show="grading" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm" @click.self="grading = false">
                                        <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-[#e7e7e7] space-y-4" @click.stop>
                                            <div class="flex items-center justify-between border-b border-[#e7e7e7] pb-3">
                                                <h3 class="font-bold text-sm text-slate-900">Grade {{ $sub->student?->name }}</h3>
                                                <button type="button" @click="grading = false" class="text-neutral-400 hover:text-neutral-700 text-base">&times;</button>
                                            </div>

                                            <form action="{{ route($userType . '.assignments.grade', [$assignment, $sub]) }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Score (Out of {{ $assignment->total_points }} pts)</label>
                                                    <input type="number" name="score" min="0" max="{{ $assignment->total_points }}" value="{{ old('score', $sub->score ?? $assignment->pass_points) }}" required class="w-full text-sm border border-neutral-300 rounded-xl px-3 py-2 bg-white">
                                                    <p class="text-[11px] text-neutral-400 mt-1">Passing score: {{ $assignment->pass_points }} pts</p>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Instructor Feedback & Critique</label>
                                                    <textarea name="instructor_feedback" rows="3" placeholder="Provide constructive feedback, suggestions, or what was done well..." class="w-full text-xs border border-neutral-300 rounded-xl p-3 bg-white">{{ old('instructor_feedback', $sub->instructor_feedback) }}</textarea>
                                                </div>

                                                <div class="space-y-2 pt-1">
                                                    <label class="block text-xs font-semibold text-slate-700">Action Decision</label>
                                                    <div class="flex items-center gap-4 text-xs">
                                                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                            <input type="radio" name="action" value="grade" checked class="text-[#344c3d] focus:ring-0">
                                                            <span>Mark as Graded</span>
                                                        </label>
                                                        @if ($assignment->allow_resubmission)
                                                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                                <input type="radio" name="action" value="request_resubmission" class="text-amber-600 focus:ring-0">
                                                                <span>Request Resubmission / Retest</span>
                                                            </label>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex justify-end gap-2 pt-2 border-t border-[#e7e7e7]">
                                                    <button type="button" @click="grading = false" class="px-4 py-2 text-xs text-neutral-500 hover:text-neutral-700">Cancel</button>
                                                    <button type="submit" class="px-5 py-2 bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-bold rounded-xl transition-colors">
                                                        Submit Grade
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs text-neutral-400">
                                    No students have submitted this assignment yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($submissions->hasPages())
                <div class="p-4 border-t border-[#e7e7e7]">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
