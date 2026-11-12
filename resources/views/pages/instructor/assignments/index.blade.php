<x-layouts.dashboard :title="'Assignments: ' . $course->trans('title') . ' - Mono LMS'" active="courses" userType="instructor">
    <div class="space-y-6">
        <div>
            <a href="{{ route('instructor.courses.edit', $course) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Course Editor
            </a>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $course->trans('title') }} - Assignments & Cohort Engine</h1>
            <p class="text-xs text-neutral-400 mt-0.5">Manage hands-on assignments, grading, submissions, and live class attendance roster.</p>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @include('pages.admin.partials.course-assignments-manager', ['course' => $course, 'userType' => 'instructor'])
    </div>
</x-layouts.dashboard>
