<x-layouts.app :title="($page?->meta_title ?: ($page?->title ?: __('instructors.title'))).' - Mono LMS'" active="instructors">

    <!-- Page Title Header -->
    <div class="bg-[#f4f6f9] py-12 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            @if ($page?->setting('eyebrow'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--theme-primary,#344c3d)]"></span>
                    {{ $page->setting('eyebrow') }}
                </span>
            @endif
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#191919] tracking-tight">{{ $page?->title ?: __('instructors.title') }}</h1>
            @if ($page?->setting('subtitle'))
                <p class="text-sm sm:text-base text-slate-600 dark:text-neutral-400 mt-2 max-w-2xl leading-relaxed">
                    {{ $page->setting('subtitle') }}
                </p>
            @endif
            <nav class="flex items-center gap-2 text-xs text-[#6d6d6d] mt-4">
                <a href="{{ route('home') }}" class="hover:text-[#344c3d]">{{ __('app.nav.home') }}</a>
                <span>/</span>
                <span class="text-[#738a6e] font-medium">{{ $page?->title ?: __('instructors.title') }}</span>
            </nav>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <!-- Results, Sort & Search Bar -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 mb-8 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 shadow-xs">
            <div class="text-xs sm:text-sm text-[#6d6d6d]">
                {{ __('instructors.results_for', ['count' => $instructors->total()]) }}
            </div>

            <form method="GET" action="{{ route('instructors.index') }}" class="flex flex-wrap items-center gap-3">
                <x-select name="sort"
                    :options="['name' => __('instructors.sort_name'), 'courses' => __('instructors.sort_courses'), 'latest' => __('instructors.sort_latest')]"
                    :selected="request('sort', 'name')"
                    :searchable="false"
                    data-ms-autosubmit
                    triggerClass="bg-white border border-[#e7e7e7] text-xs font-medium text-[#191919] px-3 py-2 rounded-lg" />
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('app.common.search') }}..." class="bg-white border border-[#e7e7e7] text-xs text-[#191919] px-3 py-2 rounded-lg focus:outline-none w-48 sm:w-56 pr-8">
                    <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </form>
        </div>

        <!-- Instructors Grid (DB-driven) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($instructors as $instructor)
                <x-instructor-card
                    :name="$instructor->name"
                    :role="$instructor->designation ?? ''"
                    :image="$instructor->avatarUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&background=392c7d&color=fff&size=400'"
                    :rating="$instructor->courses_reviews_avg_rating ? number_format($instructor->courses_reviews_avg_rating, 1) : null"
                    :reviews="$instructor->courses_reviews_count ?: null"
                    :lessons="__('instructors.courses_count', ['count' => $instructor->courses_count])"
                    :duration="$instructor->enrollments_count.' '.__('instructors.students')"
                />
            @empty
                <p class="col-span-full py-10 text-center text-gray-400">{{ __('instructors.no_instructors') }}</p>
            @endforelse
        </div>

        <div class="mt-10">{{ $instructors->appends(request()->query())->links() }}</div>

        @if ($page?->setting('cta_banner_enabled', true))
            <!-- Become Instructor CTA Card -->
            <div class="mt-16 bg-[#08131e] rounded-2xl p-8 sm:p-10 text-white relative overflow-hidden shadow-xl border border-slate-800">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-2xl">
                        <span class="text-xs uppercase tracking-wider font-bold text-[#bfcfbb]">Teach with Us</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold mt-1">
                            {{ $page?->setting('cta_heading') ?: 'Are you passionate about teaching? Share your mastery.' }}
                        </h2>
                        <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                            {{ $page?->setting('cta_subtitle') ?: 'Join our faculty of world-class instructors. Create interactive courses, mentor ambitious students, and earn recurring revenue.' }}
                        </p>
                    </div>
                    <div class="shrink-0">
                        <a href="{{ $page?->setting('cta_button_url') ?: url('/instructor/register') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider bg-[#344c3d] hover:bg-[#25372c] text-white shadow-lg transition-transform hover:scale-105">
                            <span>{{ $page?->setting('cta_button_text') ?: 'Become an Instructor' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

</x-layouts.app>
