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
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />',
            'badge' => null,
        ],
        [
            'id' => 'enrolled',
            'label' => __('student.nav.enrolled'),
            'route' => 'student.enrolled-courses',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />',
            'badge' => $enrolledCount > 0 ? (string)$enrolledCount : null,
        ],
        [
            'id' => 'orders',
            'label' => 'My Orders',
            'route' => 'student.orders',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
            'badge' => null,
        ],
        [
            'id' => 'saved-courses',
            'label' => __('student.nav.saved_courses'),
            'route' => 'student.wishlist',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />',
            'badge' => $savedCount > 0 ? (string)$savedCount : null,
            'isSavedCourses' => true,
        ],
        [
            'id' => 'certificates',
            'label' => __('student.nav.certificates'),
            'route' => 'student.certificates',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />',
            'badge' => $completedCount > 0 ? (string)$completedCount : null,
        ],
        [
            'id' => 'quiz',
            'label' => __('student.nav.quiz'),
            'route' => 'student.quiz-attempts',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />',
            'badge' => null,
        ],
        [
            'id' => 'tickets',
            'label' => __('student.nav.tickets') ?? 'Help & Support',
            'route' => 'student.tickets.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />',
            'badge' => null,
        ],
        [
            'id' => 'profile',
            'label' => __('student.nav.profile'),
            'route' => 'student.profile',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.964 0m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />',
            'badge' => null,
        ],
        [
            'id' => 'browse',
            'label' => __('student.nav.browse'),
            'route' => 'courses.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />',
            'badge' => null,
        ],
    ];
@endphp

<div class="space-y-1 text-[13px]">
    <div class="sidebar-caption px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400 select-none">
        <span>{{ __('student.panel') ?? 'Student Menu' }}</span>
    </div>

    @foreach($menu as $item)
        @php $isActive = ($active === $item['id']); @endphp
        <a href="{{ route($item['route']) }}"
           class="group flex items-center gap-2.5 px-2.5 py-2 rounded-xl font-medium transition-all duration-150 {{ $isActive ? 'bg-[#344c3d] text-white font-semibold shadow-[0_4px_16px_rgba(52,76,61,0.45)] ring-1 ring-emerald-500/25' : 'text-slate-300 hover:text-white hover:bg-white/[0.05]' }}">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ $isActive ? 'bg-white/15 text-white' : 'bg-white/[0.04] text-slate-400 group-hover:bg-white/[0.08] group-hover:text-white' }}">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    {!! $item['icon'] !!}
                </svg>
            </div>
            <span class="sidebar-label truncate flex-1">{{ $item['label'] }}</span>

            @if (!empty($item['badge']) || !empty($item['isSavedCourses']))
                <span @if(!empty($item['isSavedCourses'])) data-wishlist-count @endif class="sidebar-badge-pill text-[10px] font-bold px-1.5 py-0.5 rounded-[4px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-white/10 text-slate-300 group-hover:bg-white/20' }} {{ (!empty($item['isSavedCourses']) && $savedCount === 0) ? 'hidden' : '' }}">
                    {{ $item['badge'] }}
                </span>
            @endif

            @if($isActive)
                <span class="sidebar-badge-pill w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
            @endif
        </a>
    @endforeach
</div>
