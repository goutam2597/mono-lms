@php
    // Shared storefront error page. Code views pass $code; strings fall back
    // to the generic 4xx/5xx copy when the exact code has no translation.
    $strings = __("errors.codes.{$code}");
    if (! is_array($strings)) {
        $strings = __('errors.fallback');
    }
    $title = $title ?? $strings['title'];
    $description = $description ?? $strings['description'];
    $showSecondary = $showSecondary ?? true;
@endphp

<x-layouts.app :title="$title.' - Mono LMS'">
    <section class="bg-[#f4f6f9] border-b border-[#e7e7e7]">
        <div class="max-w-3xl mx-auto px-4 sm:px-8 py-16 sm:py-24 text-center">
            <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#344c3d]/10 text-[#344c3d] shadow-xs">
                @switch($code)
                    @case('403')
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                        @break
                    @case('404')
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                        @break
                    @case('405')
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        @break
                    @case('419')
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        @break
                    @case('429')
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        @break
                    @case('503')
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 5.25v-1.5A1.5 1.5 0 0 1 3.75 2.25h1.5A1.5 1.5 0 0 1 6.75 3.75v1.5l6.074 6.075"/></svg>
                        @break
                    @default
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                @endswitch
            </span>

            <p class="mt-8 text-7xl sm:text-8xl font-extrabold tracking-tight text-[#344c3d]">{{ $code }}</p>
            <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold text-[#191919]">{{ $title }}</h1>
            <p class="mt-3 text-sm sm:text-base text-[#6d6d6d] max-w-xl mx-auto">{{ $description }}</p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-xl bg-[#344c3d] hover:bg-[#25372c] text-white text-base font-semibold shadow-md transition-all">
                    {{ __('errors.back_home') }}
                </a>
                @if ($showSecondary)
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-xl bg-white border border-[#344c3d]/30 text-[#344c3d] hover:border-[#344c3d] hover:bg-[#344c3d]/5 text-base font-semibold transition-all">
                        {{ __('errors.browse_courses') }}
                    </a>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
