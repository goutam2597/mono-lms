@props(['panel' => 'store', 'showName' => false])

@php
    $routeNames = [
        'store' => 'locale.switch',
        'admin' => 'admin.locale.switch',
        'instructor' => 'instructor.locale.switch',
        'student' => 'student.locale.switch',
    ];
    $routeName = $routeNames[$panel] ?? 'locale.switch';
    $current = $activeLanguages->firstWhere('code', $currentLocale);
@endphp

<div class="relative group" {{ $attributes }}>
    <button type="button" class="flex items-center gap-1.5 py-2 hover:text-[#738a6e] transition-colors cursor-pointer">
        @if ($current?->flag)
            <img src="{{ asset('images/flags/'.$current->flag.'.png') }}" alt="{{ $current->name }} flag" class="w-4 h-3 inline-block rounded-xs object-cover" />
        @endif
        <span>{{ $showName ? ($current?->native_name ?? strtoupper($currentLocale)) : strtoupper($currentLocale) }}</span>
        <svg class="w-3 h-3 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div class="absolute {{ $panel === 'store' ? 'left-0' : 'right-0' }} top-full pt-2 w-44 hidden group-hover:block z-[60]">
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 py-2">
            @foreach ($activeLanguages as $lang)
                <a href="{{ route($routeName, $lang->code) }}"
                   class="flex items-center justify-between gap-2 px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d] {{ $lang->code === $currentLocale ? 'font-semibold text-[#344c3d]' : 'text-[#191919]' }}">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('images/flags/'.$lang->flag.'.png') }}" alt="{{ $lang->name }} flag" class="w-4 h-3 inline-block rounded-xs object-cover" />
                        <span>{{ $lang->native_name }}</span>
                    </span>
                    <span class="text-[10px] text-gray-400 uppercase">{{ $lang->code }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
