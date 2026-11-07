<x-layouts.app title="Instructor List - Dreams LMS" active="instructors">

    <div class="bg-[#f4f6f9] py-10 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#191919]">{{ __('instructors.list') }}</h1>
                <nav class="flex items-center gap-2 text-xs text-[#6d6d6d] mt-2">
                    <a href="{{ route('home') }}" class="hover:text-[#ff4667]">Home</a>
                    <span>/</span>
                    <a href="{{ route('instructors.grid') }}" class="hover:text-[#ff4667]">Instructors</a>
                    <span>/</span>
                    <span class="text-[#ff4667] font-medium">{{ __('instructors.list') }}</span>
                </nav>
            </div>
            <div class="text-xs text-[#888888] font-medium">
                Figma Node: <code class="bg-white px-2 py-1 rounded border border-gray-200 font-mono text-[#191919]">311:35028</code>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <div class="space-y-6">

            @foreach($instructors as $inst)
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow flex flex-col md:flex-row gap-6 items-start md:items-center">
                    <img src="{{ $inst->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($inst->name).'&background=392c7d&color=fff&size=200' }}" alt="{{ $inst->name }}" class="w-28 h-28 rounded-2xl object-cover shrink-0">
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[#ffc107]">{{ str_repeat('★', 5) }}</span>
                            <span class="text-xs font-bold text-gray-800">{{ $inst->courses_count }} {{ __('instructors.courses_count', ['count' => $inst->courses_count]) }}</span>
                        </div>
                        <a href="{{ route('instructors.show', IlluminateSupportStr::slug($inst->name)) }}" class="block text-xl font-bold text-[#191919] hover:text-[#ff4667] transition-colors">
                            {{ $inst->name }}
                        </a>
                        <p class="text-xs text-[#ff4667] font-semibold">{{ $inst->designation }}</p>
                        <p class="text-sm text-[#6d6d6d]">{{ IlluminateSupportStr::limit($inst->bio, 140) }}</p>
                        <div class="flex items-center gap-6 pt-1 text-xs text-gray-500">
                            
                            
                        </div>
                    </div>
                    <div class="w-full md:w-auto">
                        <a href="{{ route('instructors.show', IlluminateSupportStr::slug($inst->name)) }}" class="block text-center bg-[#08131e] hover:bg-[#ff4667] text-white text-xs font-semibold px-5 py-2.5 rounded-full transition-colors whitespace-nowrap">
                            {{ __('instructors.contact') }} →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-layouts.app>
