@props([
    'name' => 'David Benitez',
    'slug' => null,
    'role' => 'Principal Product Designer & Design Systems Certified',
    'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80',
    'rating' => null,
    'reviews' => null,
    'lessons' => '4 courses',
    'duration' => '0 Students',
    'courses' => null,
    'students' => null,
    'tag1' => null,
    'tag2' => null,
])

@php
    $displayCourses = $courses ?? $lessons ?? '4 courses';
    $displayStudents = $students ?? $duration ?? '0 Students';

    if (is_numeric($displayCourses)) {
        $coursesText = $displayCourses . ' courses';
    } else {
        $coursesText = $displayCourses;
    }

    if (is_numeric($displayStudents)) {
        $studentsText = $displayStudents . ' Students';
    } else {
        $studentsText = $displayStudents;
    }

    if (!$tag1) {
        $roleLower = strtolower($role ?? '');
        if (str_contains($roleLower, 'design') || str_contains($roleLower, 'ui') || str_contains($roleLower, 'ux') || str_contains($roleLower, 'creative')) {
            $tag1 = 'Product Design';
            $tag2 = $tag2 ?? 'UI/UX';
        } elseif (str_contains($roleLower, 'cloud') || str_contains($roleLower, 'cyber') || str_contains($roleLower, 'security')) {
            $tag1 = 'Cloud Systems';
            $tag2 = $tag2 ?? 'DevOps';
        } elseif (str_contains($roleLower, 'python') || str_contains($roleLower, 'machine') || str_contains($roleLower, 'data')) {
            $tag1 = 'Python';
            $tag2 = $tag2 ?? 'Data Science';
        } elseif (str_contains($roleLower, 'business') || str_contains($roleLower, 'coach')) {
            $tag1 = 'Business';
            $tag2 = $tag2 ?? 'Strategy';
        } else {
            $tag1 = 'Full-Stack';
            $tag2 = $tag2 ?? 'Web Dev';
        }
    }
@endphp

<div class="bg-white rounded-[22px] sm:rounded-[26px] overflow-hidden shadow-[0_8px_24px_rgba(0,0,0,0.06)] hover:shadow-[0_14px_36px_rgba(0,0,0,0.11)] border border-gray-100 transition-all duration-300 flex flex-col justify-between relative group">

    <!-- Top Area: Full-bleed photo with Organic Wave & Action Overlays -->
    <div class="relative w-full aspect-[16/13] sm:aspect-[16/12] overflow-hidden bg-gray-100">
        <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=392c7d&color=fff&size=400';">

        <!-- Top Left: Wishlist / Favorite Button -->
        <button type="button" class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3 w-8.5 h-8.5 rounded-full bg-white/95 hover:bg-white shadow-sm flex items-center justify-center text-gray-600 hover:text-[#137547] hover:scale-105 transition-all cursor-pointer z-20" aria-label="Favorite instructor">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>

        <!-- Top Right: Context Menu Dots Button -->
        <button type="button" class="absolute top-2.5 right-2.5 sm:top-3 sm:right-3 w-8.5 h-8.5 rounded-full bg-black/25 hover:bg-black/40 backdrop-blur-md shadow-sm flex items-center justify-center text-white transition-all cursor-pointer z-20" aria-label="More options">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
            </svg>
        </button>

        <!-- Wave transition from photo to card body -->
        <div class="absolute -bottom-px left-0 right-0 w-full overflow-hidden leading-none pointer-events-none z-10">
            <svg class="relative block w-full h-6 sm:h-7" viewBox="0 0 500 60" preserveAspectRatio="none">
                <!-- Soft sky blue accent wave layer on the right -->
                <path d="M0,50 C180,50 310,12 500,20 L500,60 L0,60 Z" fill="#bfdbfe" opacity="0.75"></path>
                <!-- Pure white foreground wave cutting into image -->
                <path d="M0,32 C130,52 280,18 500,38 L500,60 L0,60 Z" fill="#ffffff"></path>
            </svg>
        </div>
    </div>

    <!-- Content Area (Carefully tuned font sizes & padding) -->
    <div class="p-3.5 sm:p-4 flex flex-col justify-between flex-1 relative z-10">
        <div>
            <!-- Instructor Header: Name, Verified Badge & Certificate Medal -->
            <div class="flex items-start justify-between gap-2 mb-2.5">
                <div class="min-w-0 flex-1">
                    <!-- Name & Blue Verified Badge -->
                    <div class="flex items-center gap-1.5 min-w-0">
                        <a href="{{ route('instructors.show', $slug ?? \Illuminate\Support\Str::slug($name)) }}" class="font-bold text-[15px] sm:text-[16px] text-[#0f172a] hover:text-[#2563eb] transition-colors truncate leading-tight">
                            {{ $name }}
                        </a>
                        <!-- Blue Starburst Verified Badge with White Check -->
                        <span class="inline-flex text-[#2563eb] shrink-0" title="Verified Instructor">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>

                    <!-- Role / Designation -->
                    <p class="text-[11px] sm:text-xs text-[#475569] font-medium leading-snug line-clamp-2 mt-1 mb-2 min-h-[2.1rem]">
                        {{ $role }}
                    </p>

                    <!-- Accent Underline -->
                    <div class="w-7 h-0.5 sm:w-8 sm:h-1 bg-[#2563eb] rounded-full"></div>
                </div>

                <!-- Circular Certificate Medal Badge -->
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-[#fff7ed] via-[#fef3c7] to-[#fed7aa] flex items-center justify-center shrink-0 shadow-2xs border border-amber-200/60 ml-1">
                    <svg class="w-5 h-5 text-[#f97316]" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="9" r="5.5" stroke="#ea580c" stroke-width="1.2" fill="#f97316"/>
                        <path d="M12 6.8l.7 1.4 1.6.2-1.15 1.1.28 1.6-1.43-.75-1.43.75.28-1.6-1.15-1.1 1.6-.2z" fill="#ffffff"/>
                        <path d="M9.8 13.5L8.2 19l3.8-1.7 3.8 1.7-1.6-5.5" stroke="#ea580c" stroke-width="1.2" stroke-linejoin="round" fill="#f97316"/>
                    </svg>
                </div>
            </div>

            <!-- Tag Pills Row -->
            <div class="flex flex-wrap items-center gap-1.5 mb-3">
                <!-- Tag 1 (e.g. Product Design) -->
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#eff6ff] text-[#1d4ed8] text-[10px] sm:text-[11px] font-semibold border border-blue-100/80">
                    <svg class="w-3 h-3 text-[#2563eb]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
                    </svg>
                    <span>{{ $tag1 }}</span>
                </span>

                <!-- Tag 2 (e.g. UI/UX) -->
                @if($tag2)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#ecfdf5] text-[#047857] text-[10px] sm:text-[11px] font-semibold border border-emerald-100/80">
                        <svg class="w-3 h-3 text-[#059669]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-5.25 6.557c0 1.458.736 2.748 1.865 3.518"/>
                        </svg>
                        <span>{{ $tag2 }}</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Footer Stats: Courses Published & Students Enrolled -->
        <div class="pt-2.5 border-t border-gray-100 flex items-center justify-between gap-1.5">
            <!-- Courses Stat -->
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <div class="w-8 h-8 sm:w-8.5 sm:h-8.5 rounded-full bg-[#eff6ff] text-[#2563eb] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-[#2563eb]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="font-bold text-[11px] sm:text-xs text-[#0f172a] leading-tight block truncate">
                        {{ $coursesText }}
                    </span>
                    <span class="text-[10px] text-gray-400 font-medium leading-tight block">
                        Published
                    </span>
                </div>
            </div>

            <!-- Vertical Divider -->
            <div class="h-6 w-px bg-gray-200 shrink-0"></div>

            <!-- Students Stat -->
            <div class="flex items-center gap-2 min-w-0 flex-1 pl-1">
                <div class="w-8 h-8 sm:w-8.5 sm:h-8.5 rounded-full bg-[#ecfdf5] text-[#059669] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-[#059669]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="font-bold text-[11px] sm:text-xs text-[#0f172a] leading-tight block truncate">
                        {{ $studentsText }}
                    </span>
                    <span class="text-[10px] text-gray-400 font-medium leading-tight block">
                        Enrolled
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
