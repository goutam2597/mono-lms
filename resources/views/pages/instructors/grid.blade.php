<x-layouts.app :title="__('instructors.title').' - Mono LMS'" active="instructors">

    <!-- Page Title Header -->
    <div class="bg-[#f4f6f9] py-10 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <h1 class="text-3xl font-extrabold text-[#191919]">{{ __('instructors.title') }}</h1>
            <nav class="flex items-center gap-2 text-xs text-[#6d6d6d] mt-2">
                <a href="{{ route('home') }}" class="hover:text-[#344c3d]">{{ __('app.nav.home') }}</a>
                <span>/</span>
                <span class="text-[#738a6e] font-medium">{{ __('instructors.title') }}</span>
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
                    :image="$instructor->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&background=392c7d&color=fff&size=400'"
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
    </div>

</x-layouts.app>
