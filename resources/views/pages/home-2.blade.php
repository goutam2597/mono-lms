<x-layouts.app title="Dreams LMS – Home Page 2" active="home">
    <!-- Hero 2: Split with Search and Quick Badges -->
    <section class="bg-gradient-to-r from-[#08131e] via-[#112338] to-[#08131e] text-white py-20 px-4 sm:px-8 relative overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <span class="inline-block bg-[#ff4667]/20 border border-[#ff4667]/40 text-[#ff4667] text-xs font-bold px-3.5 py-1.5 rounded-full">
                    Over 500+ New Courses Added This Month
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                    Upgrade Your Skills with <span class="text-[#ff4667]">World-Class Mentors</span>
                </h1>
                <p class="text-gray-300 text-base sm:text-lg max-w-xl">
                    Join millions of students learning cutting-edge skills from practical projects, real-world case studies, and personalized coaching.
                </p>

                <!-- Search -->
                <div class="bg-white p-2 rounded-full shadow-2xl flex items-center gap-2 max-w-lg">
                    <svg class="w-5 h-5 text-gray-400 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="What do you want to learn today?" class="w-full text-sm text-[#191919] bg-transparent focus:outline-none px-2 py-2">
                    <button class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold px-6 py-2.5 rounded-full transition-colors">
                        Explore
                    </button>
                </div>

                <!-- Trending topics pills -->
                <div class="flex flex-wrap items-center gap-2 pt-2 text-xs text-gray-300">
                    <span class="text-gray-400">Popular:</span>
                    <a href="{{ route('courses.grid') }}" class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full transition-colors">UI/UX Design</a>
                    <a href="{{ route('courses.grid') }}" class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full transition-colors">React 19</a>
                    <a href="{{ route('courses.grid') }}" class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full transition-colors">Python AI</a>
                    <a href="{{ route('courses.grid') }}" class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full transition-colors">Marketing</a>
                </div>
            </div>

            <!-- Graphic Banner -->
            <div class="lg:col-span-5 relative flex items-center justify-center">
                <div class="w-full max-w-md bg-white rounded-2xl p-6 shadow-2xl text-[#191919] space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <span class="text-xs font-bold text-gray-400 uppercase">Live Masterclass</span>
                        <span class="bg-emerald-100 text-emerald-700 text-[11px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-ping"></span> Live Now
                        </span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=600&auto=format&fit=crop&q=80" class="w-full h-44 object-cover rounded-xl" alt="Web Dev Live">
                    <h3 class="font-bold text-lg text-[#191919]">Building Scalable Web Apps with Microservices</h3>
                    <div class="flex items-center justify-between text-xs text-[#6d6d6d]">
                        <span>Instructor: Rolands Granger</span>
                        <span>4.9 ★ (850 Students)</span>
                    </div>
                    <a href="{{ route('courses.detail') }}" class="block w-full text-center bg-[#392c7d] hover:bg-[#ff4667] text-white text-xs font-semibold py-2.5 rounded-full transition-colors">
                        Join Class Free →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Grid Section -->
    <section class="py-16 bg-[#f4f6f9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-[#191919]">Popular Courses in Home 2</h2>
                <a href="{{ route('courses.grid') }}" class="text-xs font-bold text-[#ff4667] hover:underline">View All Courses →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <x-course-card title="Advanced Interaction Design in Figma" category="Design" price="$140" />
                <x-course-card title="Docker and Kubernetes for Modern Developers" category="DevOps" price="$160" />
                <x-course-card title="Product Strategy: From Discovery to Market" category="Product" price="$175" />
            </div>
        </div>
    </section>
</x-layouts.app>
