@props([
    'id' => 1,
    'slug' => null,
    'title' => 'Information About UI/UX Design Degree',
    'category' => 'Web Design',
    'instructor' => 'Rolands Granger',
    'instructorAvatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80',
    'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&auto=format&fit=crop&q=80',
    'rating' => '5.0',
    'reviews' => '1',
    'price' => '$650.00',
    'oldPrice' => '$900.00',
    'lessons' => '24',
    'duration' => '6h 30m',
    'level' => 'Beginner',
])

@php
    $formattedLevel = ucfirst($level ?? 'Beginner');

    $formattedDuration = $duration ?? '6h 30m';
    if (is_numeric($duration)) {
        $hrs = floor($duration / 60);
        $mins = $duration % 60;
        $formattedDuration = ($hrs > 0 ? $hrs . 'h ' : '') . ($mins > 0 ? $mins . 'm' : ($hrs === 0 ? '0m' : ''));
    } elseif (is_string($duration) && preg_match('/^(\d+)\s*(?:minutes|min|m)?$/i', trim($duration), $matches)) {
        $totalMins = (int) $matches[1];
        $hrs = floor($totalMins / 60);
        $mins = $totalMins % 60;
        $formattedDuration = ($hrs > 0 ? $hrs . 'h ' : '') . ($mins > 0 ? $mins . 'm' : '');
    }

    $rawLesson = (int) preg_replace('/[^0-9]/', '', (string)$lessons);
    $formattedLessons = ($rawLesson > 0 ? $rawLesson : 24) . ' ' . __('app.common.lessons', ['count' => $rawLesson ?: 24]);
    if (!str_contains(strtolower($formattedLessons), 'lesson')) {
        $formattedLessons .= ' Lessons';
    }
@endphp

<div class="bg-white rounded-[22px] sm:rounded-[26px] overflow-hidden shadow-[0_8px_24px_rgba(19,117,71,0.06)] hover:shadow-[0_14px_36px_rgba(19,117,71,0.12)] border border-gray-100 transition-all duration-300 flex flex-col justify-between relative group">

    <!-- Top Area: Full-bleed image with Organic Waves & Badges -->
    <div class="relative w-full aspect-[16/10] overflow-hidden bg-gray-100">
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

        <!-- Top Left: Wishlist Button -->
        <button type="button" class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3 w-8 h-8 rounded-full bg-white/95 hover:bg-white shadow-sm flex items-center justify-center text-gray-600 hover:text-[#137547] hover:scale-105 transition-all cursor-pointer z-20" aria-label="Add to wishlist">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>

        <!-- Top Right: Category Badge with Laptop Icon -->
        <div class="absolute top-2.5 right-2.5 sm:top-3 sm:right-3 z-20 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#137547] text-white text-[11px] font-semibold shadow-sm backdrop-blur-xs">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M4 5h16a1 1 0 011 1v8a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1z"/>
            </svg>
            <span>{{ $category }}</span>
        </div>

        <!-- Wave transition from photo to card body -->
        <div class="absolute -bottom-px left-0 right-0 w-full overflow-hidden leading-none pointer-events-none z-10">
            <svg class="relative block w-full h-6 sm:h-7" viewBox="0 0 500 60" preserveAspectRatio="none">
                <!-- Mint green accent wave layer on the right -->
                <path d="M0,50 C180,50 310,12 500,20 L500,60 L0,60 Z" fill="#bbf2d2" opacity="0.65"></path>
                <!-- Pure white foreground wave cutting into image -->
                <path d="M0,30 C120,52 280,18 500,38 L500,60 L0,60 Z" fill="#ffffff"></path>
            </svg>
        </div>
    </div>

    <!-- Content Area (Scaled down padding & typography) -->
    <div class="p-3.5 sm:p-4 flex flex-col justify-between flex-1 relative z-10">
        <div>
            <!-- Instructor & Rating Row -->
            <div class="flex items-center justify-between gap-2 mb-2">
                <!-- Instructor Info -->
                <div class="flex items-center gap-2 min-w-0">
                    <img src="{{ $instructorAvatar }}" alt="{{ $instructor }}" class="w-8 h-8 sm:w-8.5 sm:h-8.5 rounded-full object-cover shrink-0 border-2 border-white ring-2 ring-[#eaf5ee] shadow-2xs">
                    <div class="min-w-0">
                        <span class="block font-bold text-xs sm:text-[13px] text-[#0f172a] hover:text-[#137547] transition-colors truncate leading-tight">
                            {{ $instructor }}
                        </span>
                        <span class="block text-[10px] text-gray-400 font-medium leading-tight mt-0.5">
                            Instructor
                        </span>
                    </div>
                </div>

                <!-- Rating Badge (Compact Pill) -->
                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#e8f7ee] text-[#137547] shrink-0">
                    <svg class="w-3 h-3 fill-[#f59e0b] text-[#f59e0b] shrink-0" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="font-bold text-[11px] text-[#0f172a] leading-none">{{ $rating }}</span>
                    <span class="text-[10px] text-[#137547] font-medium leading-none">({{ $reviews }} {{ __('app.common.reviews') }})</span>
                </div>
            </div>

            <!-- Course Title -->
            <a href="{{ route('courses.show', $slug ?? $id) }}" class="block font-bold text-[14px] sm:text-[15px] text-[#0f172a] leading-snug hover:text-[#137547] transition-colors line-clamp-2 min-h-[2.4rem] mt-2 mb-2">
                {{ $title }}
            </a>

            <!-- Metadata Row: Level | Duration | Lessons -->
            <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] font-semibold text-[#1e293b] py-1.5 mb-1 overflow-hidden">
                <!-- Level -->
                <div class="flex items-center gap-1 shrink-0">
                    <svg class="w-3.5 h-3.5 text-[#0f172a]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                    </svg>
                    <span class="truncate max-w-[70px] sm:max-w-none">{{ $formattedLevel }}</span>
                </div>

                <div class="h-3 w-px bg-gray-200 shrink-0"></div>

                <!-- Duration -->
                <div class="flex items-center gap-1 shrink-0">
                    <svg class="w-3.5 h-3.5 text-[#0f172a]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $formattedDuration }}</span>
                </div>

                <div class="h-3 w-px bg-gray-200 shrink-0"></div>

                <!-- Lessons -->
                <div class="flex items-center gap-1 shrink-0">
                    <svg class="w-3.5 h-3.5 text-[#0f172a]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="4" width="20" height="16" rx="3"/>
                        <path d="M10 8.5l6 3.5-6 3.5V8.5z" fill="currentColor"/>
                    </svg>
                    <span>{{ $formattedLessons }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Row: Price + Enroll Now Button -->
        <div class="flex items-center justify-between gap-2 pt-3 border-t border-gray-100 relative z-10">
            <!-- Price Section -->
            <div class="flex items-baseline min-w-0">
                <span class="text-[#137547] font-extrabold text-base sm:text-lg tracking-tight leading-none">{{ $price }}</span>
                @if($oldPrice)
                    <span class="text-[11px] sm:text-xs text-gray-400 line-through font-medium leading-none ml-1.5">{{ $oldPrice }}</span>
                @endif
            </div>

            <!-- Action Button: Enroll Now Pill (Increased comfortable height) -->
            <a href="{{ route('courses.show', $slug ?? $id) }}" class="group/btn inline-flex items-center justify-center gap-1.5 h-9 sm:h-9.5 px-3.5 sm:px-4 bg-gradient-to-r from-[#178546] to-[#0f6834] hover:from-[#137547] hover:to-[#0a5229] text-white text-xs sm:text-[13px] font-bold rounded-full shadow-[0_4px_14px_rgba(23,133,70,0.30)] hover:shadow-[0_6px_18px_rgba(23,133,70,0.40)] active:scale-[0.98] transition-all duration-200 leading-none shrink-0 cursor-pointer">
                <span>{{ __('courses.enroll_now') }}</span>
                <svg class="w-3.5 h-3.5 text-white shrink-0 transition-transform duration-200 group-hover/btn:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Bottom-Left Decorative Mint Wave Watermark -->
    <div class="absolute bottom-0 left-0 w-28 h-16 overflow-hidden pointer-events-none z-0">
        <svg class="w-full h-full text-[#e8f7ee] fill-current" viewBox="0 0 140 100" preserveAspectRatio="none">
            <path d="M0,100 L0,30 Q40,40 70,70 T140,80 L140,100 Z" opacity="0.85"></path>
        </svg>
    </div>
</div>
