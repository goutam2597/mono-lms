@props(['active' => 'dashboard'])

@php
    $menu = [
        [
            'id' => 'dashboard',
            'label' => __('instructor.nav.dashboard'),
            'route' => 'instructor.dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />',
        ],
        [
            'id' => 'courses',
            'label' => __('instructor.nav.courses'),
            'route' => 'instructor.courses.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />',
        ],
        [
            'id' => 'students',
            'label' => __('instructor.nav.students'),
            'route' => 'instructor.students',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />',
        ],
        [
            'id' => 'reviews',
            'label' => __('instructor.nav.reviews'),
            'route' => 'instructor.reviews',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />',
        ],
        [
            'id' => 'profile',
            'label' => __('instructor.nav.profile'),
            'route' => 'instructor.profile',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.964 0m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />',
        ],
        [
            'id' => 'verification',
            'label' => 'Verification',
            'route' => 'instructor.verification.show',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />',
        ],
        [
            'id' => 'tickets',
            'label' => __('instructor.nav.tickets') ?? 'Help & Support',
            'route' => 'instructor.tickets.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />',
        ],
    ];
@endphp

<div class="space-y-1 text-[13px]">
    <div class="px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400 select-none">
        <span>Instructor Menu</span>
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
            <span class="truncate flex-1">{{ $item['label'] }}</span>

            @if($isActive)
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
            @endif
        </a>
    @endforeach
</div>
