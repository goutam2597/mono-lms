<x-layouts.app title="Screens Gallery - Dreams LMS UI Kit" active="screens">
    <!-- Hero Banner -->
    <section class="bg-gradient-to-r from-[#392c7d] to-[#191919] text-white py-16 px-4 sm:px-8 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-semibold mb-4 text-[#ff4667]">
                <span>Figma UI Kit Node: 1:4659 (Screens)</span>
            </div>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">
                Dreams LMS <span class="text-[#ff4667]">Screens Gallery</span>
            </h1>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl leading-relaxed">
                All production screens from the Dreams LMS Figma UI Kit accurately implemented with Laravel 13, Blade Components, and Tailwind CSS.
            </p>

            <!-- Quick Filter Bar -->
            <div class="flex flex-wrap gap-2 mt-8 pt-6 border-t border-white/10 text-xs font-medium">
                <a href="#homepages" class="px-4 py-2 rounded-full bg-white/10 hover:bg-[#ff4667] text-white transition-colors">Homepages (4)</a>
                <a href="#courses" class="px-4 py-2 rounded-full bg-white/10 hover:bg-[#ff4667] text-white transition-colors">Courses (3)</a>
                <a href="#instructors" class="px-4 py-2 rounded-full bg-white/10 hover:bg-[#ff4667] text-white transition-colors">Instructors (3)</a>
                <a href="#student" class="px-4 py-2 rounded-full bg-white/10 hover:bg-[#ff4667] text-white transition-colors">Student Dashboard (5)</a>
                <a href="#instructor" class="px-4 py-2 rounded-full bg-white/10 hover:bg-[#ff4667] text-white transition-colors">Instructor Dashboard (3)</a>
                <a href="#auth" class="px-4 py-2 rounded-full bg-white/10 hover:bg-[#ff4667] text-white transition-colors">Authentication (6)</a>
            </div>
        </div>
    </section>

    <!-- Main Screens Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-16 space-y-16">

        <!-- 1. HOMEPAGES -->
        <section id="homepages" class="scroll-mt-24">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Section 1</span>
                    <h2 class="text-2xl font-bold text-[#191919]">Homepages</h2>
                </div>
                <span class="text-sm text-gray-500 font-medium">1 Variant</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Home 5 (Main and Only Home) -->
                <div class="bg-white border-2 border-[#ff4667] rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group flex flex-col justify-between relative">
                    <div class="absolute top-3 right-3 z-10">
                        <span class="inline-block bg-[#ff4667] text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                            ★ Active Home
                        </span>
                    </div>
                    <div>
                        <div class="h-48 bg-gradient-to-br from-[#392c7d] via-[#5625e8] to-[#071136] relative p-6 flex flex-col justify-between overflow-hidden">
                            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full"></div>
                            <span class="inline-block bg-white/20 text-white text-[11px] font-bold px-2.5 py-1 rounded-full w-fit backdrop-blur-xs">Home Page 5</span>
                            <div class="text-white">
                                <h3 class="text-2xl font-extrabold">Home Page 5</h3>
                                <p class="text-xs text-gray-200 mt-1">Engaging &amp; Accessible, 4 stats ribbon, 3-part Cloud feature showcase</p>
                            </div>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Figma Node: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-800 font-mono">2355:29907</code></span>
                                <span class="text-[#03c95a] font-semibold flex items-center gap-1">● Active Root (/)</span>
                            </div>
                            <p class="text-sm text-[#6d6d6d]">High-impact layout with search pill, 5 category cards, 8 featured courses, 3 alternating enterprise feature stories, and news masonry.</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('home-5') }}" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold py-2.5 rounded-full flex items-center justify-center gap-2 transition-colors shadow-sm">
                            <span>Open Home Page 5</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. COURSES -->
        <section id="courses" class="scroll-mt-24">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Section 2</span>
                    <h2 class="text-2xl font-bold text-[#191919]">Courses Screens</h2>
                </div>
                <span class="text-sm text-gray-500 font-medium">3 Screens</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Course Grid -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="h-44 bg-blue-50 border-b border-gray-100 p-6 flex flex-col justify-between">
                            <span class="bg-blue-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full w-fit">Catalog View</span>
                            <h3 class="text-xl font-bold text-[#191919]">Course Grid (With Filters)</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Figma Node: <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono">398:51274</code></span>
                                <span class="text-[#03c95a] font-semibold">● Full Filter Sidebar</span>
                            </div>
                            <p class="text-sm text-[#6d6d6d]">Sidebar with Categories, Instructors, Price (Free/Paid), Price Range Slider, Difficulty Level, and Star Ratings.</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('courses.grid') }}" class="w-full bg-[#392c7d] hover:bg-[#ff4667] text-white text-sm font-semibold py-2.5 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Course Grid</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Course List -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="h-44 bg-purple-50 border-b border-gray-100 p-6 flex flex-col justify-between">
                            <span class="bg-purple-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full w-fit">List Layout</span>
                            <h3 class="text-xl font-bold text-[#191919]">Course List View</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Figma Node: <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono">398:51425</code></span>
                                <span class="text-[#03c95a] font-semibold">● Horizontal Cards</span>
                            </div>
                            <p class="text-sm text-[#6d6d6d]">Wide course cards with detailed summaries, lesson hours, instructor bio snippets, and direct purchase triggers.</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('courses.list') }}" class="w-full bg-[#392c7d] hover:bg-[#ff4667] text-white text-sm font-semibold py-2.5 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Course List</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Course Detail -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="h-44 bg-rose-50 border-b border-gray-100 p-6 flex flex-col justify-between">
                            <span class="bg-[#ff4667] text-white text-xs font-semibold px-2.5 py-0.5 rounded-full w-fit">Single Course</span>
                            <h3 class="text-xl font-bold text-[#191919]">Course Details</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Figma Node: <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono">398:52163</code></span>
                                <span class="text-[#03c95a] font-semibold">● Sticky Enrollment</span>
                            </div>
                            <p class="text-sm text-[#6d6d6d]">Hero course overview, sticky pricing video card, curriculum accordion, what you'll learn, and instructor profile.</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('courses.detail') }}" class="w-full bg-[#392c7d] hover:bg-[#ff4667] text-white text-sm font-semibold py-2.5 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Course Details</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. INSTRUCTORS -->
        <section id="instructors" class="scroll-mt-24">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Section 3</span>
                    <h2 class="text-2xl font-bold text-[#191919]">Instructors Screens</h2>
                </div>
                <span class="text-sm text-gray-500 font-medium">3 Screens</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Instructor Grid -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="h-44 bg-amber-50 border-b border-gray-100 p-6 flex flex-col justify-between">
                            <span class="bg-amber-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full w-fit">Instructors Catalog</span>
                            <h3 class="text-xl font-bold text-[#191919]">Instructor Grid</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Figma Node: <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono">311:27960</code></span>
                                <span class="text-[#03c95a] font-semibold">● Grid & Filter</span>
                            </div>
                            <p class="text-sm text-[#6d6d6d]">Mentor cards showing photo, ratings, number of lessons taught, duration, and subject specialty.</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('instructors.grid') }}" class="w-full bg-[#08131e] hover:bg-[#ff4667] text-white text-sm font-semibold py-2.5 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Instructor Grid</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Instructor List -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="h-44 bg-emerald-50 border-b border-gray-100 p-6 flex flex-col justify-between">
                            <span class="bg-emerald-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full w-fit">List View</span>
                            <h3 class="text-xl font-bold text-[#191919]">Instructor List</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Figma Node: <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono">311:35028</code></span>
                                <span class="text-[#03c95a] font-semibold">● Expanded Bios</span>
                            </div>
                            <p class="text-sm text-[#6d6d6d]">Comprehensive horizontal list view of top teachers with course badges and contact options.</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('instructors.list') }}" class="w-full bg-[#08131e] hover:bg-[#ff4667] text-white text-sm font-semibold py-2.5 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Instructor List</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Instructor Detail -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="h-44 bg-indigo-50 border-b border-gray-100 p-6 flex flex-col justify-between">
                            <span class="bg-indigo-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full w-fit">Profile Page</span>
                            <h3 class="text-xl font-bold text-[#191919]">Instructor Details</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Figma Node: <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono">311:37242</code></span>
                                <span class="text-[#03c95a] font-semibold">● Bio & Courses</span>
                            </div>
                            <p class="text-sm text-[#6d6d6d]">Full instructor profile with experience timeline, certifications, student feedback, and active courses.</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('instructors.detail') }}" class="w-full bg-[#08131e] hover:bg-[#ff4667] text-white text-sm font-semibold py-2.5 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Instructor Profile</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. STUDENT DASHBOARD -->
        <section id="student" class="scroll-mt-24">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Section 4</span>
                    <h2 class="text-2xl font-bold text-[#191919]">Student Dashboard Screens</h2>
                </div>
                <span class="text-sm text-gray-500 font-medium">5 Screens</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Dashboard Home -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-[#ff4667] text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">Primary</span>
                            <span class="text-xs text-gray-400 font-mono">5013:75550</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#191919] mb-2">Student Dashboard</h3>
                        <p class="text-sm text-[#6d6d6d]">Interactive dashboard with ongoing quiz banner, 3 stat cards, recently enrolled courses with progress bar, and invoices table.</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('student.dashboard') }}" class="w-full bg-[#392c7d] hover:bg-[#ff4667] text-white text-sm font-semibold py-2 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Student Dashboard</span>
                        </a>
                    </div>
                </div>

                <!-- My Profile -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Account</span>
                            <span class="text-xs text-gray-400 font-mono">5013:75769</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#191919] mb-2">Student Profile</h3>
                        <p class="text-sm text-[#6d6d6d]">Personal info, avatar upload, biography, social links, contact information, and security password settings.</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('student.profile') }}" class="w-full bg-[#392c7d] hover:bg-[#ff4667] text-white text-sm font-semibold py-2 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Student Profile</span>
                        </a>
                    </div>
                </div>

                <!-- Enrolled Courses -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Learning</span>
                            <span class="text-xs text-gray-400 font-mono">5013:75981</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#191919] mb-2">Enrolled Courses</h3>
                        <p class="text-sm text-[#6d6d6d]">Filterable tabs: Enrolled (12), Active (2), Completed (10) with progress bars and certificate download triggers.</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('student.enrolled-courses') }}" class="w-full bg-[#392c7d] hover:bg-[#ff4667] text-white text-sm font-semibold py-2 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Enrolled Courses</span>
                        </a>
                    </div>
                </div>

                <!-- My Certificates -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Awards</span>
                            <span class="text-xs text-gray-400 font-mono">5035:15047</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#191919] mb-2">My Certificates</h3>
                        <p class="text-sm text-[#6d6d6d]">Official achievement certificates list with issuance date, instructor signature, and PDF export buttons.</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('student.certificates') }}" class="w-full bg-[#392c7d] hover:bg-[#ff4667] text-white text-sm font-semibold py-2 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Certificates</span>
                        </a>
                    </div>
                </div>

                <!-- Quiz Attempts -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Assessments</span>
                            <span class="text-xs text-gray-400 font-mono">5013:76280</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#191919] mb-2">My Quiz Attempts</h3>
                        <p class="text-sm text-[#6d6d6d]">Detailed quiz gradebook showing course name, score percentage, passing threshold, status badges, and review modals.</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('student.quiz-attempts') }}" class="w-full bg-[#392c7d] hover:bg-[#ff4667] text-white text-sm font-semibold py-2 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Quiz Attempts</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. INSTRUCTOR DASHBOARD -->
        <section id="instructor" class="scroll-mt-24">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Section 5</span>
                    <h2 class="text-2xl font-bold text-[#191919]">Instructor Dashboard Screens</h2>
                </div>
                <span class="text-sm text-gray-500 font-medium">3 Screens</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Instructor Dashboard -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-[#08131e] text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">Analytics</span>
                            <span class="text-xs text-gray-400 font-mono">5013:76334</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#191919] mb-2">Instructor Dashboard</h3>
                        <p class="text-sm text-[#6d6d6d]">Total Revenue ($18,420), Enrolled Students (12,450), Active Courses (12), earnings graph, and live schedule.</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('instructor.dashboard') }}" class="w-full bg-[#08131e] hover:bg-[#ff4667] text-white text-sm font-semibold py-2 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Instructor Dashboard</span>
                        </a>
                    </div>
                </div>

                <!-- Instructor Courses -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Management</span>
                            <span class="text-xs text-gray-400 font-mono">5013:77112</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#191919] mb-2">Instructor Courses</h3>
                        <p class="text-sm text-[#6d6d6d]">Course catalog table with enrollment counters, publish status toggles, ratings, pricing, and curriculum editors.</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('instructor.courses') }}" class="w-full bg-[#08131e] hover:bg-[#ff4667] text-white text-sm font-semibold py-2 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open My Courses</span>
                        </a>
                    </div>
                </div>

                <!-- Instructor Reviews -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Feedback</span>
                            <span class="text-xs text-gray-400 font-mono">5013:76839</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#191919] mb-2">Instructor Reviews</h3>
                        <p class="text-sm text-[#6d6d6d]">Student review ratings, detailed comment feedback, helpfulness counters, and instructor replies.</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('instructor.reviews') }}" class="w-full bg-[#08131e] hover:bg-[#ff4667] text-white text-sm font-semibold py-2 rounded-full flex items-center justify-center gap-2 transition-colors">
                            <span>Open Reviews</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. AUTHENTICATION -->
        <section id="auth" class="scroll-mt-24">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#ff4667]">Section 6</span>
                    <h2 class="text-2xl font-bold text-[#191919]">Authentication Screens</h2>
                </div>
                <span class="text-sm text-gray-500 font-medium">6 Screens</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Login -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-rose-100 text-[#ff4667] text-xs font-semibold px-2 py-0.5 rounded">Exact Dev Mode</span>
                            <span class="text-xs text-gray-400 font-mono">373:44660</span>
                        </div>
                        <h3 class="font-bold text-base text-[#191919]">Sign In (Login)</h3>
                        <p class="text-xs text-[#6d6d6d] mt-1">Split layout with vector hero, social login (Google/FB), email and password.</p>
                    </div>
                    <a href="{{ route('login') }}" class="mt-4 text-xs font-semibold text-[#ff4667] hover:underline flex items-center gap-1">
                        <span>Preview Screen</span> →
                    </a>
                </div>

                <!-- Register -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-rose-100 text-[#ff4667] text-xs font-semibold px-2 py-0.5 rounded">Registration</span>
                            <span class="text-xs text-gray-400 font-mono">375:48770</span>
                        </div>
                        <h3 class="font-bold text-base text-[#191919]">Sign Up (Register)</h3>
                        <p class="text-xs text-[#6d6d6d] mt-1">Full name, email, password strength indicator, terms acceptance.</p>
                    </div>
                    <a href="{{ route('register') }}" class="mt-4 text-xs font-semibold text-[#ff4667] hover:underline flex items-center gap-1">
                        <span>Preview Screen</span> →
                    </a>
                </div>

                <!-- Forgot Password -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded">Recovery</span>
                            <span class="text-xs text-gray-400 font-mono">375:49092</span>
                        </div>
                        <h3 class="font-bold text-base text-[#191919]">Forgot Password</h3>
                        <p class="text-xs text-[#6d6d6d] mt-1">Password reset email entry with validation and security prompts.</p>
                    </div>
                    <a href="{{ route('forgot-password') }}" class="mt-4 text-xs font-semibold text-[#ff4667] hover:underline flex items-center gap-1">
                        <span>Preview Screen</span> →
                    </a>
                </div>

                <!-- OTP -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded">2FA</span>
                            <span class="text-xs text-gray-400 font-mono">375:49627</span>
                        </div>
                        <h3 class="font-bold text-base text-[#191919]">OTP Verification</h3>
                        <p class="text-xs text-[#6d6d6d] mt-1">4-digit one-time code inputs with countdown timer and resend link.</p>
                    </div>
                    <a href="{{ route('otp') }}" class="mt-4 text-xs font-semibold text-[#ff4667] hover:underline flex items-center gap-1">
                        <span>Preview Screen</span> →
                    </a>
                </div>

                <!-- Set Password -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded">Reset</span>
                            <span class="text-xs text-gray-400 font-mono">375:50135</span>
                        </div>
                        <h3 class="font-bold text-base text-[#191919]">Set New Password</h3>
                        <p class="text-xs text-[#6d6d6d] mt-1">New password and confirmation fields with security requirement checkmarks.</p>
                    </div>
                    <a href="{{ route('set-password') }}" class="mt-4 text-xs font-semibold text-[#ff4667] hover:underline flex items-center gap-1">
                        <span>Preview Screen</span> →
                    </a>
                </div>

                <!-- Lock Screen -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded">Lock</span>
                            <span class="text-xs text-gray-400 font-mono">375:50392</span>
                        </div>
                        <h3 class="font-bold text-base text-[#191919]">Lock Screen</h3>
                        <p class="text-xs text-[#6d6d6d] mt-1">Quick unlock with avatar preview and master PIN/password field.</p>
                    </div>
                    <a href="{{ route('lock-screen') }}" class="mt-4 text-xs font-semibold text-[#ff4667] hover:underline flex items-center gap-1">
                        <span>Preview Screen</span> →
                    </a>
                </div>
            </div>
        </section>

    </div>
</x-layouts.app>
