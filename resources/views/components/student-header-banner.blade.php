@props([
    'student' => null,
    'name' => 'Eugene Andre',
    'role' => 'Student',
    'avatar' => '',
    'enrolled' => '00',
    'certificates' => '00',
    'completed' => '00'
])

@php
    $student = $student ?? auth('student')->user();
    $name = $student?->name ?? $name;
    $email = $student?->email ?? 'student@mono-lms.com';
    $avatar = $avatar ?: ($student?->avatarUrl() ?? '');
    $enrolledCount = $student ? $student->enrollments()->count() : (int) $enrolled;
    $completedCount = $student ? $student->enrollments()->where('status', 'completed')->count() : (int) $completed;
    $initial = strtoupper(substr($name, 0, 1) ?: 'S');
@endphp

<div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 sm:p-6 shadow-xs mb-6 relative overflow-hidden">
    <!-- Top Brand Accent Strip -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-[var(--theme-primary,#344c3d)]"></div>

    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 pt-1">
        <!-- Student Info -->
        <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
            <div class="relative shrink-0">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-[8px] overflow-hidden ring-1 ring-slate-200 bg-slate-100 flex items-center justify-center shadow-xs">
                    @if ($avatar)
                        <img src="{{ $avatar }}"
                             alt="{{ $name }}"
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full bg-[var(--theme-primary,#344c3d)] text-white font-bold text-2xl items-center justify-center hidden">
                            {{ $initial }}
                        </div>
                    @else
                        <div class="w-full h-full bg-[var(--theme-primary,#344c3d)] text-white font-bold text-2xl flex items-center justify-center">
                            {{ $initial }}
                        </div>
                    @endif
                </div>
                <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full" title="Active"></span>
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight truncate">{{ $name }}</h1>
                    <span class="inline-flex items-center gap-1 bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20 text-[11px] font-semibold px-2 py-0.5 rounded-[4px]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--theme-primary,#344c3d)]"></span>
                        {{ $role }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 mt-1">
                    <span class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="truncate">{{ $email }}</span>
                    </span>
                    <span class="hidden sm:inline text-slate-300">•</span>
                    <span class="text-slate-400">
                        {{ __('app.common.joined') ?? 'Joined' }}: {{ $student?->created_at?->format('M Y') ?? '2026' }}
                    </span>
                </div>

                <!-- Quick Inline Stat Pills -->
                <div class="flex flex-wrap items-center gap-3 mt-3 pt-2 border-t border-slate-100 text-xs">
                    <div class="flex items-center gap-1.5 text-slate-600">
                        <span class="font-bold text-slate-900">{{ str_pad((string) $enrolledCount, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-slate-400">{{ __('student.nav.enrolled') }}</span>
                    </div>
                    <span class="text-slate-200">|</span>
                    <div class="flex items-center gap-1.5 text-slate-600">
                        <span class="font-bold text-emerald-600">{{ str_pad((string) $completedCount, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-slate-400">{{ __('student.dashboard.completed') }}</span>
                    </div>
                    <span class="text-slate-200">|</span>
                    <div class="flex items-center gap-1.5 text-slate-600">
                        <span class="font-bold text-amber-600">{{ str_pad((string) $completedCount, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-slate-400">{{ __('student.dashboard.certificates') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons (No Theme Button) -->
        <div class="flex items-center gap-2.5 w-full lg:w-auto shrink-0 justify-start lg:justify-end">
            <a href="{{ route('student.profile') }}"
               class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold px-3.5 py-2 rounded-[6px] transition-colors shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>{{ __('student.profile.title') }}</span>
            </a>
            <a href="{{ route('courses.index') }}"
               class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold px-4 py-2 rounded-[6px] transition-colors shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>{{ __('student.nav.browse') }}</span>
            </a>
        </div>
    </div>
</div>
