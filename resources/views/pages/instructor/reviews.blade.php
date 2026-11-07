<x-layouts.dashboard :title="__('instructor.reviews.title').' - Mono LMS'" active="reviews" userType="instructor">

    <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs space-y-6">
        <div>
            <h2 class="text-xl font-bold text-[#191919]">{{ __('instructor.reviews.title') }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ __('instructor.reviews.count', ['count' => $reviews->total()]) }}</p>
        </div>

        <div class="space-y-4">
            @forelse ($reviews as $review)
                <div class="border border-gray-100 rounded-xl p-5 hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-[#392c7d]/10 text-[#392c7d] font-bold flex items-center justify-center">{{ strtoupper(substr($review->student?->name ?? 'S', 0, 1)) }}</span>
                            <div>
                                <p class="font-semibold text-[#191919] text-sm">{{ $review->student?->name ?? '—' }}</p>
                                <p class="text-xs text-gray-400">{{ \Illuminate\Support\Str::limit($review->course?->title, 40) }} · {{ $review->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <span class="text-[#ffc107] font-semibold whitespace-nowrap">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    </div>
                    @if ($review->comment)
                        <p class="text-sm text-[#5d5d5d] leading-relaxed pl-[52px]">{{ $review->comment }}</p>
                    @endif
                </div>
            @empty
                <p class="py-10 text-center text-gray-400 text-sm">{{ __('instructor.reviews.no_reviews') }}</p>
            @endforelse
        </div>

        <div>{{ $reviews->links() }}</div>
    </div>

</x-layouts.dashboard>
