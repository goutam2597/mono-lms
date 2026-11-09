<x-layouts.app :title="($instructor->name ?? 'Instructor') . ' - Instructor Profile'" active="instructors">

    @php
        $avatarUrl = $instructor->avatarUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&background=392c7d&color=fff&size=400';
        $rating = $instructor->reviews()->avg('rating');
        $reviewsCount = $instructor->reviews()->count();
        $studentsCount = $instructor->enrollments()->count();
        $coursesCount = $courses->count();
    @endphp

    <!-- Instructor Hero Header -->
    <div class="bg-[#344c3d] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="relative w-32 h-32 sm:w-40 sm:h-40 rounded-2xl overflow-hidden border-4 border-white/20 shadow-xl shrink-0 bg-slate-800">
                    <img src="{{ $avatarUrl }}"
                         alt="{{ $instructor->name }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($instructor->name) }}&background=392c7d&color=fff&size=400';">
                </div>
                <div class="space-y-3 text-center md:text-left">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white">{{ $instructor->name }}</h1>
                        <span class="bg-[#738a6e] text-white text-xs font-bold px-3 py-1 rounded-full">Top Rated</span>
                    </div>
                    <p class="text-gray-200 text-sm sm:text-base font-medium">{{ $instructor->designation ?? 'Lead Instructor & Mentor' }}</p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 text-xs sm:text-sm text-gray-200 pt-2">
                        <div>⭐ <strong class="text-white">{{ $rating ? number_format($rating, 1) : '5.0' }}</strong> Rating ({{ $reviewsCount }} Reviews)</div>
                        <div>👥 <strong class="text-white">{{ number_format($studentsCount) }}</strong> Students</div>
                        <div>📚 <strong class="text-white">{{ $coursesCount }}</strong> Active Courses</div>
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
                    <div class="text-sm text-[#5d5d5d] leading-relaxed whitespace-pre-line">
                        {{ $instructor->bio ?: 'A passionate instructor and industry expert sharing knowledge on Mono LMS.' }}
                    </div>
                </div>

                <!-- Courses by this instructor -->
                <div>
                    <h3 class="text-2xl font-bold text-[#191919] mb-6">Courses by {{ $instructor->name }} ({{ $coursesCount }})</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse ($courses as $c)
                            <x-course-card
                                :slug="$c->slug"
                                :title="$c->title"
                                :category="$c->category?->name ?? 'Course'"
                                :instructor="$instructor->name"
                                :instructorAvatar="$instructor->avatarUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&background=344C3D&color=DCE7DA'"
                                :price="$c->price > 0 ? '$'.number_format($c->price, 2) : 'Free'"
                                :rating="(string) ($c->averageRating() ?: '5.0')"
                                :reviews="(string) $c->reviewCount()"
                                :image="$c->image ?? 'https://picsum.photos/seed/course-'.$c->id.'/600/400'"
                            />
                        @empty
                            <p class="col-span-full py-8 text-center text-slate-400">No active courses published yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Contact / Details -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs space-y-4">
                    <h4 class="font-bold text-[#191919] text-base">Contact Mentor</h4>
                    <p class="text-xs text-[#6d6d6d]">Have questions regarding a course? Reach out directly to discuss mentorship and syllabus queries.</p>
                    <a href="mailto:{{ $instructor->email }}" class="block text-center w-full bg-[#344c3d] hover:bg-[#25372c] text-white font-semibold text-xs py-3 rounded-xl transition-colors shadow-sm">
                        Send Email
                    </a>
                    <div class="border-t border-gray-100 pt-4 space-y-2 text-xs text-gray-600">
                        <div>✉️ <strong>Email:</strong> {{ $instructor->email }}</div>
                        @if ($instructor->phone)
                            <div>📞 <strong>Phone:</strong> {{ $instructor->phone }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
