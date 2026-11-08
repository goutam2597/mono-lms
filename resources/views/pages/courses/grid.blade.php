<x-layouts.app :title="__('courses.title').' - Mono LMS'" active="courses">

    <!-- Page Title Header -->
    <div class="bg-[#f4f6f9] py-10 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#191919]">{{ __('courses.title') }}</h1>
                <nav class="flex items-center gap-2 text-xs text-[#6d6d6d] mt-2">
                    <a href="{{ route('home') }}" class="hover:text-[#344c3d]">Home</a>
                    <span>/</span>
                    <a href="{{ route('courses.index') }}" class="hover:text-[#344c3d]">Courses</a>
                    <span>/</span>
                    <span class="text-[#738a6e] font-medium">{{ __('courses.title') }}</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <!-- Results & Sort Bar -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 mb-8 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 shadow-xs">
            <!-- Results Count -->
            <div class="text-xs sm:text-sm text-[#6d6d6d]">
                {{ __('courses.results_for', ['count' => $courses->total()]) }}
            </div>

            <!-- Right Controls: View Switcher, Sort Dropdown, Search Input -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Grid / List Switcher -->
                
                <form method="GET" action="{{ route('courses.index') }}" class="flex flex-wrap items-center gap-3">
                    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                    @if(request('level'))<input type="hidden" name="level" value="{{ request('level') }}">@endif
                    <x-select name="sort"
                        :options="['popular' => __('courses.sort_popular'), 'latest' => __('courses.sort_latest'), 'price_low' => __('courses.sort_price_low'), 'price_high' => __('courses.sort_price_high')]"
                        :selected="request('sort', 'popular')"
                        :searchable="false"
                        data-ms-autosubmit
                        triggerClass="bg-white border border-[#e7e7e7] text-xs font-medium text-[#191919] px-3 py-2 rounded-lg" />
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('app.common.search') }}..." class="bg-white border border-[#e7e7e7] text-xs text-[#191919] px-3 py-2 rounded-lg focus:outline-none w-48 sm:w-56 pr-8">
                        <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- Layout Grid: Filter Sidebar + Courses Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Filter Sidebar -->
            <aside class="lg:col-span-3 space-y-6">
                <!-- Categories Filter -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                        <h4 class="font-bold text-sm text-[#191919]">Categories</h4>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <form method="GET" action="{{ route('courses.index') }}">
                        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                        <div class="space-y-2.5 text-xs text-[#6d6d6d]">
                            <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="category" value="" @checked(!request('category')) class="control" onchange="this.form.requestSubmit()">
                                    <span>{{ __('app.common.all') }}</span>
                                </div>
                            </label>
                            @foreach ($categories as $category)
                                <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="category" value="{{ $category->slug }}" @checked(request('category') === $category->slug) class="control" onchange="this.form.requestSubmit()">
                                        <span>{{ $category->name }}</span>
                                    </div>
                                    <span class="text-gray-400">({{ $category->courses_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </form>
                    <button class="mt-3 text-xs font-semibold text-[#738a6e] hover:underline">See More Categories</button>
                </div>

                <!-- Instructors Filter -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                        <h4 class="font-bold text-sm text-[#191919]">Instructors</h4>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="space-y-2.5 text-xs text-[#6d6d6d]">
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" checked class="control">
                                <span>Keny White</span>
                            </div>
                            <span class="text-gray-400">(10)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="control">
                                <span>Hinata Hyuga</span>
                            </div>
                            <span class="text-gray-400">(5)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="control">
                                <span>John Doe</span>
                            </div>
                            <span class="text-gray-400">(3)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="control">
                                <span>Nicole Brown</span>
                            </div>
                            <span class="text-gray-400">(4)</span>
                        </label>
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                        <h4 class="font-bold text-sm text-[#191919]">Price</h4>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="space-y-2.5 text-xs text-[#6d6d6d]">
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="price_type" checked class="control">
                                <span>All Courses</span>
                            </div>
                            <span class="text-gray-400">(50)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="price_type" class="control">
                                <span>Free Courses</span>
                            </div>
                            <span class="text-gray-400">(15)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="price_type" class="control">
                                <span>Paid Courses</span>
                            </div>
                            <span class="text-gray-400">(35)</span>
                        </label>
                    </div>
                </div>

                <!-- Level Filter -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                        <h4 class="font-bold text-sm text-[#191919]">{{ __('courses.filter_level') }}</h4>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <form method="GET" action="{{ route('courses.index') }}">
                        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        <div class="space-y-2.5 text-xs text-[#6d6d6d]">
                            <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="level" value="" @checked(!request('level')) class="control" onchange="this.form.requestSubmit()">
                                    <span>{{ __('app.common.all') }}</span>
                                </div>
                            </label>
                            @foreach (['beginner' => __('app.levels.beginner'), 'intermediate' => __('app.levels.intermediate'), 'advanced' => __('app.levels.advanced')] as $levelValue => $levelLabel)
                                <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="level" value="{{ $levelValue }}" @checked(request('level') === $levelValue) class="control" onchange="this.form.requestSubmit()">
                                        <span>{{ $levelLabel }}</span>
                                    </div>
                                    <span class="text-gray-400">({{ $levelCounts[$levelValue] ?? 0 }})</span>
                                </label>
                            @endforeach
                        </div>
                    </form>
                </div>

            </aside>

            <!-- Course Cards Grid -->
            <main class="lg:col-span-9 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
@forelse ($courses as $course)
                    <x-course-card
                        :slug="$course->slug"
                        :title="$course->title"
                        :category="$course->category?->name ?? ''"
                        :instructor="$course->instructor?->name ?? ''"
                        :instructorAvatar="$course->instructor?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor?->name ?? 'I').'&background=344C3D&color=DCE7DA'"
                        :image="$course->image"
                        :rating="$course->averageRating() ?: '5.0'"
                        :reviews="$course->reviewCount()"
                        :price="$course->price > 0 ? __('app.common.currency').number_format($course->price, 2) : __('courses.price_free')"
                        :oldPrice="$course->old_price ? __('app.common.currency').number_format($course->old_price, 2) : null"
                        :lessons="$course->lessons->count()"
                        :duration="$course->duration_minutes"
                        :level="$course->level"
                    />
                @empty
                    <div class="col-span-full bg-white border border-[#e7e7e7] rounded-2xl p-12 text-center">
                        <p class="text-sm text-gray-400">{{ __('courses.no_courses') }}</p>
                    </div>
                @endforelse
                </div>

                <!-- Pagination -->
                <div class="w-full pt-6 border-t border-gray-200">
                    {{ $courses->appends(request()->query())->links() }}
                </div>
            </main>

        </div>
    </div>

</x-layouts.app>
