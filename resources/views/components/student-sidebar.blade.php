@props(['active' => 'dashboard'])

@php
    $menu = [
        ['id' => 'dashboard', 'label' => __('student.nav.dashboard'), 'route' => 'student.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
        ['id' => 'enrolled', 'label' => __('student.nav.enrolled'), 'route' => 'student.enrolled-courses', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ['id' => 'certificates', 'label' => __('student.nav.certificates'), 'route' => 'student.certificates', 'icon' => 'M12 14a6 6 0 100-12 6 6 0 000 12zm-3-6l2 2 4-4m2.5 7.5L17 22l-5-3-5 3 1.5-8.5'],
        ['id' => 'quiz', 'label' => __('student.nav.quiz'), 'route' => 'student.quiz-attempts', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
        ['id' => 'browse', 'label' => __('student.nav.browse'), 'route' => 'courses.index', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
        ['id' => 'profile', 'label' => __('student.nav.profile'), 'route' => 'student.profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ];
@endphp

<div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs">
    <div class="space-y-1">
        @foreach($menu as $item)
            @php $isActive = ($active === $item['id']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ $isActive ? 'bg-[#344c3d] text-white shadow-xs' : 'text-[#5d5d5d] hover:bg-[#f4f6f9] hover:text-[#191919]' }}">
                <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-[#888888]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>

    <div class="border-t border-gray-100 my-4"></div>

    <button type="button" id="student-sidebar-theme-toggle" class="w-full flex items-center justify-between px-4 py-2.5 mb-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors border border-slate-200 cursor-pointer">
        <span class="flex items-center gap-2.5">
            <svg class="w-4 h-4 hidden dark:inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
            <svg class="w-4 h-4 inline-block dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
            <span>Theme Mode</span>
        </span>
        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
            <span class="inline dark:hidden">Light</span>
            <span class="hidden dark:inline">Dark</span>
        </span>
    </button>

    <x-language-switcher panel="student" class="[&>button]:w-full [&>button]:text-[14px]" />

    <script>
        (function () {
            var btn = document.getElementById('student-sidebar-theme-toggle');
            if (!btn) return;
            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            btn.addEventListener('click', function () {
                var isDark = document.documentElement.classList.toggle('dark');
                var nextTheme = isDark ? 'dark' : 'light';
                try { localStorage.setItem('mono_theme_student', nextTheme); } catch (e) {}
                fetch('{{ route('theme.toggle', 'student') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ theme: nextTheme })
                }).catch(function () {});
            });
        })();
    </script>

    <form method="POST" action="{{ route('logout') }}" class="mt-1">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            <span>{{ __('student.nav.logout') }}</span>
        </button>
    </form>
</div>
