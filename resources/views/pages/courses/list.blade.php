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
                Showing <strong class="text-[#191919]">1–6</strong> of <strong class="text-[#191919]">42</strong> results
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
                <select class="bg-white border border-[#e7e7e7] text-xs font-medium text-[#191919] px-3 py-2 rounded-lg focus:outline-none cursor-pointer">
                    <option>Newly Published</option>
                    <option>Highest Rated</option>
                </select>
            </div>
        </div>

        <!-- Horizontal Cards List -->
        <div class="space-y-6">
            @php
                $listCourses = [
                    [
                        'title' => 'Information About UI/UX Design Degree & Career Opportunities',
                        'desc' => 'Comprehensive masterclass covering design thinking, user research, wireframing, high-fidelity Figma prototyping, and design systems.',
                        'instructor' => 'David Benitez',
                        'category' => 'UI/UX Design',
                        'rating' => '4.9',
                        'reviews' => '200',
                        'lessons' => '16 Lessons',
                        'duration' => '12hr 40min',
                        'price' => '$120',
                        'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80'
                    ],
                    [
                        'title' => 'Complete Python Bootcamp From Zero to Hero in 30 Days',
                        'desc' => 'Master Python 3 from variables and data structures to object-oriented programming, API development, web scraping, and automation.',
                        'instructor' => 'Rolands Granger',
                        'category' => 'Python',
                        'rating' => '4.8',
                        'reviews' => '340',
                        'lessons' => '24 Lessons',
                        'duration' => '22hr 15min',
                        'price' => '$150',
                        'image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=600&auto=format&fit=crop&q=80'
                    ],
                    [
                        'title' => 'The Complete Business, Leadership and Management Masterclass',
                        'desc' => 'Strategic leadership tactics, financial management, team leadership, crisis handling, and agile execution for modern managers.',
                        'instructor' => 'Nicole Brown',
                        'category' => 'Business',
                        'rating' => '5.0',
                        'reviews' => '210',
                        'lessons' => '18 Lessons',
                        'duration' => '14hr 30min',
                        'price' => '$168',
                        'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&auto=format&fit=crop&q=80'
                    ],
                ];
            @endphp

            @foreach($listCourses as $c)
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs hover:shadow-md transition-shadow flex flex-col md:flex-row gap-6 items-start md:items-center">
                    <div class="w-full md:w-64 h-48 rounded-lg overflow-hidden shrink-0 bg-gray-100">
                        <img src="{{ $c['image'] }}" alt="{{ $c['title'] }}" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 space-y-2">
                        <div class="flex items-center gap-3">
                            <span class="bg-[#f4f6f9] text-[#191919] text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $c['category'] }}</span>
                            <div class="flex items-center gap-1 text-xs text-[#6d6d6d]">
                                <span class="text-[#ffc107]">★</span>
                                <span class="font-bold text-gray-800">{{ $c['rating'] }}</span>
                                <span>({{ $c['reviews'] }} Reviews)</span>
                            </div>
                        </div>

                        <a href="{{ route('courses.detail') }}" class="block text-xl font-bold text-[#191919] hover:text-[#ff4667] transition-colors">
                            {{ $c['title'] }}
                        </a>

                        <p class="text-sm text-[#6d6d6d] line-clamp-2">{{ $c['desc'] }}</p>

                        <div class="flex flex-wrap items-center gap-6 pt-2 text-xs text-[#6d6d6d]">
                            <div class="flex items-center gap-1.5">
                                <span>Instructor: <strong class="text-gray-800">{{ $c['instructor'] }}</strong></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span>{{ $c['lessons'] }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span>{{ $c['duration'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex md:flex-col items-center md:items-end justify-between gap-4 pt-4 md:pt-0 border-t md:border-t-0 border-gray-100">
                        <div class="text-2xl font-bold text-[#ff4667]">{{ $c['price'] }}</div>
                        <a href="{{ route('courses.detail') }}" class="bg-[#08131e] hover:bg-[#ff4667] text-white text-xs font-semibold px-5 py-2.5 rounded-full transition-colors">
                            View Course →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-layouts.app>
