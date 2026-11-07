@props(['title' => 'Dashboard - Dreams LMS', 'active' => 'dashboard', 'userType' => 'student'])

<x-layouts.app :title="$title" :active="$userType === 'student' ? 'student' : 'instructor'">
    <div class="bg-[#f4f6f9] py-8 min-h-[calc(100vh-200px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <!-- Profile Banner -->
            @if($userType === 'student')
                <x-student-header-banner />
            @else
                <div class="relative bg-gradient-to-r from-[#08131e] to-[#1f1947] rounded-2xl p-6 sm:p-8 text-white mb-8 overflow-hidden shadow-md">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80" alt="Rolands Granger" class="w-20 h-20 rounded-full object-cover border-4 border-white/20 shadow-lg">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h1 class="text-2xl sm:text-3xl font-bold">Rolands Granger</h1>
                                    <span class="bg-[#ff4667] text-white text-[11px] font-semibold px-2.5 py-0.5 rounded-full">Instructor</span>
                                </div>
                                <p class="text-gray-300 text-sm mt-1">Senior Full-Stack Mentor • 12 Courses</p>
                                <div class="flex items-center gap-4 mt-3 text-xs sm:text-sm text-gray-300">
                                    <div><strong class="text-white font-bold">12,450</strong> Students</div>
                                    <div>•</div>
                                    <div><strong class="text-white font-bold">4.9 ★</strong> Rating</div>
                                    <div>•</div>
                                    <div><strong class="text-white font-bold">$18,420</strong> Earnings</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('courses.grid') }}" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs sm:text-sm font-semibold px-5 py-2.5 rounded-full transition-colors shadow-sm">
                            + Create New Course
                        </a>
                    </div>
                </div>
            @endif

            <!-- Main Layout Grid: Sidebar + Content -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-3">
                    @if($userType === 'student')
                        <x-student-sidebar :active="$active" />
                    @else
                        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs space-y-1">
                            <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ $active === 'dashboard' ? 'bg-[#ff4667] text-white' : 'text-[#5d5d5d] hover:bg-[#f4f6f9]' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('instructor.courses') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ $active === 'courses' ? 'bg-[#ff4667] text-white' : 'text-[#5d5d5d] hover:bg-[#f4f6f9]' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <span>My Courses</span>
                            </a>
                            <a href="{{ route('instructor.reviews') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ $active === 'reviews' ? 'bg-[#ff4667] text-white' : 'text-[#5d5d5d] hover:bg-[#f4f6f9]' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                <span>Reviews</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Main Content Slot -->
                <div class="lg:col-span-9">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
