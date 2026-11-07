<x-layouts.app title="Rolands Granger - Instructor Profile" active="instructors">

    <!-- Instructor Hero Header -->
    <div class="bg-[#392c7d] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&auto=format&fit=crop&q=80" class="w-32 h-32 sm:w-40 sm:h-40 rounded-3xl object-cover border-4 border-white/20 shadow-xl">
                <div class="space-y-3 text-center md:text-left">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Rolands Granger</h1>
                        <span class="bg-[#ff4667] text-white text-xs font-bold px-3 py-1 rounded-full">Top Rated</span>
                    </div>
                    <p class="text-gray-300 text-sm sm:text-base font-medium">Senior Full-Stack Engineer & Cloud Architect</p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 text-xs sm:text-sm text-gray-300 pt-2">
                        <div>⭐ <strong class="text-white">4.9</strong> Rating (200 Reviews)</div>
                        <div>👥 <strong class="text-white">12,450</strong> Students</div>
                        <div>📚 <strong class="text-white">12</strong> Active Courses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructor Bio & Courses Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Column: Biography & Experience -->
            <div class="lg:col-span-8 space-y-10">
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-8 shadow-xs space-y-4">
                    <h2 class="text-2xl font-bold text-[#191919]">Biography</h2>
                    <p class="text-sm text-[#5d5d5d] leading-relaxed">
                        Hello! I am Rolands Granger, a passionate software architect with more than a decade of experience designing and building enterprise distributed systems, web applications, and cloud infrastructures.
                    </p>
                    <p class="text-sm text-[#5d5d5d] leading-relaxed">
                        My mission as an educator is to break down complex architectural patterns, modern backend engineering, and clean code principles into bite-sized, practical project lessons that you can directly apply in your everyday job.
                    </p>
                </div>

                <!-- Courses by this instructor -->
                <div>
                    <h3 class="text-2xl font-bold text-[#191919] mb-6">Courses by Rolands (12)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-course-card 
                            title="Complete Python Bootcamp From Zero to Hero"
                            category="Programming"
                            instructor="Rolands Granger"
                            price="$150"
                            rating="4.8"
                            reviews="340"
                            image="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=600&auto=format&fit=crop&q=80"
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
                    </div>
                </div>
            </div>

            <!-- Right Column: Contact / Social Details -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs space-y-4">
                    <h4 class="font-bold text-[#191919] text-base">Contact Mentor</h4>
                    <p class="text-xs text-[#6d6d6d]">Have questions regarding a course? Reach out directly to discuss mentorship and syllabus queries.</p>
                    <button class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white font-semibold text-xs py-3 rounded-full transition-colors shadow-sm">
                        Send Message
                    </button>
                    <div class="border-t border-gray-100 pt-4 space-y-2 text-xs text-gray-600">
                        <div>🌐 <strong>Website:</strong> rolandsgranger.dev</div>
                        <div>✉️ <strong>Email:</strong> rolands@dreamslms.com</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
