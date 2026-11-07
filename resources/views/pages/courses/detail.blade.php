<x-layouts.app :title="$course->title.' - Mono LMS'" active="courses">

    @if (session('success') || session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-8 pt-6">
            <div class="{{ session('success') ? 'bg-[#03c95a]/10 border-[#03c95a]/30 text-[#0d7a38]' : 'bg-[#5625e8]/10 border-[#5625e8]/30 text-[#392c7d]' }} border px-4 py-3 rounded-xl text-sm font-medium">
                {{ session('success') ?? session('info') }}
            </div>
        </div>
    @endif

    <!-- Course Hero Section -->
    <div class="bg-[#08131e] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-8 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="bg-[#ff4667] text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $course->category?->name ?? '' }}</span>
                        <span class="text-xs text-gray-400">{{ __('app.levels.'.$course->level) }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                        {{ $course->title }}
                    </h1>

                    @if ($course->subtitle)
                        <p class="text-gray-300 text-base sm:text-lg leading-relaxed max-w-2xl">
                            {{ $course->subtitle }}
                        </p>
                    @endif

                    <div class="flex flex-wrap items-center gap-6 pt-2 text-sm text-gray-300">
                        <div class="flex items-center gap-2">
                            @if ($course->instructor?->avatar)
                                <img src="{{ $course->instructor->avatar }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <span class="w-8 h-8 rounded-full bg-[#392c7d] flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($course->instructor?->name ?? 'I', 0, 1)) }}</span>
                            @endif
                            <span>{{ __('courses.about_instructor') }}: <strong class="text-white font-semibold">{{ $course->instructor?->name }}</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5 text-[#ffc107]">
                            <span>{{ str_repeat('★', (int) round($course->averageRating() ?: 5)) }}</span>
                            <span class="font-bold text-white">{{ $course->averageRating() ?: '5.0' }}</span>
                            <span class="text-gray-400">({{ $course->reviewCount() }} {{ __('app.common.reviews') }})</span>
                        </div>
                        <div>
                            <span>{{ $course->enrolledCount() }} {{ __('app.common.students') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content & Sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Left Overview Column -->
            <div class="lg:col-span-8 space-y-10">
                <!-- What You'll Learn -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs">
                    <h2 class="text-xl font-bold text-[#191919] mb-6">What You Will Learn</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-[#5d5d5d]">
                        <div class="flex items-start gap-3">
                            <span class="text-[#03c95a] font-bold text-base mt-0.5">✓</span>
                            <span>Build responsive websites and mobile app layouts using modern design tools</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-[#03c95a] font-bold text-base mt-0.5">✓</span>
                            <span>Master typography, grid systems, auto-layout, and color science</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-[#03c95a] font-bold text-base mt-0.5">✓</span>
                            <span>Design enterprise-grade Design Systems with token variables</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-[#03c95a] font-bold text-base mt-0.5">✓</span>
                            <span>Conduct real user research, usability testing, and wireframing</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-[#03c95a] font-bold text-base mt-0.5">✓</span>
                            <span>Create interactive high-fidelity micro-interactions and transitions</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-[#03c95a] font-bold text-base mt-0.5">✓</span>
                            <span>Export assets for front-end developer handoff with Code Connect</span>
                        </div>
                    </div>
                </div>


                <!-- Course Curriculum (DB-driven lessons) -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-[#191919]">{{ __('courses.curriculum') }}</h2>
                        <span class="text-xs text-gray-500 font-medium">{{ $course->lessons->count() }} {{ __('app.common.lessons') }} · {{ $course->duration_minutes }} {{ __('app.common.minutes') }}</span>
                    </div>

                    @if ($course->lessons->count())
                        <div class="space-y-2">
                            @foreach ($course->lessons as $lesson)
                                <div class="flex items-center justify-between bg-[#f4f6f9] rounded-xl px-4 py-3 border border-gray-200 text-sm">
                                    <span class="text-[#191919] font-medium">▶ {{ $loop->iteration }}. {{ $lesson->title }}</span>
                                    <span class="text-xs text-gray-500 whitespace-nowrap">{{ $lesson->duration_minutes }} {{ __('app.common.minutes') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400">{{ __('instructor.lessons.no_lessons') }}</p>
                    @endif
                </div>
                            <p class="text-sm text-[#6d6d6d] leading-relaxed">
                                David has over 12 years of industry experience leading design teams in Silicon Valley and Europe. He has trained over 25,000 designers worldwide.
                            </p>
                            <div class="flex items-center gap-6 pt-2 text-xs text-gray-500 font-medium">
                                <span>⭐ 4.9 Instructor Rating</span>
                                <span>👥 25,400+ Students</span>
                                <span>📚 8 Courses</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sticky Pricing Card -->
            <div class="lg:col-span-4 lg:sticky lg:top-24">
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xl space-y-6">
                    <!-- Video Preview Container -->
                    <div class="relative h-48 rounded-xl overflow-hidden bg-gray-900 group">
                        <img src="{{ $course->image ?? 'https://picsum.photos/seed/course-'.$course->id.'/640/420' }}" class="w-full h-full object-cover opacity-80" alt="{{ $course->title }}">
                    </div>

                    <!-- Price -->
                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl font-extrabold text-[#191919]">{{ $course->price > 0 ? __('app.common.currency').number_format($course->price, 2) : __('courses.price_free') }}</span>
                        @if ($course->old_price)
                            <span class="text-base text-gray-400 line-through">{{ __('app.common.currency') }}{{ number_format($course->old_price, 2) }}</span>
                        @endif
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-3">
                        @auth('student')
                            @if ($isEnrolled)
                                <a href="{{ route('student.enrolled-courses') }}" class="block w-full text-center bg-[#03c95a] hover:bg-[#02a84c] text-white font-bold py-3.5 rounded-full transition-colors shadow-md text-sm">
                                    {{ __('courses.go_to_course') }}
                                </a>
                            @else
                                <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white font-bold py-3.5 rounded-full transition-colors shadow-md text-sm">
                                        {{ $course->price > 0 ? __('courses.enroll_now') : __('courses.enroll_free') }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-[#ff4667] hover:bg-[#e03a58] text-white font-bold py-3.5 rounded-full transition-colors shadow-md text-sm">
                                {{ __('courses.guest_enroll_hint') }}
                            </a>
                        @endauth
                    </div>

                    <!-- Features List -->
                    <div class="border-t border-gray-100 pt-5 space-y-3 text-xs text-[#5d5d5d]">
                        <div class="font-bold text-[#191919] text-sm">{{ __('courses.includes') }}</div>
                        <div class="flex items-center gap-2">
                            <span>📹</span> {{ $course->lessons->count() }} {{ __('courses.on_demand_video') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span>⏱️</span> {{ __('app.common.duration') }}: {{ $course->duration_minutes }} {{ __('app.common.minutes') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span>📱</span> {{ __('app.common.level') }}: {{ __('app.levels.'.$course->level) }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span>♾️</span> {{ __('courses.full_lifetime_access') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span>🏆</span> {{ __('courses.certificate_completion') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-layouts.app>
