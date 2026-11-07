<x-layouts.app title="Dreams LMS – Online Learning Management System" active="home">

    <!-- 1. HERO SECTION (Figma: 206:12619) -->
    <section class="bg-[#392c7d] text-white py-16 lg:py-24 relative overflow-hidden">
        <!-- Background decorative vectors -->
        <div class="absolute -left-20 top-20 w-72 h-72 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute right-0 top-10 w-96 h-96 rounded-full bg-[#ff4667]/10 pointer-events-none"></div>
        <div class="absolute right-1/4 bottom-0 w-64 h-64 rounded-full bg-indigo-500/10 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-xs sm:text-sm font-medium">
                        <span class="w-2 h-2 rounded-full bg-[#ff4667]"></span>
                        <span>Start Learning Today</span>
                        <svg class="w-4 h-4 text-[#ff4667]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                        Empowering Minds, <br class="hidden sm:inline">
                        <span class="text-[#ff4667]">Shaping Futures</span>
                    </h1>

                    <p class="text-gray-300 text-base sm:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        The right course, guided by an expert mentor, can provide invaluable insights, practical skills, and personalized feedback to fast-track your career.
                    </p>

                    <!-- Search Box Form -->
                    <div class="bg-white p-2 sm:p-2.5 rounded-2xl sm:rounded-full shadow-2xl flex flex-col sm:flex-row items-stretch sm:items-center gap-2 max-w-xl mx-auto lg:mx-0">
                        <div class="flex items-center gap-2 px-4 py-2 border-b sm:border-b-0 sm:border-r border-gray-200">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            <select class="text-xs sm:text-sm font-medium text-[#191919] bg-transparent focus:outline-none cursor-pointer">
                                <option>All Categories</option>
                                <option>Development</option>
                                <option>UI/UX Design</option>
                                <option>Business</option>
                                <option>Marketing</option>
                            </select>
                        </div>
                        <div class="flex-1 flex items-center px-4 py-2">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" placeholder="Search courses, instructors, skills..." class="w-full text-xs sm:text-sm text-[#191919] placeholder-gray-400 bg-transparent focus:outline-none">
                        </div>
                        <button class="bg-[#ff4667] hover:bg-[#e03a58] text-white px-7 py-3 rounded-xl sm:rounded-full font-semibold text-xs sm:text-sm transition-all shadow-md">
                            Search
                        </button>
                    </div>

                    <!-- Hero Stats -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-8 pt-6 border-t border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-2">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&auto=format&fit=crop&q=80" class="w-8 h-8 rounded-full border-2 border-[#392c7d] object-cover">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&auto=format&fit=crop&q=80" class="w-8 h-8 rounded-full border-2 border-[#392c7d] object-cover">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=60&auto=format&fit=crop&q=80" class="w-8 h-8 rounded-full border-2 border-[#392c7d] object-cover">
                            </div>
                            <div class="text-left">
                                <div class="text-base font-bold text-white">25K+</div>
                                <div class="text-xs text-gray-300">Active Students</div>
                            </div>
                        </div>
                        <div class="text-left">
                            <div class="text-base font-bold text-white">1,200+</div>
                            <div class="text-xs text-gray-300">Online Courses</div>
                        </div>
                        <div class="text-left">
                            <div class="text-base font-bold text-white">500+</div>
                            <div class="text-xs text-gray-300">Verified Mentors</div>
                        </div>
                    </div>
                </div>

                <!-- Right Layered Cards (Exact Figma composition) -->
                <div class="lg:col-span-5 relative flex items-center justify-center">
                    <!-- Decorative back card -->
                    <div class="hidden sm:block absolute -top-4 -left-4 w-72 h-80 bg-white/10 rounded-2xl rotate-6 pointer-events-none"></div>

                    <!-- Main Floating Course Card -->
                    <div class="relative w-full max-w-sm bg-white rounded-2xl p-5 shadow-2xl text-[#191919] border border-gray-100 transform hover:-translate-y-1 transition-transform">
                        <div class="relative h-48 rounded-xl overflow-hidden mb-4 bg-gradient-to-br from-[#04a3e6] to-[#274797]">
                            <img src="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=600&auto=format&fit=crop&q=80" class="w-full h-full object-cover mix-blend-overlay opacity-80" alt="UI Design">
                            <span class="absolute top-3 left-3 bg-[#ff4667] text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow-xs">Bestseller</span>
                            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-xs text-[#191919] text-[11px] font-semibold px-2.5 py-1 rounded-full shadow-xs">Interactive</span>
                        </div>

                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&auto=format&fit=crop&q=80" class="w-6 h-6 rounded-full object-cover">
                                <span class="text-xs text-[#6d6d6d]">David Benitez</span>
                            </div>
                            <span class="bg-[#f4f6f9] text-xs font-medium px-2 py-0.5 rounded-full text-[#191919]">Productivity</span>
                        </div>

                        <h3 class="font-bold text-base text-[#191919] mb-2 leading-snug">
                            The Complete Business & Management Masterclass
                        </h3>

                        <div class="flex items-center gap-1.5 text-xs text-[#6d6d6d] mb-4">
                            <div class="flex text-[#ffc107]">★ ★ ★ ★ ★</div>
                            <span class="font-bold text-gray-800">5.0</span>
                            <span>(210 Reviews)</span>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="text-lg font-bold text-[#ff4667]">$168</div>
                            <a href="{{ route('courses.detail') }}" class="bg-[#08131e] hover:bg-[#ff4667] text-white text-xs font-semibold px-4 py-2 rounded-full transition-colors">
                                Add to Cart →
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. BENEFITS & PARTNERS (Figma: 204:27068) -->
    <section class="py-16 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <!-- 3 Benefit Items -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Benefit 1 -->
                <div class="bg-[#f4f6f9] p-8 rounded-2xl flex flex-col justify-between hover:bg-gray-100 transition-colors">
                    <div class="w-14 h-14 rounded-2xl bg-[#392c7d] text-white flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#191919] mb-2">Fast Learning</h3>
                        <p class="text-sm text-[#6d6d6d] leading-relaxed">
                            Access top video lessons, practical assignments, and interactive quizzes on your own schedule.
                        </p>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-[#f4f6f9] p-8 rounded-2xl flex flex-col justify-between hover:bg-gray-100 transition-colors">
                    <div class="w-14 h-14 rounded-2xl bg-[#ff4667] text-white flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#191919] mb-2">Experienced Mentors</h3>
                        <p class="text-sm text-[#6d6d6d] leading-relaxed">
                            Learn directly from seasoned industry veterans with proven track records at Fortune 500 companies.
                        </p>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-[#f4f6f9] p-8 rounded-2xl flex flex-col justify-between hover:bg-gray-100 transition-colors">
                    <div class="w-14 h-14 rounded-2xl bg-[#08131e] text-white flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#191919] mb-2">Verified Certificates</h3>
                        <p class="text-sm text-[#6d6d6d] leading-relaxed">
                            Receive verifiable accreditation certificates to showcase on LinkedIn and boost your career resume.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Trusted Partners Ribbon -->
            <div class="text-center">
                <p class="text-xs uppercase tracking-widest text-[#888888] font-bold mb-8">Trusted by leading companies worldwide</p>
                <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-14 opacity-60 grayscale hover:grayscale-0 transition-all">
                    <span class="text-xl font-bold text-gray-700 tracking-tight">ADOBE</span>
                    <span class="text-xl font-bold text-gray-700 tracking-tight">AIRTABLE</span>
                    <span class="text-xl font-bold text-gray-700 tracking-tight">DROPBOX</span>
                    <span class="text-xl font-bold text-gray-700 tracking-tight">GITLAB</span>
                    <span class="text-xl font-bold text-gray-700 tracking-tight">CLICKUP</span>
                    <span class="text-xl font-bold text-gray-700 tracking-tight">LOOM</span>
                    <span class="text-xl font-bold text-gray-700 tracking-tight">LATTICE</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. TOP CATEGORIES (Figma: 206:2018) -->
    <section class="py-20 bg-[#f4f6f9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Browse Subjects</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919] mt-2 mb-3">Top Courses & Categories</h2>
                <p class="text-sm sm:text-base text-[#6d6d6d]">Discover popular learning domains and begin building in-demand skills.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
                @php
                    $categories = [
                        ['title' => 'Frontend Dev', 'icon' => '💻', 'count' => '42 Courses'],
                        ['title' => 'Jira & Agile', 'icon' => '📊', 'count' => '18 Courses'],
                        ['title' => 'UI/UX Design', 'icon' => '🎨', 'count' => '35 Courses'],
                        ['title' => 'Python & AI', 'icon' => '🐍', 'count' => '54 Courses'],
                        ['title' => 'Digital Marketing', 'icon' => '🚀', 'count' => '27 Courses'],
                        ['title' => 'Cloud & DevOps', 'icon' => '☁️', 'count' => '31 Courses'],
                    ];
                @endphp

                @foreach($categories as $cat)
                    <a href="{{ route('courses.grid') }}" class="bg-white border border-[#e7e7e7] p-5 rounded-2xl text-center hover:border-[#ff4667] hover:shadow-md transition-all group flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">
                            {{ $cat['icon'] }}
                        </div>
                        <h4 class="font-bold text-sm text-[#191919] group-hover:text-[#ff4667] transition-colors mb-1">{{ $cat['title'] }}</h4>
                        <span class="text-xs text-[#888888]">{{ $cat['count'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. FEATURED COURSES GRID -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Trending Now</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919] mt-1">Explore Featured Courses</h2>
                </div>
                <a href="{{ route('courses.grid') }}" class="text-sm font-semibold text-[#ff4667] hover:underline flex items-center gap-1.5">
                    <span>View All 1,200+ Courses</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <x-course-card 
                    title="Information About UI/UX Design Degree"
                    category="Design"
                    instructor="David Benitez"
                    instructorAvatar="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80"
                    image="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80"
                    price="$120"
                    rating="4.9"
                    reviews="200"
                />

                <x-course-card 
                    title="Complete Python Bootcamp From Zero to Hero"
                    category="Programming"
                    instructor="Rolands Granger"
                    instructorAvatar="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80"
                    image="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=600&auto=format&fit=crop&q=80"
                    price="$150"
                    oldPrice="$220"
                    rating="4.8"
                    reviews="340"
                />

                <x-course-card 
                    title="The Complete Business and Management Masterclass"
                    category="Business"
                    instructor="Nicole Brown"
                    instructorAvatar="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&auto=format&fit=crop&q=80"
                    image="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&auto=format&fit=crop&q=80"
                    price="$168"
                    rating="5.0"
                    reviews="210"
                />

                <x-course-card 
                    title="Modern Fullstack Web Development with Laravel & Vue"
                    category="Development"
                    instructor="Keny White"
                    instructorAvatar="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=80"
                    image="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80"
                    price="$135"
                    rating="4.9"
                    reviews="185"
                />

                <x-course-card 
                    title="Machine Learning & Deep Learning A-Z with Python"
                    category="Data Science"
                    instructor="Hinata Hyuga"
                    instructorAvatar="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&auto=format&fit=crop&q=80"
                    image="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80"
                    price="$180"
                    oldPrice="$250"
                    rating="4.9"
                    reviews="410"
                />

                <x-course-card 
                    title="Graphic Design Masterclass: Photoshop & Illustrator"
                    category="Design"
                    instructor="John Doe"
                    instructorAvatar="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=100&auto=format&fit=crop&q=80"
                    image="https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&auto=format&fit=crop&q=80"
                    price="$110"
                    rating="4.7"
                    reviews="128"
                />
            </div>
        </div>
    </section>

    <!-- 5. MASTER THE SKILLS BANNER (Figma: 206:1384) -->
    <section class="py-16 bg-[#08131e] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Video Preview -->
                <div class="lg:col-span-6 relative rounded-3xl overflow-hidden shadow-2xl group">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&auto=format&fit=crop&q=80" class="w-full h-80 sm:h-96 object-cover" alt="Learning Experience">
                    <div class="absolute inset-0 bg-[#08131e]/40 flex items-center justify-center">
                        <button class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[#ff4667] text-white flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform cursor-pointer" aria-label="Play Video">
                            <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Content & Stats -->
                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Transform Your Future</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight">
                        Master the Skills to Drive Your Career Forward
                    </h2>
                    <p class="text-gray-300 text-sm sm:text-base leading-relaxed">
                        Stay ahead with industry-demanded tech, design, and business curricula. Get personalized guidance from professionals and build a portfolio that lands jobs.
                    </p>

                    <!-- Stats Row -->
                    <div class="grid grid-cols-2 gap-6 pt-4 border-t border-gray-800">
                        <div>
                            <div class="text-3xl font-extrabold text-[#ff4667]">4.9 / 5.0</div>
                            <div class="text-sm font-semibold text-white mt-1">Average Student Score</div>
                            <div class="text-xs text-gray-400">Over 10,000+ positive student reviews</div>
                        </div>
                        <div>
                            <div class="text-3xl font-extrabold text-white">150,000+</div>
                            <div class="text-sm font-semibold text-white mt-1">Enrolled Learners</div>
                            <div class="text-xs text-gray-400">Across 85+ countries worldwide</div>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        <a href="{{ route('courses.grid') }}" class="bg-[#ff4667] hover:bg-[#e03a58] text-white font-semibold text-sm px-6 py-3 rounded-full transition-colors shadow-md">
                            Explore Courses
                        </a>
                        <a href="{{ route('register') }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold text-sm px-6 py-3 rounded-full transition-colors border border-white/20">
                            Become an Instructor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. TOP INSTRUCTORS -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Qualified Teachers</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919] mt-1">Learn From the Best Mentors</h2>
                </div>
                <a href="{{ route('instructors.grid') }}" class="text-sm font-semibold text-[#ff4667] hover:underline flex items-center gap-1.5">
                    <span>View All Instructors</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-instructor-card 
                    name="Rolands Granger" 
                    role="Developer & Mentor"
                    image="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80"
                    rating="4.9"
                    reviews="200"
                    lessons="12+ Lessons"
                    duration="169hr 20min"
                />
                <x-instructor-card 
                    name="David Benitez" 
                    role="UI/UX Specialist"
                    image="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80"
                    rating="4.9"
                    reviews="180"
                    lessons="15+ Lessons"
                    duration="142hr 10min"
                />
                <x-instructor-card 
                    name="Hinata Hyuga" 
                    role="Data Scientist"
                    image="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&auto=format&fit=crop&q=80"
                    rating="5.0"
                    reviews="310"
                    lessons="20+ Lessons"
                    duration="210hr 45min"
                />
                <x-instructor-card 
                    name="Keny White" 
                    role="Cloud Architect"
                    image="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&auto=format&fit=crop&q=80"
                    rating="4.8"
                    reviews="145"
                    lessons="10+ Lessons"
                    duration="98hr 30min"
                />
            </div>
        </div>
    </section>

</x-layouts.app>
