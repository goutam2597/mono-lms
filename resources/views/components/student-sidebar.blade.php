@props(['active' => 'dashboard'])

@php
    $student = auth('student')->user();
    $enrolledCount = $student ? $student->enrollments()->count() : 0;
    $completedCount = $student ? $student->enrollments()->where('status', 'completed')->count() : 0;
    $savedCount = $student ? $student->wishlists()->count() : 0;

    $menu = [
        [
            'id' => 'dashboard',
            'label' => __('student.nav.dashboard'),
            'route' => 'student.dashboard',
            'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
            'badge' => null
        ],
        [
            'id' => 'enrolled',
            'label' => __('student.nav.enrolled'),
            'route' => 'student.enrolled-courses',
            'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'badge' => $enrolledCount > 0 ? (string)$enrolledCount : null
        ],
        [
            'id' => 'saved-courses',
            'label' => __('student.nav.saved_courses'),
            'route' => 'student.wishlist',
            'icon' => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z',
            'badge' => $savedCount > 0 ? (string)$savedCount : null,
            'isSavedCourses' => true,
        ],
        [
            'id' => 'certificates',
            'label' => __('student.nav.certificates'),
            'route' => 'student.certificates',
            'icon' => 'M12 14a6 6 0 100-12 6 6 0 000 12zm-3-6l2 2 4-4m2.5 7.5L17 22l-5-3-5 3 1.5-8.5',
            'badge' => $completedCount > 0 ? (string)$completedCount : null
        ],
        [
            'id' => 'quiz',
            'label' => __('student.nav.quiz'),
            'route' => 'student.quiz-attempts',
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
            'badge' => null
        ],
        [
            'id' => 'browse',
            'label' => __('student.nav.browse'),
            'route' => 'courses.index',
            'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
            'badge' => null
        ],
        [
            'id' => 'profile',
            'label' => __('student.nav.profile'),
            'route' => 'student.profile',
            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'badge' => null
        ],
        [
            'id' => 'tickets',
            'label' => __('student.nav.tickets') ?? 'Help & Support',
            'route' => 'student.tickets.index',
            'icon' => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z',
            'badge' => null,
        ],
    ];
@endphp

<div class="bg-white border border-[#e2e8f0] rounded-[10px] p-2.5 shadow-xs">
    <div class="px-3 pt-2 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
        {{ __('student.panel') ?? 'Learning Menu' }}
    </div>

    <nav class="space-y-1">
        @foreach($menu as $item)
            @php $isActive = ($active === $item['id']); @endphp
            <a href="{{ route($item['route']) }}"
               class="group flex items-center justify-between px-3 py-2.5 rounded-[6px] text-xs font-semibold transition-all {{ $isActive ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="flex items-center gap-2.5 min-w-0">
                    <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    <span class="truncate">{{ $item['label'] }}</span>
                </span>
                @if ($item['badge'] !== null || !empty($item['isSavedCourses']))
                    <span @if(!empty($item['isSavedCourses'])) data-wishlist-count @endif class="text-[10px] font-bold px-1.5 py-0.5 rounded-[4px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-slate-200' }} {{ (!empty($item['isSavedCourses']) && $savedCount === 0) ? 'hidden' : '' }}">
                        {{ $item['badge'] }}
                    </span>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="border-t border-slate-100 my-2"></div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-[6px] text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors cursor-pointer">
            <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span>{{ __('student.nav.logout') }}</span>
        </button>
    </form>
</div>
