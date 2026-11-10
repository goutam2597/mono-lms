<x-layouts.dashboard :title="__('instructor.reviews.title').' - Mono LMS'" active="reviews" userType="instructor">

    <div class="space-y-6">
        <!-- Top Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Instructor Rating</span>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-2xl font-extrabold text-slate-900">{{ number_format($avgRating ?? 0, 1) }}</p>
                        <span class="text-amber-400 text-lg">★</span>
                    </div>
                    <span class="text-[11px] text-slate-400">Average across your courses</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-lg">
                    ★
                </div>
            </div>

            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Reviews</span>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalReviews ?? 0) }}</p>
                    <span class="text-[11px] text-slate-400">Approved student reviews</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">5-Star Feedback</span>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($fiveStarCount ?? 0) }}</p>
                    <span class="text-[11px] text-slate-400">Perfect score ratings</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Reviews Card -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl shadow-xs overflow-hidden">
            <!-- Header & Filter Bar -->
            <div class="p-5 border-b border-[#e7e7e7] flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-[#191919]">{{ __('instructor.reviews.title') }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Read feedback from enrolled students and post official responses</p>
                </div>

                <form method="GET" action="{{ route('instructor.reviews') }}" class="flex flex-wrap items-center gap-2 text-xs">
                    <select name="course_id" onchange="this.form.submit()" class="bg-white border border-gray-200 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 focus:outline-none focus:border-[#344C3D]">
                        <option value="">All My Courses</option>
                        @foreach ($courses as $c)
                            <option value="{{ $c->id }}" @selected(request('course_id') == $c->id)>{{ \Illuminate\Support\Str::limit($c->title, 25) }}</option>
                        @endforeach
                    </select>

                    <select name="rating" onchange="this.form.submit()" class="bg-white border border-gray-200 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 focus:outline-none focus:border-[#344C3D]">
                        <option value="">All Ratings</option>
                        <option value="5" @selected(request('rating') == '5')>★★★★★ (5 Stars)</option>
                        <option value="4" @selected(request('rating') == '4')>★★★★☆ (4 Stars)</option>
                        <option value="3" @selected(request('rating') == '3')>★★★☆☆ (3 Stars)</option>
                        <option value="2" @selected(request('rating') == '2')>★★☆☆☆ (2 Stars)</option>
                        <option value="1" @selected(request('rating') == '1')>★☆☆☆☆ (1 Star)</option>
                    </select>

                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Search feedback..."
                           class="bg-white border border-gray-200 px-3 py-1.5 rounded-lg text-xs focus:outline-none focus:border-[#344C3D] w-36 sm:w-44">

                    <button type="submit" class="px-3 py-1.5 bg-[#08131e] text-white font-semibold rounded-lg text-xs">Filter</button>
                    @if (request()->hasAny(['course_id', 'rating', 'q']))
                        <a href="{{ route('instructor.reviews') }}" class="text-rose-600 text-xs font-semibold hover:underline">Reset</a>
                    @endif
                </form>
            </div>

            <!-- List of Reviews -->
            <div class="divide-y divide-gray-100">
                @forelse ($reviews as $review)
                    <div class="p-5 sm:p-6 space-y-3 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[var(--theme-primary,#344c3d)] text-white font-bold flex items-center justify-center shrink-0 text-sm">
                                    {{ strtoupper(substr($review->student?->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-[#191919] text-sm">{{ $review->student?->name ?? 'Verified Student' }}</h4>
                                    <p class="text-xs text-gray-400">
                                        Course: <span class="font-semibold text-slate-700">{{ $review->course?->title ?? 'Course' }}</span> · {{ $review->created_at->format('M d, Y') }} ({{ $review->created_at->diffForHumans() }})
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-[#ffc107] font-semibold text-sm whitespace-nowrap">
                                <span>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                <span class="text-slate-700 text-xs font-bold ml-1">({{ $review->rating }}.0)</span>
                            </div>
                        </div>

                        @if ($review->comment)
                            <p class="text-sm text-slate-700 leading-relaxed pl-13">
                                {{ $review->comment }}
                            </p>
                        @endif

                        <!-- Instructor Response Box -->
                        <div class="pl-13">
                            @if ($review->instructor_reply)
                                <div class="border-l-2 border-[var(--theme-primary,#344c3d)] bg-slate-50 p-3.5 rounded-r-xl space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-900">Your Response</span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] px-1.5 py-0.5 rounded">
                                                Public
                                            </span>
                                            @if ($review->replied_at)
                                                <span class="text-[10px] text-slate-400">• {{ $review->replied_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                        <button type="button"
                                                onclick="document.getElementById('reply-form-{{ $review->id }}').classList.toggle('hidden')"
                                                class="text-xs text-[var(--theme-primary,#344c3d)] hover:underline font-semibold">
                                            Edit Response
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-700 leading-relaxed">
                                        {{ $review->instructor_reply }}
                                    </p>
                                </div>
                            @else
                                <button type="button"
                                        onclick="document.getElementById('reply-form-{{ $review->id }}').classList.toggle('hidden')"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    <span>Reply to this student</span>
                                </button>
                            @endif

                            <!-- Toggleable Reply Form -->
                            <form id="reply-form-{{ $review->id }}"
                                  method="POST"
                                  action="{{ route('instructor.reviews.reply', $review) }}"
                                  class="mt-3 bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-2.5 {{ $review->instructor_reply ? 'hidden' : 'hidden' }}">
                                @csrf
                                <label for="reply-input-{{ $review->id }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    {{ $review->instructor_reply ? 'Update Your Response' : 'Write an Official Response' }}
                                </label>
                                <textarea id="reply-input-{{ $review->id }}"
                                          name="instructor_reply"
                                          rows="2"
                                          placeholder="Thank the student, provide extra tips, or address their feedback..."
                                          class="w-full bg-white border border-slate-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">{{ old('instructor_reply', $review->instructor_reply ?? '') }}</textarea>
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            onclick="document.getElementById('reply-form-{{ $review->id }}').classList.add('hidden')"
                                            class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-1.5 rounded-lg bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-xs">
                                        Post Response
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-400 text-sm">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        <p class="font-semibold text-slate-600">No reviews found</p>
                        <p class="text-xs text-slate-400 mt-1">When students complete and review your courses, their verified ratings will appear here.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-5 py-4 border-t border-[#e7e7e7]">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

</x-layouts.dashboard>
