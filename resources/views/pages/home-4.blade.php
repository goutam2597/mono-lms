<x-layouts.app title="Dreams LMS – Home Page 4" active="home">

    <!-- 1. HERO SECTION (Figma: 2355:26614 & 2355:26661) -->
    <section class="bg-gradient-to-b from-[#f9faff] to-white pt-8 pb-16 lg:pt-12 lg:pb-24 overflow-hidden relative">
        <!-- Background decorative ambient circles -->
        <div class="absolute -top-24 right-0 w-[500px] h-[500px] rounded-full bg-[#392c7d]/5 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-24 w-96 h-96 rounded-full bg-[#ff4667]/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Left Content (Figma: 2355:26662) -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <!-- Top Tagline -->
                    <div class="inline-block">
                        <span class="text-[#ff4667] font-bold text-lg underline decoration-2 underline-offset-4 tracking-wide">
                            The Leader in Online Learning
                        </span>
                    </div>

                    <!-- Main Display Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-[56px] font-extrabold text-[#002058] tracking-tight leading-[1.15]">
                        Engaging & Accessible Online Courses For All
                    </h1>

                    <!-- Description -->
                    <p class="text-[#6d6d6d] text-base sm:text-lg leading-relaxed max-w-xl">
                        Our specialized online courses are designed to bring the classroom experience to you, no matter where you are.
                    </p>

                    <!-- Search Bar with Category Dropdown (Figma: 2355:26667) -->
                    <div class="bg-white border border-[#e0ebff] rounded-xl p-2 sm:p-2.5 shadow-[0px_4px_17px_rgba(195,195,195,0.25)] flex flex-col sm:flex-row items-stretch sm:items-center gap-2 max-w-xl">
                        <!-- Select Category -->
                        <div class="flex items-center gap-2 px-3 py-2 sm:border-r border-[#e0ebff]">
                            <svg class="w-4 h-4 text-[#392c7d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <select class="text-sm font-semibold text-[#191919] bg-transparent focus:outline-none cursor-pointer">
                                <option>Select Category</option>
                                <option>Angular Development</option>
                                <option>Node JS Development</option>
                                <option>AWS Cloud</option>
                                <option>Swift & iOS</option>
                                <option>Vue JS Framework</option>
                            </select>
                        </div>

                        <!-- Text Input -->
                        <div class="flex-1 px-3 py-2">
                            <input type="text" placeholder="Search for Courses, Instructors" class="w-full text-sm text-[#191919] placeholder-[#b0b0b0] bg-transparent focus:outline-none">
                        </div>

                        <!-- Search Button -->
                        <button class="bg-[#ff4667] hover:bg-[#e03a58] text-white px-6 py-3 rounded-lg font-medium text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Search</span>
                        </button>
                    </div>

                    <!-- Trusted By Ribbon (Figma: 2355:26679) -->
                    <div class="pt-2">
                        <p class="text-sm font-medium text-[#191919]">
                            Trusted by over 15K Users worldwide since 2024
                        </p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <div class="flex items-center text-[#ffc107]">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-xs font-medium text-[#6d6d6d]">4.9 / 200 Review</span>
                        </div>
                    </div>
                </div>

                <!-- Right Graphic: Composite Images & Badge (Figma: 2355:26690) -->
                <div class="lg:col-span-5 relative">
                    <div class="relative grid grid-cols-2 gap-4 h-[440px] sm:h-[480px]">
                        <!-- Left Tall Image -->
                        <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=600&auto=format&fit=crop&q=80" alt="Students studying together" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <span class="bg-[#ff4667] text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Top Rated</span>
                                <p class="text-xs font-semibold mt-1">Interactive Classrooms</p>
                            </div>
                        </div>

                        <!-- Right Stack of 2 Images -->
                        <div class="flex flex-col gap-4">
                            <div class="relative flex-1 rounded-2xl overflow-hidden shadow-lg border-4 border-white">
                                <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&auto=format&fit=crop&q=80" alt="Online course student" class="w-full h-full object-cover">
                            </div>
                            <div class="relative flex-1 rounded-2xl overflow-hidden shadow-lg border-4 border-white">
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=500&auto=format&fit=crop&q=80" alt="Student with laptop" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <!-- Center Circular Badge Overlay (Figma: 2355:26695) -->
                        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-28 h-28 bg-white/95 backdrop-blur-md rounded-full shadow-2xl border-4 border-[#ff4667] flex flex-col items-center justify-center p-2 text-center z-20">
                            <span class="text-xs font-extrabold text-[#392c7d] tracking-wider uppercase">Dreams</span>
                            <span class="text-[10px] font-bold text-[#ff4667]">LMS</span>
                            <span class="text-[9px] text-[#6d6d6d] font-medium leading-tight mt-0.5">Verified Quality</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. FOUR FEATURES RIBBON (Figma: 2355:25674) -->
    <section class="bg-[#392c7d] py-10 px-4 sm:px-8 relative z-20 shadow-md">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl p-6 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-xl bg-[#f4f6f9] flex items-center justify-center shrink-0 text-[#392c7d]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <p class="text-[#6d6d6d] font-medium text-sm leading-snug">
                        Get certified with 100+ certification courses
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-xl p-6 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-xl bg-[#f4f6f9] flex items-center justify-center shrink-0 text-[#ff4667]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z"/>
                        </svg>
                    </div>
                    <p class="text-[#6d6d6d] font-medium text-sm leading-snug">
                        Keep up with the latest in cloud & tech
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-xl p-6 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-xl bg-[#f4f6f9] flex items-center justify-center shrink-0 text-[#21b477]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <p class="text-[#6d6d6d] font-medium text-sm leading-snug">
                        Build skills your way, from labs to courses
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white rounded-xl p-6 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-xl bg-[#f4f6f9] flex items-center justify-center shrink-0 text-[#ffb500]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-[#6d6d6d] font-medium text-sm leading-snug">
                        Stay motivated with engaging instructors
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. WHAT'S NEW & 4 STATS (Figma: 2355:25707) -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Section (Figma: 2355:25708) -->
                <div class="lg:col-span-7 space-y-6">
                    <span class="text-[#ff4667] font-bold text-lg">Learn with DreamLMS</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919] leading-tight">
                        Get Trained By Experts & Professionals around the World
                    </h2>
                    <p class="text-[#6d6d6d] text-base leading-relaxed">
                        Mentor is essential for personal and professional growth. This platform provides the key benefits of professional mentoring, including skill development, networking, and career advancement, while offering strategies for finding the right mentor.
                    </p>

                    <!-- 3 Thumbnail Preview Images -->
                    <div class="grid grid-cols-3 gap-4 pt-2">
                        <div class="h-28 rounded-xl overflow-hidden shadow-sm">
                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=400&auto=format&fit=crop&q=80" alt="Tech mentor" class="w-full h-full object-cover">
                        </div>
                        <div class="h-28 rounded-xl overflow-hidden shadow-sm">
                            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=400&auto=format&fit=crop&q=80" alt="Team meeting" class="w-full h-full object-cover">
                        </div>
                        <div class="h-28 rounded-xl overflow-hidden shadow-sm">
                            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?w=400&auto=format&fit=crop&q=80" alt="Classroom learning" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('courses.grid') }}" class="inline-block bg-[#ff4667] hover:bg-[#e03a58] text-white font-medium text-base px-8 py-3 rounded-full transition-colors shadow-sm">
                            Learn More
                        </a>
                    </div>
                </div>

                <!-- Right Section: 4 Stat Cards in 2x2 Grid (Figma: 2355:25726) -->
                <div class="lg:col-span-5 grid grid-cols-2 gap-5">
                    <!-- Stat 1: 10K Online Courses (Green) -->
                    <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 text-center shadow-[0px_4px_12px_rgba(212,212,212,0.25)] flex flex-col items-center justify-center space-y-3">
                        <div class="w-16 h-16 rounded-full bg-emerald-50 text-[#21b477] flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl font-extrabold text-[#21b477]">10K</div>
                            <div class="text-sm font-medium text-[#6d6d6d] mt-1">Online Courses</div>
                        </div>
                    </div>

                    <!-- Stat 2: 40K Online Students (Purple) -->
                    <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 text-center shadow-[0px_4px_12px_rgba(212,212,212,0.25)] flex flex-col items-center justify-center space-y-3">
                        <div class="w-16 h-16 rounded-full bg-indigo-50 text-[#392c7d] flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl font-extrabold text-[#392c7d]">40K</div>
                            <div class="text-sm font-medium text-[#6d6d6d] mt-1">Online Students</div>
                        </div>
                    </div>

                    <!-- Stat 3: 215+ Expert Tutors (Coral) -->
                    <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 text-center shadow-[0px_4px_12px_rgba(212,212,212,0.25)] flex flex-col items-center justify-center space-y-3">
                        <div class="w-16 h-16 rounded-full bg-rose-50 text-[#ff4667] flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl font-extrabold text-[#ff4667]">215+</div>
                            <div class="text-sm font-medium text-[#6d6d6d] mt-1">Expert Tutors</div>
                        </div>
                    </div>

                    <!-- Stat 4: 215+ Certified Courses (Amber) -->
                    <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 text-center shadow-[0px_4px_12px_rgba(212,212,212,0.25)] flex flex-col items-center justify-center space-y-3">
                        <div class="w-16 h-16 rounded-full bg-amber-50 text-[#ffb500] flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl font-extrabold text-[#ffb500]">215+</div>
                            <div class="text-sm font-medium text-[#6d6d6d] mt-1">Certified Courses</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 4. TOP CATEGORIES (Figma: 2355:25768) -->
    <section class="py-20 bg-[#fcfcff]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <span class="text-[#ff4667] font-semibold italic text-lg">Favourite Course</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#002058]">Top Category</h2>
                <p class="text-[#6d6d6d] text-base">
                    Discover our featured courses, specially curated to help you gain in-demand skills
                </p>
            </div>

            <!-- 8 Category Cards (2 Rows of 4) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- 1. Angular -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-[0px_4px_4px_rgba(212,212,212,0.25)] hover:border-[#ff4667] transition-all group flex flex-col justify-between">
                    <div class="w-14 h-14 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-2xl mb-4 group-hover:scale-110 transition-transform">
                        A
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors">Angular Development</h3>
                        <p class="text-sm text-[#6d6d6d] mt-1">220 Instructors</p>
                    </div>
                </div>

                <!-- 2. Nodeman -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-[0px_4px_4px_rgba(212,212,212,0.25)] hover:border-[#ff4667] transition-all group flex flex-col justify-between">
                    <div class="w-14 h-14 rounded-xl bg-green-50 text-emerald-600 flex items-center justify-center font-bold text-2xl mb-4 group-hover:scale-110 transition-transform">
                        N
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors">Nodeman Development</h3>
                        <p class="text-sm text-[#6d6d6d] mt-1">124 Instructors</p>
                    </div>
                </div>

                <!-- 3. Node Js -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-[0px_4px_4px_rgba(212,212,212,0.25)] hover:border-[#ff4667] transition-all group flex flex-col justify-between">
                    <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-2xl mb-4 group-hover:scale-110 transition-transform">
                        JS
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors">Node Js Development</h3>
                        <p class="text-sm text-[#6d6d6d] mt-1">33 Instructors</p>
                    </div>
                </div>

                <!-- 4. AWS -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-[0px_4px_4px_rgba(212,212,212,0.25)] hover:border-[#ff4667] transition-all group flex flex-col justify-between">
                    <div class="w-14 h-14 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition-transform">
                        AWS
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors">AWS Development</h3>
                        <p class="text-sm text-[#6d6d6d] mt-1">285 Instructors</p>
                    </div>
                </div>

                <!-- 5. Strapi CMS -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-[0px_4px_4px_rgba(212,212,212,0.25)] hover:border-[#ff4667] transition-all group flex flex-col justify-between">
                    <div class="w-14 h-14 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition-transform">
                        S
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors">Strapi CMS</h3>
                        <p class="text-sm text-[#6d6d6d] mt-1">99 Instructors</p>
                    </div>
                </div>

                <!-- 6. Swift -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-[0px_4px_4px_rgba(212,212,212,0.25)] hover:border-[#ff4667] transition-all group flex flex-col justify-between">
                    <div class="w-14 h-14 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-2xl mb-4 group-hover:scale-110 transition-transform">
                        
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors">Swift Development</h3>
                        <p class="text-sm text-[#6d6d6d] mt-1">64 Instructors</p>
                    </div>
                </div>

                <!-- 7. Vue JS -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-[0px_4px_4px_rgba(212,212,212,0.25)] hover:border-[#ff4667] transition-all group flex flex-col justify-between">
                    <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center font-bold text-2xl mb-4 group-hover:scale-110 transition-transform">
                        V
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors">VUE JS Development</h3>
                        <p class="text-sm text-[#6d6d6d] mt-1">32 Instructors</p>
                    </div>
                </div>

                <!-- 8. ApostropheCMS -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-[0px_4px_4px_rgba(212,212,212,0.25)] hover:border-[#ff4667] transition-all group flex flex-col justify-between">
                    <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition-transform">
                        A'
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors">ApostropheCMS</h3>
                        <p class="text-sm text-[#6d6d6d] mt-1">21 Instructors</p>
                    </div>
                </div>
            </div>

            <!-- View All Button -->
            <div class="text-center mt-10">
                <a href="{{ route('courses.grid') }}" class="inline-block bg-[#ff4667] hover:bg-[#e03a58] text-white font-medium text-base px-8 py-3 rounded-full transition-colors shadow-sm">
                    View all Category
                </a>
            </div>
        </div>
    </section>

    <!-- 5. FEATURED COURSES (Figma: 2480:20019) -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <span class="text-[#ff4667] font-semibold italic text-lg">What’s New</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919]">Featured Courses</h2>
                <p class="text-[#6d6d6d] text-base">
                    Get certified, master modern tech skills, and level up your career
                </p>
            </div>

            <!-- 6 Course Cards in Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Course 1 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-[0px_4px_12px_rgba(220,220,220,0.25)] hover:-translate-y-1 transition-all">
                    <div class="relative h-48">
                        <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=600&auto=format&fit=crop&q=80" alt="Algebra Course" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#03c95a] text-white text-xs font-bold px-3 py-1 rounded-md">Maths</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-amber-400 text-xs gap-1">
                                <span>★★★★★</span>
                                <span class="text-[#6d6d6d] font-normal ml-1">5.0 (12 Reviews)</span>
                            </div>
                            <span class="text-xs font-semibold text-[#03c95a]">Expert</span>
                        </div>
                        <h3 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('courses.detail') }}">Mastering Algebra: From Basics to Advanced Concepts</a>
                        </h3>
                        <div class="flex items-center justify-between text-sm">
                            <a href="{{ route('instructors.detail') }}" class="text-[#5625e8] underline font-medium">Emily Williams</a>
                            <span class="text-[#6d6d6d] font-medium text-xs">2hr 10min</span>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl font-bold text-[#ff4667]">$300</span>
                                <span class="text-xs text-[#b0b0b0] line-through">$999.00</span>
                            </div>
                            <a href="{{ route('courses.detail') }}" class="text-xs font-bold text-[#392c7d] hover:text-[#ff4667]">Enroll Now →</a>
                        </div>
                    </div>
                </div>

                <!-- Course 2 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-[0px_4px_12px_rgba(220,220,220,0.25)] hover:-translate-y-1 transition-all">
                    <div class="relative h-48">
                        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=600&auto=format&fit=crop&q=80" alt="Self Improvements" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#03c95a] text-white text-xs font-bold px-3 py-1 rounded-md">Self Improvements</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-amber-400 text-xs gap-1">
                                <span>★★★★★</span>
                                <span class="text-[#6d6d6d] font-normal ml-1">4.9 (45 Reviews)</span>
                            </div>
                            <span class="text-xs font-semibold text-[#03c95a]">All Levels</span>
                        </div>
                        <h3 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('courses.detail') }}">Transform Your Mindset & Habits for Peak Performance</a>
                        </h3>
                        <div class="flex items-center justify-between text-sm">
                            <a href="{{ route('instructors.detail') }}" class="text-[#5625e8] underline font-medium">David Miller</a>
                            <span class="text-[#6d6d6d] font-medium text-xs">4hr 30min</span>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl font-bold text-[#ff4667]">$240</span>
                            </div>
                            <a href="{{ route('courses.detail') }}" class="text-xs font-bold text-[#392c7d] hover:text-[#ff4667]">Enroll Now →</a>
                        </div>
                    </div>
                </div>

                <!-- Course 3 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-[0px_4px_12px_rgba(220,220,220,0.25)] hover:-translate-y-1 transition-all">
                    <div class="relative h-48">
                        <img src="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=600&auto=format&fit=crop&q=80" alt="Machine Learning" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#ff4667] text-white text-xs font-bold px-3 py-1 rounded-md">Python AI</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-amber-400 text-xs gap-1">
                                <span>★★★★★</span>
                                <span class="text-[#6d6d6d] font-normal ml-1">5.0 (88 Reviews)</span>
                            </div>
                            <span class="text-xs font-semibold text-[#03c95a]">Advanced</span>
                        </div>
                        <h3 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('courses.detail') }}">Python Bootcamp: Zero to Hero in Machine Learning</a>
                        </h3>
                        <div class="flex items-center justify-between text-sm">
                            <a href="{{ route('instructors.detail') }}" class="text-[#5625e8] underline font-medium">Sarah Jenkins</a>
                            <span class="text-[#6d6d6d] font-medium text-xs">8hr 15min</span>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl font-bold text-[#ff4667]">$356</span>
                            </div>
                            <a href="{{ route('courses.detail') }}" class="text-xs font-bold text-[#392c7d] hover:text-[#ff4667]">Enroll Now →</a>
                        </div>
                    </div>
                </div>

                <!-- Course 4 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-[0px_4px_12px_rgba(220,220,220,0.25)] hover:-translate-y-1 transition-all">
                    <div class="relative h-48">
                        <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80" alt="Laravel Course" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#392c7d] text-white text-xs font-bold px-3 py-1 rounded-md">Web Dev</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-amber-400 text-xs gap-1">
                                <span>★★★★★</span>
                                <span class="text-[#6d6d6d] font-normal ml-1">4.8 (34 Reviews)</span>
                            </div>
                            <span class="text-xs font-semibold text-[#03c95a]">Intermediate</span>
                        </div>
                        <h3 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('courses.detail') }}">Full-Stack Web Development with Laravel & Vue</a>
                        </h3>
                        <div class="flex items-center justify-between text-sm">
                            <a href="{{ route('instructors.detail') }}" class="text-[#5625e8] underline font-medium">Alex Turner</a>
                            <span class="text-[#6d6d6d] font-medium text-xs">6hr 40min</span>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl font-bold text-[#ff4667]">$280</span>
                                <span class="text-xs text-[#b0b0b0] line-through">$650.00</span>
                            </div>
                            <a href="{{ route('courses.detail') }}" class="text-xs font-bold text-[#392c7d] hover:text-[#ff4667]">Enroll Now →</a>
                        </div>
                    </div>
                </div>

                <!-- Course 5 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-[0px_4px_12px_rgba(220,220,220,0.25)] hover:-translate-y-1 transition-all">
                    <div class="relative h-48">
                        <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&auto=format&fit=crop&q=80" alt="iOS App Development" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#ffb500] text-white text-xs font-bold px-3 py-1 rounded-md">Mobile</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-amber-400 text-xs gap-1">
                                <span>★★★★★</span>
                                <span class="text-[#6d6d6d] font-normal ml-1">4.9 (70 Reviews)</span>
                            </div>
                            <span class="text-xs font-semibold text-[#03c95a]">All Levels</span>
                        </div>
                        <h3 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('courses.detail') }}">iOS 18 App Development with Swift & SwiftUI</a>
                        </h3>
                        <div class="flex items-center justify-between text-sm">
                            <a href="{{ route('instructors.detail') }}" class="text-[#5625e8] underline font-medium">Jessica Alba</a>
                            <span class="text-[#6d6d6d] font-medium text-xs">5hr 20min</span>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl font-bold text-[#ff4667]">$320</span>
                            </div>
                            <a href="{{ route('courses.detail') }}" class="text-xs font-bold text-[#392c7d] hover:text-[#ff4667]">Enroll Now →</a>
                        </div>
                    </div>
                </div>

                <!-- Course 6 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-[0px_4px_12px_rgba(220,220,220,0.25)] hover:-translate-y-1 transition-all">
                    <div class="relative h-48">
                        <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=600&auto=format&fit=crop&q=80" alt="AWS Cloud" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#ff4667] text-white text-xs font-bold px-3 py-1 rounded-md">Cloud</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-amber-400 text-xs gap-1">
                                <span>★★★★★</span>
                                <span class="text-[#6d6d6d] font-normal ml-1">5.0 (92 Reviews)</span>
                            </div>
                            <span class="text-xs font-semibold text-[#03c95a]">Expert</span>
                        </div>
                        <h3 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('courses.detail') }}">Cloud Solutions Architecture with AWS & DevOps</a>
                        </h3>
                        <div class="flex items-center justify-between text-sm">
                            <a href="{{ route('instructors.detail') }}" class="text-[#5625e8] underline font-medium">Robert Fox</a>
                            <span class="text-[#6d6d6d] font-medium text-xs">7hr 45min</span>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl font-bold text-[#ff4667]">$390</span>
                            </div>
                            <a href="{{ route('courses.detail') }}" class="text-xs font-bold text-[#392c7d] hover:text-[#ff4667]">Enroll Now →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View All Courses Button -->
            <div class="text-center mt-12">
                <a href="{{ route('courses.grid') }}" class="inline-block bg-[#ff4667] hover:bg-[#e03a58] text-white font-medium text-base px-8 py-3 rounded-full transition-colors shadow-sm">
                    View all Courses
                </a>
            </div>
        </div>
    </section>

    <!-- 6. 3 ALTERNATING FEATURE HIGHLIGHTS (Figma: 2355:26259) -->
    <section class="py-20 bg-[#fafbfe]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-16">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <span class="text-[#ff4667] font-bold text-lg">What’s New</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919]">Master the skills to drive your career</h2>
                <p class="text-[#6d6d6d] text-base">
                    Get certified, master modern tech skills, and level up your career — whether you’re starting out or a seasoned pro. 95% of eLearning learners report.
                </p>
            </div>

            <!-- Block 1: Award Winning (Text Left, Image Right) -->
            <div class="bg-[#f4f6f9] rounded-2xl p-6 sm:p-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-6 space-y-6">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#191919]">
                            Award Winning Course Management
                        </h3>
                        <p class="text-[#6d6d6d] text-base leading-relaxed">
                            An award-winning course management system (CMS) or program is typically recognized for its exceptional quality, innovation, and effectiveness in helping both instructors and students succeed.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Interactive Tools for Engagement
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Customizable Course Creation
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Robust Analytics and Reporting
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Collaborative and Peer-to-Peer Learning
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                The Most World Class Instructors
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-6 relative">
                        <div class="rounded-xl overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=700&auto=format&fit=crop&q=80" alt="Course classroom" class="w-full h-80 sm:h-96 object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Block 2: Learn Anything from Anywhere (Image Left, Text Right) -->
            <div class="bg-[#f4f6f9] rounded-2xl p-6 sm:p-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-6 order-2 lg:order-1 relative">
                        <div class="rounded-xl overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=700&auto=format&fit=crop&q=80" alt="Anywhere learning" class="w-full h-80 sm:h-96 object-cover">
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-20 h-20 rounded-2xl bg-[#21b477] text-white flex flex-col items-center justify-center shadow-lg font-bold text-xs p-2 text-center">
                            <span>24/7</span>
                            <span>Access</span>
                        </div>
                    </div>
                    <div class="lg:col-span-6 order-1 lg:order-2 space-y-6">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#191919]">
                            Learn anything from anywhere anytime
                        </h3>
                        <p class="text-[#6d6d6d] text-base leading-relaxed">
                            In today’s fast-paced, digital world, the ability to learn anything, from anywhere, and at any time is more accessible than ever. Whether you are looking to expand your knowledge or gain new skills.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Access to a World of Knowledge
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Diverse Learning Formats
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Learn at Your Own Pace
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Affordable and Flexible Pricing
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Learning from Anywhere
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Block 3: Certification for Career (Text Left, Image Right) -->
            <div class="bg-[#f4f6f9] rounded-2xl p-6 sm:p-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-6 space-y-6">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#191919]">
                            Certification for solid development of your Career
                        </h3>
                        <p class="text-[#6d6d6d] text-base leading-relaxed">
                            Certifications are a powerful way to enhance your skills, build credibility, and boost your career growth. In today’s competitive job market, a professional certification sets you apart.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Demonstrates Industry Expertise
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Increases Earning Potential
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Globally Recognized Credentials
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-[#6d6d6d]">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Portfolio of Real Projects
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-6 relative">
                        <div class="rounded-xl overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=700&auto=format&fit=crop&q=80" alt="Career certification" class="w-full h-80 sm:h-96 object-cover">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- 7. WHY CHOOSE US / VALUE PROPOSITIONS (Figma: 2355:26368) -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 rounded-2xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&auto=format&fit=crop&q=80" alt="Students learning" class="w-full h-[480px] object-cover">
                </div>
                <div class="lg:col-span-6 space-y-8">
                    <div>
                        <span class="text-[#ff4667] font-bold text-lg">Why Choose Us</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919] mt-2 leading-tight">
                            Build Your Future with the World's Best Learning Platform
                        </h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Item 1 -->
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-indigo-50 text-[#392c7d] flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-[#191919]">Learn with Experts</h4>
                                <p class="text-sm text-[#6d6d6d] mt-1">Our instructors are industry veterans with decades of hands-on production experience.</p>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-rose-50 text-[#ff4667] flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-[#191919]">Download Courses for Offline Study</h4>
                                <p class="text-sm text-[#6d6d6d] mt-1">Take your lessons anywhere, anytime with uninterrupted offline video playback.</p>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-emerald-50 text-[#21b477] flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-[#191919]">Advanced Skill Certification</h4>
                                <p class="text-sm text-[#6d6d6d] mt-1">Receive verifiable diplomas and share credentials directly on your LinkedIn profile.</p>
                            </div>
                        </div>

                        <!-- Item 4 -->
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-amber-50 text-[#ffb500] flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-[#191919]">Real-world Portfolio Projects</h4>
                                <p class="text-sm text-[#6d6d6d] mt-1">Graduate with real projects that prove your capabilities to recruiters.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. STATS WORLDWIDE LEARNERS BANNER (Figma: 2355:26710) -->
    <section class="bg-[#392c7d] text-white py-20 relative overflow-hidden">
        <!-- Floating Avatar Bubbles Map Pattern -->
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <div class="absolute top-10 left-12 w-16 h-16 rounded-full bg-white/20"></div>
            <div class="absolute top-24 right-20 w-20 h-20 rounded-full bg-white/20"></div>
            <div class="absolute bottom-10 left-1/4 w-24 h-24 rounded-full bg-white/20"></div>
            <div class="absolute bottom-16 right-1/3 w-16 h-16 rounded-full bg-white/20"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 relative z-10 text-center space-y-12">
            <div class="max-w-2xl mx-auto space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Join more than 1 million learners worldwide
                </h2>
                <p class="text-indigo-200 text-base">
                    Learn from anywhere, with any device, anytime with our cloud platform
                </p>
            </div>

            <!-- 3 Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-center space-y-2">
                    <div class="text-4xl sm:text-5xl font-extrabold text-[#ff4667]">250K+</div>
                    <div class="text-sm font-medium text-gray-200">Enrolled Students</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-center space-y-2">
                    <div class="text-4xl sm:text-5xl font-extrabold text-[#ffb500]">120+</div>
                    <div class="text-sm font-medium text-gray-200">Countries Represented</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-center space-y-2">
                    <div class="text-4xl sm:text-5xl font-extrabold text-emerald-400">95%</div>
                    <div class="text-sm font-medium text-gray-200">Course Completion Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. TOP INSTRUCTORS / MENTORS (Figma: 2355:26416) -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <span class="text-[#ff4667] font-bold text-lg">Top Mentors</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919]">Learn From Top Instructors</h2>
                <p class="text-[#6d6d6d] text-base">
                    Our verified mentors are industry leads from Google, Meta, Amazon, and Microsoft.
                </p>
            </div>

            <!-- 4 Instructor Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Instructor 1 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                    <div class="relative h-64 overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80" alt="Instructor" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-gray-400 hover:text-[#ff4667] transition-colors">
                            ♥
                        </button>
                    </div>
                    <div class="p-6 text-center space-y-2">
                        <h4 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('instructors.detail') }}">David Benitez</a>
                        </h4>
                        <p class="text-xs text-[#ff4667] font-semibold">Senior UX Architect</p>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-center gap-2 text-xs text-[#6d6d6d]">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                            <span>15,400 Students</span>
                        </div>
                    </div>
                </div>

                <!-- Instructor 2 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                    <div class="relative h-64 overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&auto=format&fit=crop&q=80" alt="Instructor" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-gray-400 hover:text-[#ff4667] transition-colors">
                            ♥
                        </button>
                    </div>
                    <div class="p-6 text-center space-y-2">
                        <h4 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('instructors.detail') }}">Rolands Granger</a>
                        </h4>
                        <p class="text-xs text-[#ff4667] font-semibold">Cloud Systems Specialist</p>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-center gap-2 text-xs text-[#6d6d6d]">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                            <span>28,300 Students</span>
                        </div>
                    </div>
                </div>

                <!-- Instructor 3 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                    <div class="relative h-64 overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=500&auto=format&fit=crop&q=80" alt="Instructor" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-gray-400 hover:text-[#ff4667] transition-colors">
                            ♥
                        </button>
                    </div>
                    <div class="p-6 text-center space-y-2">
                        <h4 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('instructors.detail') }}">Hannah Morales</a>
                        </h4>
                        <p class="text-xs text-[#ff4667] font-semibold">AI & Deep Learning Lead</p>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-center gap-2 text-xs text-[#6d6d6d]">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                            <span>19,800 Students</span>
                        </div>
                    </div>
                </div>

                <!-- Instructor 4 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                    <div class="relative h-64 overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=500&auto=format&fit=crop&q=80" alt="Instructor" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-gray-400 hover:text-[#ff4667] transition-colors">
                            ♥
                        </button>
                    </div>
                    <div class="p-6 text-center space-y-2">
                        <h4 class="font-bold text-lg text-[#191919] hover:text-[#ff4667] transition-colors">
                            <a href="{{ route('instructors.detail') }}">Arthur Morgan</a>
                        </h4>
                        <p class="text-xs text-[#ff4667] font-semibold">Full-Stack Consultant</p>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-center gap-2 text-xs text-[#6d6d6d]">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                            <span>35,100 Students</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- See All Button -->
            <div class="text-center mt-10">
                <a href="{{ route('instructors.grid') }}" class="inline-block bg-[#ff4667] hover:bg-[#e03a58] text-white font-medium text-base px-8 py-3 rounded-full transition-colors shadow-sm">
                    See all Mentors
                </a>
            </div>
        </div>
    </section>

    <!-- 10. REVIEWS & BRAND LOGOS (Figma: 2355:26521) -->
    <section class="py-20 bg-[#f9faff]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-16">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <span class="text-[#ff4667] font-bold text-lg">Testimonials</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919]">What Our Students Say</h2>
                <p class="text-[#6d6d6d] text-base">
                    Discover how Dreams LMS transformed careers and unlocked new possibilities for students across the globe.
                </p>
            </div>

            <!-- Review Card Split -->
            <div class="bg-white rounded-2xl p-8 sm:p-12 shadow-lg border border-gray-100 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-5 relative">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80" alt="Student testimonial" class="w-full h-80 sm:h-96 object-cover rounded-2xl shadow-md">
                    <!-- Quote Mark Badge -->
                    <div class="absolute top-4 right-4 w-12 h-12 bg-[#ff4667] text-white rounded-2xl flex items-center justify-center font-serif text-3xl shadow-lg">
                        “
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-6">
                    <div class="flex items-center text-amber-400 gap-1 text-sm">
                        <span>★★★★★</span>
                    </div>
                    <h3 class="text-2xl font-bold text-[#191919]">
                        "The best investment I made for my developer career."
                    </h3>
                    <p class="text-[#6d6d6d] text-base leading-relaxed">
                        The practical curriculum and mentoring gave me confidence to apply for Senior Frontend roles. Within 3 months of finishing the course track, I secured an offer with a 45% salary bump!
                    </p>
                    <div>
                        <div class="font-bold text-lg text-[#191919]">Sophia Martinez</div>
                        <div class="text-sm text-[#ff4667] font-medium">Full Stack Engineer at Stripe</div>
                    </div>
                </div>
            </div>

            <!-- Brand Logos Ribbon (Figma: 2355:26554) -->
            <div class="pt-6 border-t border-gray-200">
                <p class="text-center text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">
                    Trusted By Teams At
                </p>
                <div class="flex flex-wrap items-center justify-center sm:justify-between gap-8 opacity-60">
                    <span class="text-xl font-extrabold text-gray-600 tracking-tight">Atlassian Jira</span>
                    <span class="text-xl font-extrabold text-gray-600 tracking-tight">Calendly</span>
                    <span class="text-xl font-extrabold text-gray-600 tracking-tight">LiveChat</span>
                    <span class="text-xl font-extrabold text-gray-600 tracking-tight">Dropbox</span>
                    <span class="text-xl font-extrabold text-gray-600 tracking-tight">Mixpanel</span>
                    <span class="text-xl font-extrabold text-gray-600 tracking-tight">Make.com</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 11. LATEST BLOGS / NEWS (Figma: 2355:26562) -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <span class="text-[#ff4667] font-bold text-lg">Latest Blogs</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#191919]">Insights, News & Educational Trends</h2>
                <p class="text-[#6d6d6d] text-base">
                    Read the latest research and perspectives from top engineers and educators.
                </p>
            </div>

            <!-- 3 Article Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Blog 1 -->
                <div class="rounded-2xl border border-[#e7e7e7] overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col justify-between">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80" alt="Blog post" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        <span class="absolute top-3 left-3 bg-[#ff4667] text-white text-xs font-bold px-3 py-1 rounded-md">Education</span>
                    </div>
                    <div class="p-6 space-y-3">
                        <span class="text-xs text-[#6d6d6d] flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#ff4667]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            November 07, 2026
                        </span>
                        <h4 class="font-bold text-lg text-[#191919] group-hover:text-[#ff4667] transition-colors leading-snug">
                            How Generative AI is Reshaping STEM Learning in 2026
                        </h4>
                        <p class="text-sm text-[#6d6d6d]">
                            Discover how personalized AI tutors are augmenting classroom experiences and accelerating mastery.
                        </p>
                    </div>
                </div>

                <!-- Blog 2 -->
                <div class="rounded-2xl border border-[#e7e7e7] overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col justify-between">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?w=600&auto=format&fit=crop&q=80" alt="Blog post" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        <span class="absolute top-3 left-3 bg-[#392c7d] text-white text-xs font-bold px-3 py-1 rounded-md">Career</span>
                    </div>
                    <div class="p-6 space-y-3">
                        <span class="text-xs text-[#6d6d6d] flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#ff4667]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            November 04, 2026
                        </span>
                        <h4 class="font-bold text-lg text-[#191919] group-hover:text-[#ff4667] transition-colors leading-snug">
                            10 High-Paying Software Skills Every Junior Engineer Needs
                        </h4>
                        <p class="text-sm text-[#6d6d6d]">
                            From Docker microservices to cloud observability, here are the tools hiring managers are looking for.
                        </p>
                    </div>
                </div>

                <!-- Blog 3 -->
                <div class="rounded-2xl border border-[#e7e7e7] overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col justify-between">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&auto=format&fit=crop&q=80" alt="Blog post" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        <span class="absolute top-3 left-3 bg-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-md">Mentorship</span>
                    </div>
                    <div class="p-6 space-y-3">
                        <span class="text-xs text-[#6d6d6d] flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#ff4667]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            October 29, 2026
                        </span>
                        <h4 class="font-bold text-lg text-[#191919] group-hover:text-[#ff4667] transition-colors leading-snug">
                            Why Having a Senior Mentor Beats 100 Hours of Tutorial Hell
                        </h4>
                        <p class="text-sm text-[#6d6d6d]">
                            Real feedback and code reviews are the single greatest secret to developer velocity and interview readiness.
                        </p>
                    </div>
                </div>
            </div>

            <!-- View All News Button -->
            <div class="text-center mt-12">
                <a href="{{ route('courses.grid') }}" class="inline-block bg-[#ff4667] hover:bg-[#e03a58] text-white font-medium text-base px-8 py-3 rounded-full transition-colors shadow-sm">
                    View all News
                </a>
            </div>
        </div>
    </section>

</x-layouts.app>
