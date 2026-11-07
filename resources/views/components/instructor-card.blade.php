@props([
    'name' => 'Rolands Granger',
    'slug' => null,
    'role' => 'Full-Stack Developer',
    'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80',
    'rating' => '4.9',
    'reviews' => '200',
    'lessons' => '12+ Lessons',
    'duration' => '169hr 20min'
])

<div class="bg-white border border-[#e7e7e7] shadow-[0px_4px_12px_rgba(212,212,212,0.25)] hover:shadow-[0px_8px_20px_rgba(0,0,0,0.08)] transition-all duration-300 flex flex-col p-5 rounded-[10px] group">
    <!-- Image with Favorite Button -->
    <div class="relative w-full h-44 rounded-lg overflow-hidden mb-4 bg-gray-100">
        <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        <button class="absolute top-3 left-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-gray-500 hover:text-[#ff4667] hover:bg-white shadow-sm transition-all" aria-label="Favorite instructor">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </button>
    </div>

    <!-- Instructor Info -->
    <div class="flex flex-col gap-1.5 mb-3">
        <!-- Rating -->
        <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#ffc107]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <span class="text-xs text-[#6d6d6d] font-medium">{{ $rating }} ({{ $reviews }} Reviews)</span>
        </div>
        <!-- Name -->
        <a href="{{ route('instructors.show', $slug ?? \Illuminate\Support\Str::slug($name)) }}" class="font-bold text-[18px] text-[#191919] hover:text-[#ff4667] transition-colors">
            {{ $name }}
        </a>
        <span class="text-xs text-[#6d6d6d]">{{ $role }}</span>
    </div>

    <!-- Divider -->
    <div class="w-full h-px bg-gray-100 my-2"></div>

    <!-- Stats Info Row -->
    <div class="flex items-center justify-between text-xs text-[#6d6d6d] pt-1">
        <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#392c7d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>{{ $lessons }}</span>
        </div>
        <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#ff4667]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $duration }}</span>
        </div>
    </div>
</div>
