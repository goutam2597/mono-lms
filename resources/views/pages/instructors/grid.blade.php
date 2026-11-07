<x-layouts.app title="Instructor Grid - Dreams LMS" active="instructors">

    <!-- Page Title Header (Figma: 311:27960) -->
    <div class="bg-[#f4f6f9] py-10 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#191919]">{{ __('instructors.grid') }}</h1>
                <nav class="flex items-center gap-2 text-xs text-[#6d6d6d] mt-2">
                    <a href="{{ route('home') }}" class="hover:text-[#ff4667]">Home</a>
                    <span>/</span>
                    <a href="{{ route('instructors.grid') }}" class="hover:text-[#ff4667]">Instructors</a>
                    <span>/</span>
                    <span class="text-[#ff4667] font-medium">{{ __('instructors.grid') }}</span>
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
                {{ __('instructors.results_for', ['count' => $instructors->total()]) }}
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

        <!-- Instructors Grid (DB-driven) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($instructors as $instructor)
                <x-instructor-card
                    :name="$instructor->name"
                    :role="$instructor->designation ?? ''"
                    :image="$instructor->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&background=392c7d&color=fff&size=400'"
                    :lessons="$instructor->courses_count.' '.__('app.common.lessons')"
                    :duration="$instructor->courses_count.' '.__('instructors.courses_count', ['count' => $instructor->courses_count])"
                />
            @empty
                <p class="col-span-full py-10 text-center text-gray-400">{{ __('instructors.no_instructors') }}</p>
            @endforelse
        </div>

        <div class="mt-10">{{ $instructors->links() }}</div>
    </div>

</x-layouts.app>
