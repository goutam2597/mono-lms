<x-layouts.app title="Course List - Dreams LMS" active="courses">

    <!-- Page Title Header -->
    <div class="bg-[#f4f6f9] py-10 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#191919]">Course List</h1>
                <nav class="flex items-center gap-2 text-xs text-[#6d6d6d] mt-2">
                    <a href="{{ route('home') }}" class="hover:text-[#ff4667]">Home</a>
                    <span>/</span>
                    <a href="{{ route('courses.grid') }}" class="hover:text-[#ff4667]">Courses</a>
                    <span>/</span>
                    <span class="text-[#ff4667] font-medium">Course List</span>
                </nav>
            </div>
            <div class="text-xs text-[#888888] font-medium">
                Figma Node: <code class="bg-white px-2 py-1 rounded border border-gray-200 font-mono text-[#191919]">398:51425</code>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <!-- Results Bar -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 mb-8 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 shadow-xs">
            <div class="text-xs sm:text-sm text-[#6d6d6d]">
                {{ __('courses.results_for', ['count' => $courses->total()]) }}
            </div>

            <!-- Switcher & Sort -->
            <div class="flex items-center gap-3">
                <div class="flex items-center bg-[#f4f6f9] p-1 rounded-lg border border-gray-200">
                    <a href="{{ route('courses.grid') }}" class="p-1.5 rounded text-gray-500 hover:text-[#191919]" title="Grid View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </a>
                    <a href="{{ route('courses.list') }}" class="p-1.5 rounded bg-white text-[#ff4667] shadow-xs" title="List View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </a>
                </div>
                <form method="GET" action="{{ route('courses.list') }}" class="flex items-center gap-3">
                    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                    <x-select name="sort"
                        :options="['popular' => __('courses.sort_popular'), 'latest' => __('courses.sort_latest')]"
                        :selected="request('sort', 'popular')"
                        :searchable="false"
                        data-ms-autosubmit
                        triggerClass="bg-white border border-[#e7e7e7] text-xs font-medium text-[#191919] px-3 py-2 rounded-lg" />
                </form>
            </div>
        </div>

        <!-- Horizontal Cards List -->
        <div class="space-y-6">

            @forelse ($courses as $c)
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs hover:shadow-md transition-shadow flex flex-col md:flex-row gap-6 items-start md:items-center">
                    <div class="w-full md:w-64 h-48 rounded-lg overflow-hidden shrink-0 bg-gray-100">
                        <img src="{{ $c->image ?? 'https://picsum.photos/seed/course-'.$c->id.'/600/400' }}" alt="{{ $c->title }}" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 space-y-2">
                        <div class="flex items-center gap-3">
                            <span class="bg-[#f4f6f9] text-[#191919] text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $c->category?->name }}</span>
                            <div class="flex items-center gap-1 text-xs text-[#6d6d6d]">
                                <span class="text-[#ffc107]">★</span>
                                <span class="font-bold text-gray-800">{{ $c->averageRating() ?: '5.0' }}</span>
                                <span>({{ $c->reviewCount() }} {{ __('app.common.reviews') }})</span>
                            </div>
                        </div>

                        <a href="{{ route('courses.show', $c->slug) }}" class="block text-xl font-bold text-[#191919] hover:text-[#ff4667] transition-colors">
                            {{ $c->title }}
                        </a>

                        <p class="text-sm text-[#6d6d6d] line-clamp-2">{{ $c->subtitle }}</p>

                        <div class="flex flex-wrap items-center gap-6 pt-2 text-xs text-[#6d6d6d]">
                            <div class="flex items-center gap-1.5">
                                <span>Instructor: <strong class="text-gray-800">{{ $c->instructor?->name }}</strong></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span>{{ $c->lessons->count() }} {{ __('app.common.lessons') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span>{{ $c->duration_minutes }} {{ __('app.common.minutes') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex md:flex-col items-center md:items-end justify-between gap-4 pt-4 md:pt-0 border-t md:border-t-0 border-gray-100">
                        <div class="text-2xl font-bold text-[#ff4667]">{{ $c->price > 0 ? __('app.common.currency').number_format($c->price, 2) : __('courses.price_free') }}</div>
                        <a href="{{ route('courses.show', $c->slug) }}" class="bg-[#08131e] hover:bg-[#ff4667] text-white text-xs font-semibold px-5 py-2.5 rounded-full transition-colors">
                            {{ __('courses.enroll_now') }} →
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-12 text-center">
                    <p class="text-sm text-gray-400">{{ __('courses.no_courses') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">{{ $courses->appends(request()->query())->links() }}</div>
        </div>
    </div>

</x-layouts.app>
