<x-layouts.app title="Course Grid - Dreams LMS" active="courses">

    <!-- Page Title Header (Figma: 398:51277) -->
    <div class="bg-[#f4f6f9] py-10 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#191919]">Course Grid</h1>
                <nav class="flex items-center gap-2 text-xs text-[#6d6d6d] mt-2">
                    <a href="{{ route('home') }}" class="hover:text-[#ff4667]">Home</a>
                    <span>/</span>
                    <a href="{{ route('courses.grid') }}" class="hover:text-[#ff4667]">Courses</a>
                    <span>/</span>
                    <span class="text-[#ff4667] font-medium">Course Grid</span>
                </nav>
            </div>
            <div class="text-xs text-[#888888] font-medium">
                Figma Node: <code class="bg-white px-2 py-1 rounded border border-gray-200 font-mono text-[#191919]">398:51274</code>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <!-- Results & Sort Bar (Figma: 398:51279) -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 mb-8 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 shadow-xs">
            <!-- Left Filter Toggle & Results Count -->
            <div class="flex items-center justify-between lg:justify-start gap-6">
                <button class="inline-flex items-center gap-2 text-sm font-semibold text-[#191919] bg-[#f4f6f9] px-4 py-2 rounded-lg">
                    <svg class="w-4 h-4 text-[#ff4667]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filters</span>
                </button>
                <div class="text-xs sm:text-sm text-[#6d6d6d]">
                    Showing <strong class="text-[#191919]">1–9</strong> of <strong class="text-[#191919]">50</strong> results
                </div>
            </div>

            <!-- Right Controls: View Switcher, Sort Dropdown, Search Input -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Grid / List Switcher -->
                <div class="flex items-center bg-[#f4f6f9] p-1 rounded-lg border border-gray-200">
                    <a href="{{ route('courses.grid') }}" class="p-1.5 rounded bg-white text-[#ff4667] shadow-xs" title="Grid View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </a>
                    <a href="{{ route('courses.list') }}" class="p-1.5 rounded text-gray-500 hover:text-[#191919]" title="List View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </a>
                </div>

                <!-- Sort Dropdown -->
                <div class="relative">
                    <select class="bg-white border border-[#e7e7e7] text-xs font-medium text-[#191919] px-3 py-2 rounded-lg focus:outline-none cursor-pointer pr-8">
                        <option>Newly Published</option>
                        <option>Highest Rated</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <input type="text" placeholder="Search courses..." class="bg-white border border-[#e7e7e7] text-xs text-[#191919] px-3 py-2 rounded-lg focus:outline-none w-48 sm:w-56 pr-8">
                    <svg class="w-4 h-4 text-gray-400 absolute right-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Layout Grid: Filter Sidebar + Courses Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Filter Sidebar (Figma: 398:51305) -->
            <aside class="lg:col-span-3 space-y-6">
                <!-- Categories Filter -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                        <h4 class="font-bold text-sm text-[#191919]">Categories</h4>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="space-y-2.5 text-xs text-[#6d6d6d]">
                        @php
                            $cats = [
                                ['label' => 'Backend', 'count' => 3, 'checked' => true],
                                ['label' => 'CSS & Styling', 'count' => 2, 'checked' => false],
                                ['label' => 'Frontend', 'count' => 2, 'checked' => true],
                                ['label' => 'General Skills', 'count' => 2, 'checked' => false],
                                ['label' => 'IT & Software', 'count' => 2, 'checked' => false],
                                ['label' => 'Photography', 'count' => 2, 'checked' => false],
                                ['label' => 'Programming', 'count' => 3, 'checked' => true],
                                ['label' => 'Technology', 'count' => 2, 'checked' => false],
                            ];
                        @endphp
                        @foreach($cats as $cat)
                            <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" {{ $cat['checked'] ? 'checked' : '' }} class="w-4 h-4 text-[#ff4667] rounded border-gray-300 focus:ring-[#ff4667]">
                                    <span>{{ $cat['label'] }}</span>
                                </div>
                                <span class="text-gray-400">({{ $cat['count'] }})</span>
                            </label>
                        @endforeach
                    </div>
                    <button class="mt-3 text-xs font-semibold text-[#ff4667] hover:underline">See More Categories</button>
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
                                <input type="checkbox" checked class="w-4 h-4 text-[#ff4667] rounded border-gray-300 focus:ring-[#ff4667]">
                                <span>Keny White</span>
                            </div>
                            <span class="text-gray-400">(10)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="w-4 h-4 text-[#ff4667] rounded border-gray-300 focus:ring-[#ff4667]">
                                <span>Hinata Hyuga</span>
                            </div>
                            <span class="text-gray-400">(5)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="w-4 h-4 text-[#ff4667] rounded border-gray-300 focus:ring-[#ff4667]">
                                <span>John Doe</span>
                            </div>
                            <span class="text-gray-400">(3)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="w-4 h-4 text-[#ff4667] rounded border-gray-300 focus:ring-[#ff4667]">
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
                                <input type="radio" name="price_type" checked class="w-4 h-4 text-[#ff4667] focus:ring-[#ff4667]">
                                <span>All Courses</span>
                            </div>
                            <span class="text-gray-400">(50)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="price_type" class="w-4 h-4 text-[#ff4667] focus:ring-[#ff4667]">
                                <span>Free Courses</span>
                            </div>
                            <span class="text-gray-400">(15)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="price_type" class="w-4 h-4 text-[#ff4667] focus:ring-[#ff4667]">
                                <span>Paid Courses</span>
                            </div>
                            <span class="text-gray-400">(35)</span>
                        </label>
                    </div>
                </div>

                <!-- Level Filter -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                        <h4 class="font-bold text-sm text-[#191919]">Difficulty Level</h4>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="space-y-2.5 text-xs text-[#6d6d6d]">
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" checked class="w-4 h-4 text-[#ff4667] rounded border-gray-300">
                                <span>Beginner</span>
                            </div>
                            <span class="text-gray-400">(10)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" checked class="w-4 h-4 text-[#ff4667] rounded border-gray-300">
                                <span>Intermediate</span>
                            </div>
                            <span class="text-gray-400">(25)</span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer hover:text-[#191919]">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="w-4 h-4 text-[#ff4667] rounded border-gray-300">
                                <span>Advanced</span>
                            </div>
                            <span class="text-gray-400">(15)</span>
                        </label>
                    </div>
                </div>

            </aside>

            <!-- Course Cards Grid (Figma: 398:51409) -->
            <main class="lg:col-span-9 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <x-course-card 
                        title="Information About UI/UX Design Degree"
                        category="Design"
                        instructor="David Benitez"
                        price="$120"
                        rating="4.9"
                        reviews="200"
                        image="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80"
                    />

                    <x-course-card 
                        title="Complete Python Bootcamp From Zero to Hero"
                        category="Programming"
                        instructor="Rolands Granger"
                        price="$150"
                        oldPrice="$220"
                        rating="4.8"
                        reviews="340"
                        image="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=600&auto=format&fit=crop&q=80"
                    />

                    <x-course-card 
                        title="The Complete Business and Management Masterclass"
                        category="Business"
                        instructor="Nicole Brown"
                        price="$168"
                        rating="5.0"
                        reviews="210"
                        image="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&auto=format&fit=crop&q=80"
                    />

                    <x-course-card 
                        title="Modern Web Development with React 19 & Tailwind"
                        category="Frontend"
                        instructor="Keny White"
                        price="$135"
                        rating="4.9"
                        reviews="185"
                        image="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80"
                    />

                    <x-course-card 
                        title="Machine Learning & Deep Learning A-Z with Python"
                        category="Data Science"
                        instructor="Hinata Hyuga"
                        price="$180"
                        oldPrice="$250"
                        rating="4.9"
                        reviews="410"
                        image="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80"
                    />

                    <x-course-card 
                        title="Graphic Design Masterclass: Photoshop & Illustrator"
                        category="Design"
                        instructor="John Doe"
                        price="$110"
                        rating="4.7"
                        reviews="128"
                        image="https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&auto=format&fit=crop&q=80"
                    />

                    <x-course-card 
                        title="Laravel 12 API Development and Testing Masterclass"
                        category="Backend"
                        instructor="Rolands Granger"
                        price="$145"
                        rating="4.9"
                        reviews="95"
                        image="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&auto=format&fit=crop&q=80"
                    />

                    <x-course-card 
                        title="Advanced DevOps: Kubernetes, Docker and CI/CD"
                        category="DevOps"
                        instructor="Keny White"
                        price="$165"
                        rating="4.8"
                        reviews="212"
                        image="https://images.unsplash.com/photo-1607799279861-4dd421887fb3?w=600&auto=format&fit=crop&q=80"
                    />

                    <x-course-card 
                        title="Digital Marketing Mastery: SEO, Social Media & Ads"
                        category="Marketing"
                        instructor="Nicole Brown"
                        price="$125"
                        rating="4.9"
                        reviews="170"
                        image="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&auto=format&fit=crop&q=80"
                    />
                </div>

                <!-- Pagination (Figma: 398:51422) -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                    <span class="text-xs text-[#6d6d6d]">Showing 1 to 9 of 50 results</span>
                    <div class="flex items-center gap-1.5 text-xs font-semibold">
                        <button class="px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-600 disabled:opacity-50" disabled>Previous</button>
                        <button class="w-8 h-8 rounded-lg bg-[#ff4667] text-white">1</button>
                        <button class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700">2</button>
                        <button class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700">3</button>
                        <span class="px-1 text-gray-400">...</span>
                        <button class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700">6</button>
                        <button class="px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-600">Next</button>
                    </div>
                </div>
            </main>

        </div>
    </div>

</x-layouts.app>
