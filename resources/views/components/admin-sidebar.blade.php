@props(['active' => 'dashboard', 'admin' => null])

@php
    $menu = [
        ['id' => 'dashboard', 'label' => __('admin.nav.dashboard'), 'route' => 'admin.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
        ['id' => 'admins', 'label' => __('admin.nav.admins'), 'route' => 'admin.admins.index', 'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['id' => 'roles', 'label' => __('admin.nav.roles'), 'route' => 'admin.roles.index', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
        ['id' => 'instructors', 'label' => __('admin.nav.instructors'), 'route' => 'admin.instructors.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ['id' => 'students', 'label' => __('admin.nav.students'), 'route' => 'admin.students.index', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['id' => 'categories', 'label' => __('admin.nav.categories'), 'route' => 'admin.categories.index', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['id' => 'courses', 'label' => __('admin.nav.courses'), 'route' => 'admin.courses.index', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['id' => 'reviews', 'label' => __('admin.nav.reviews'), 'route' => 'admin.reviews.index', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
        ['id' => 'languages', 'label' => __('admin.nav.languages'), 'route' => 'admin.languages.index', 'icon' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7m-3.5-3.5L18 21m-3-6l3 6m0 0l3-6m-3 6V3'],
    ];
@endphp

<div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs">
    @php
        // Only show menu items the signed-in admin (via role) may open.
        $admin ??= auth('admin')->user();
        $menu = collect($menu)->filter(function ($item) use ($admin) {
            return match ($item['id']) {
                'admins', 'roles' => $admin->isSuperAdmin(),
                'instructors' => $admin->hasPermission('instructors.manage'),
                'students' => $admin->hasPermission('students.manage'),
                'categories' => $admin->hasPermission('categories.manage'),
                'courses' => $admin->hasPermission('courses.manage'),
                'reviews' => $admin->hasPermission('reviews.moderate'),
                'languages' => $admin->hasPermission('languages.manage'),
                default => true,
            };
        })->values()->all();
    @endphp

    <div class="space-y-1">
        @foreach($menu as $item)
            @php $isActive = ($active === $item['id']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ $isActive ? 'bg-[#ff4667] text-white shadow-xs' : 'text-[#5d5d5d] hover:bg-[#f4f6f9] hover:text-[#191919]' }}">
                <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-[#888888]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>

    <div class="border-t border-gray-100 my-4"></div>

    <div class="px-4 py-2 text-xs text-gray-400">
        {{ auth('admin')->user()?->name }} · {{ auth('admin')->user()?->isSuperAdmin() ? __('admin.admins.super_admin') : (auth('admin')->user()?->role?->name ?? '—') }}
    </div>

    <x-language-switcher panel="admin" class="[&>button]:w-full [&>button]:text-[14px]" />

    <form method="POST" action="{{ route('admin.logout') }}" class="mt-1">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            <span>{{ __('app.nav.sign_out') }}</span>
        </button>
    </form>
</div>
