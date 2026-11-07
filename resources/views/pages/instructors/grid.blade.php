<x-layouts.app title="Instructor Grid - Dreams LMS" active="instructors">

    <!-- Page Title Header (Figma: 311:27960) -->
    <div class="bg-[#f4f6f9] py-10 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#191919]">Instructor Grid</h1>
                <nav class="flex items-center gap-2 text-xs text-[#6d6d6d] mt-2">
                    <a href="{{ route('home') }}" class="hover:text-[#ff4667]">Home</a>
                    <span>/</span>
                    <a href="{{ route('instructors.grid') }}" class="hover:text-[#ff4667]">Instructors</a>
                    <span>/</span>
                    <span class="text-[#ff4667] font-medium">Instructor Grid</span>
                </nav>
            </div>
            <div class="text-xs text-[#888888] font-medium">
                Figma Node: <code class="bg-white px-2 py-1 rounded border border-gray-200 font-mono text-[#191919]">311:27960</code>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <!-- Results & Sort Bar -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 mb-8 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 shadow-xs">
            <div class="text-xs sm:text-sm text-[#6d6d6d]">
                Showing <strong class="text-[#191919]">1–8</strong> of <strong class="text-[#191919]">24</strong> instructors
            </div>

            <!-- View Switcher & Search -->
            <div class="flex items-center gap-3">
                <div class="flex items-center bg-[#f4f6f9] p-1 rounded-lg border border-gray-200">
                    <a href="{{ route('instructors.grid') }}" class="p-1.5 rounded bg-white text-[#ff4667] shadow-xs" title="Grid View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </a>
                    <a href="{{ route('instructors.list') }}" class="p-1.5 rounded text-gray-500 hover:text-[#191919]" title="List View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </a>
                </div>
                <input type="text" placeholder="Search mentors..." class="bg-white border border-[#e7e7e7] text-xs text-[#191919] px-3 py-2 rounded-lg focus:outline-none w-48">
            </div>
        </div>

        <!-- Instructors Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-instructor-card 
                name="Rolands Granger"
                role="Full-Stack Developer"
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
                role="Machine Learning Engineer"
                image="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&auto=format&fit=crop&q=80"
                rating="5.0"
                reviews="310"
                lessons="20+ Lessons"
                duration="210hr 45min"
            />
            <x-instructor-card 
                name="Keny White"
                role="Cloud Systems Architect"
                image="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&auto=format&fit=crop&q=80"
                rating="4.8"
                reviews="145"
                lessons="10+ Lessons"
                duration="98hr 30min"
            />
            <x-instructor-card 
                name="Nicole Brown"
                role="Strategic Business Coach"
                image="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&auto=format&fit=crop&q=80"
                rating="4.9"
                reviews="220"
                lessons="14+ Lessons"
                duration="115hr 00min"
            />
            <x-instructor-card 
                name="John Doe"
                role="Creative Director & Artist"
                image="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80"
                rating="4.7"
                reviews="98"
                lessons="8+ Lessons"
                duration="76hr 15min"
            />
            <x-instructor-card 
                name="Sarah Jenkins"
                role="Cybersecurity Specialist"
                image="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=600&auto=format&fit=crop&q=80"
                rating="4.9"
                reviews="165"
                lessons="11+ Lessons"
                duration="130hr 50min"
            />
            <x-instructor-card 
                name="Michael Chang"
                role="Data Architect"
                image="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=600&auto=format&fit=crop&q=80"
                rating="4.8"
                reviews="190"
                lessons="16+ Lessons"
                duration="155hr 20min"
            />
        </div>
    </div>

</x-layouts.app>
