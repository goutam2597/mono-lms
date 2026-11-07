<x-layouts.dashboard title="Instructor Reviews - Dreams LMS" active="reviews" userType="instructor">

    <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs space-y-6">
        <div>
            <h2 class="text-xl font-bold text-[#191919]">Student Reviews (200)</h2>
            <p class="text-xs text-[#6d6d6d] mt-0.5">Average instructor score: <strong class="text-gray-900 font-bold">4.9 / 5.0</strong> based on all verified reviews.</p>
        </div>

        <div class="space-y-4">
            @php
                $reviews = [
                    ['name' => 'Eugene Andre', 'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80', 'course' => 'Complete Python Bootcamp', 'rating' => 5, 'date' => '2 days ago', 'comment' => 'One of the best structured courses I have ever taken! The explanations on asynchronous loops and generators finally made complete sense to me.'],
                    ['name' => 'Sarah Connor', 'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80', 'course' => 'Laravel 12 API Development', 'rating' => 5, 'date' => '1 week ago', 'comment' => 'Rolands is an exceptional instructor. Every concept was demonstrated with real architectural tests and production-ready code.'],
                    ['name' => 'Alex Murphy', 'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80', 'course' => 'Docker & Kubernetes Deep Dive', 'rating' => 4, 'date' => '2 weeks ago', 'comment' => 'Very comprehensive course. Would love to see more examples on multi-tenant databases in the future!'],
                ];
            @endphp

            @foreach($reviews as $rev)
                <div class="p-5 rounded-xl border border-gray-100 bg-gray-50 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $rev['avatar'] }}" alt="{{ $rev['name'] }}" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <div class="font-bold text-sm text-[#191919]">{{ $rev['name'] }}</div>
                                <div class="text-[11px] text-gray-500">Course: <span class="font-medium text-[#392c7d]">{{ $rev['course'] }}</span></div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-[#ffc107]">★ ★ ★ ★ ★</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $rev['date'] }}</div>
                        </div>
                    </div>
                    <p class="text-xs text-[#5d5d5d] leading-relaxed">{{ $rev['comment'] }}</p>
                    <div class="pt-2 flex items-center gap-4 text-xs">
                        <button class="text-[#ff4667] font-semibold hover:underline">Reply to Student</button>
                        <span class="text-gray-300">•</span>
                        <button class="text-gray-400 hover:text-gray-600">Helpful (12)</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-layouts.dashboard>
