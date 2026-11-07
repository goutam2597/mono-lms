@props([
    'id' => 1,
    'title' => 'Information About UI/UX Design Degree',
    'category' => 'Design',
    'instructor' => 'David Benitez',
    'instructorAvatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
    'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80',
    'rating' => '4.9',
    'reviews' => '200',
    'price' => '$120',
    'oldPrice' => null,
    'lessons' => '12',
    'duration' => '9hr 30min'
])

<div class="bg-white border border-[#e7e7e7] shadow-[0px_4px_12px_rgba(212,212,212,0.25)] hover:shadow-[0px_8px_20px_rgba(0,0,0,0.08)] transition-all duration-300 flex flex-col justify-between p-5 rounded-[10px] group">
    <div>
        <!-- Image Container -->
        <div class="relative w-full h-44 rounded-lg overflow-hidden mb-4 bg-gray-100">
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            <!-- Wishlist Button -->
            <button class="absolute top-3 left-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-gray-500 hover:text-[#ff4667] hover:bg-white shadow-sm transition-all" aria-label="Add to wishlist">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
        </div>

        <!-- Instructor & Category Row -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <img src="{{ $instructorAvatar }}" alt="{{ $instructor }}" class="w-6 h-6 rounded-full object-cover">
                <span class="text-sm text-[#6d6d6d] font-normal">{{ $instructor }}</span>
            </div>
            <span class="bg-[#f4f6f9] text-[#191919] text-xs font-medium px-2.5 py-1 rounded-full">{{ $category }}</span>
        </div>

        <!-- Course Title -->
        <a href="{{ route('courses.detail') }}" class="block font-bold text-[17px] text-[#191919] leading-snug mb-3 hover:text-[#ff4667] transition-colors line-clamp-2">
            {{ $title }}
        </a>

        <!-- Rating & Reviews -->
        <div class="flex items-center gap-1.5 mb-4">
            <svg class="w-4 h-4 text-[#ffc107]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <span class="text-sm text-[#6d6d6d] font-medium">{{ $rating }}</span>
            <span class="text-sm text-[#888888]">({{ $reviews }} Reviews)</span>
        </div>
    </div>

    <!-- Price & Action Button -->
    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
        <div class="flex items-baseline gap-1.5">
            <span class="text-[#ff4667] font-bold text-lg">{{ $price }}</span>
            @if($oldPrice)
                <span class="text-xs text-gray-400 line-through">{{ $oldPrice }}</span>
            @endif
        </div>
        <a href="{{ route('courses.detail') }}" class="bg-[#08131e] hover:bg-[#ff4667] text-white text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1 transition-colors">
            <span>View Course</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>
