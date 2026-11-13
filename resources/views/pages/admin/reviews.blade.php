<x-layouts.dashboard :title="__('admin.reviews.title').' - Mono LMS'" active="reviews" userType="admin">

    <div class="space-y-6">
        <!-- Top Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Reviews</span>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($counts['all']) }}</p>
                    <span class="text-[11px] text-slate-400">All student feedback</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Pending Moderation</span>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ number_format($counts['pending']) }}</p>
                    <span class="text-[11px] text-amber-600/80 font-medium">Requires administrator review</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Approved & Live</span>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($counts['approved']) }}</p>
                    <span class="text-[11px] text-slate-400">Published on course pages</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Platform Rating</span>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-2xl font-extrabold text-slate-900">{{ number_format($counts['avg_rating'], 1) }}</p>
                        <span class="text-amber-400 text-lg">★</span>
                    </div>
                    <span class="text-[11px] text-slate-400">Across approved reviews</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-lg">
                    ★
                </div>
            </div>
        </div>

        <!-- Main Card with Filters and Table -->
        <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
            <!-- Header & Filter Bar -->
            <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-[#191919] text-base">{{ __('admin.reviews.title') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Moderate learner feedback, publish star ratings, and reply to reviews</p>
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-1.5 flex-wrap">
                    @php
                        $statusTabs = [
                            '' => ['label' => 'All Reviews', 'count' => $counts['all']],
                            'pending' => ['label' => 'Pending Moderation', 'count' => $counts['pending']],
                            'approved' => ['label' => 'Approved', 'count' => $counts['approved']],
                            'rejected' => ['label' => 'Rejected', 'count' => $counts['rejected']],
                        ];
                    @endphp
                    @foreach ($statusTabs as $k => $tab)
                        @php $isActive = (string)request('status') === (string)$k; @endphp
                        <a href="{{ route('admin.reviews.index', array_filter(['status' => $k, 'rating' => request('rating'), 'q' => request('q')])) }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-none border transition-colors {{ $isActive ? 'bg-[#08131e] border-[#08131e] text-white shadow-xs' : 'bg-white border-slate-200 text-slate-700 hover:border-slate-400' }}">
                            <span>{{ $tab['label'] }}</span>
                            @if ($tab['count'] > 0)
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $isActive ? 'bg-white/20 text-white' : ($k === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $tab['count'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Search & Rating Sub-filters -->
            <div class="px-5 py-3 bg-slate-50/70 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif

                    <div class="relative flex-1 min-w-[200px] max-w-md">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Search by student, email, course title, or comment..."
                               class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 pl-8 text-xs focus:outline-none focus:border-[#344C3D]">
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <select name="rating"
                            onchange="this.form.submit()"
                            class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 focus:outline-none focus:border-[#344C3D]">
                        <option value="">All Star Ratings</option>
                        <option value="5" @selected(request('rating') == '5')>★★★★★ (5 Stars)</option>
                        <option value="4" @selected(request('rating') == '4')>★★★★☆ (4 Stars)</option>
                        <option value="3" @selected(request('rating') == '3')>★★★☆☆ (3 Stars)</option>
                        <option value="2" @selected(request('rating') == '2')>★★☆☆☆ (2 Stars)</option>
                        <option value="1" @selected(request('rating') == '1')>★☆☆☆☆ (1 Star)</option>
                    </select>

                    <button type="submit" class="px-3 py-1.5 bg-[#08131e] text-white font-semibold uppercase tracking-wider text-[11px] hover:bg-slate-800 transition-colors">
                        Filter
                    </button>

                    @if (request()->hasAny(['q', 'rating', 'status']))
                        <a href="{{ route('admin.reviews.index') }}" class="text-xs text-rose-600 hover:underline font-medium">
                            Reset Filters
                        </a>
                    @endif
                </form>

                <div class="text-[11px] text-slate-500 font-medium">
                    Showing {{ $reviews->firstItem() ?? 0 }} - {{ $reviews->lastItem() ?? 0 }} of {{ $reviews->total() }} reviews
                </div>
            </div>

            <!-- Bulk Toolbar -->
            <div class="px-5 py-2.5 bg-white border-b border-slate-100 flex items-center justify-between gap-4 text-xs">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" id="check-all" class="control" aria-label="Select All">
                        <span class="text-xs font-semibold text-slate-700">Select All</span>
                    </label>

                    <span class="text-slate-300">|</span>

                    <span class="text-slate-500 text-[11px]" id="selected-counter">0 selected</span>
                </div>

                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.reviews.bulk-approve') }}" id="bulk-approve-form">
                        @csrf
                        <button type="submit" class="bulk-submit-btn text-xs font-semibold px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-none disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                            ✓ Bulk Approve
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.reviews.bulk-reject') }}" id="bulk-reject-form">
                        @csrf
                        <button type="submit" class="bulk-submit-btn text-xs font-semibold px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded-none disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                            ✕ Bulk Reject
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.reviews.bulk-destroy') }}" id="bulk-delete-form" onsubmit="return confirm('Delete selected reviews permanently?')">
                        @csrf
                        <button type="submit" class="bulk-submit-btn text-xs font-semibold px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-none disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                            Trash Selected
                        </button>
                    </form>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-[10px] uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-100">
                            <th class="w-10 px-5 py-3"></th>
                            <th class="px-5 py-3 font-semibold">{{ __('admin.reviews.student') }}</th>
                            <th class="px-5 py-3 font-semibold">{{ __('admin.reviews.course') }}</th>
                            <th class="px-5 py-3 font-semibold">{{ __('app.common.rating') }}</th>
                            <th class="px-5 py-3 font-semibold">{{ __('admin.reviews.comment') }}</th>
                            <th class="px-5 py-3 font-semibold">{{ __('app.common.status') }}</th>
                            <th class="px-5 py-3 font-semibold">Date</th>
                            <th class="px-5 py-3 text-right font-semibold">{{ __('app.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $statusBadge = [
                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                            ];
                        @endphp
                        @forelse ($reviews as $review)
                            <tr class="hover:bg-slate-50/70 transition-colors {{ $review->status === 'pending' ? 'bg-amber-50/20' : '' }}">
                                <td class="w-10 px-5 py-3.5 text-center">
                                    <input type="checkbox"
                                           class="control row-check"
                                           name="ids[]"
                                           value="{{ $review->id }}">
                                </td>

                                <!-- Student Details -->
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($review->student?->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-[#191919] truncate">{{ $review->student?->name ?? 'Student Deleted' }}</p>
                                            <p class="text-[11px] text-slate-400 truncate">{{ $review->student?->email ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Course Details -->
                                <td class="px-5 py-3.5">
                                    <div class="max-w-[200px]">
                                        @if ($review->course)
                                            <a href="{{ route('courses.show', $review->course->slug) }}" target="_blank" class="font-semibold text-slate-800 hover:text-[var(--theme-primary,#344c3d)] line-clamp-1">
                                                {{ $review->course->trans('title') }}
                                            </a>
                                            <p class="text-[11px] text-slate-400 mt-0.5">By {{ $review->course->instructor?->name ?? 'Instructor' }}</p>
                                        @else
                                            <span class="text-slate-400 italic">Course Removed</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Star Rating -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        <span class="text-amber-400 text-sm tracking-wider">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                        <span class="text-[11px] font-bold text-slate-700 ml-1">({{ $review->rating }}.0)</span>
                                    </div>
                                </td>

                                <!-- Comment & Reply Preview -->
                                <td class="px-5 py-3.5 max-w-xs">
                                    @if ($review->comment)
                                        <p class="text-slate-700 line-clamp-2 leading-relaxed">
                                            {{ $review->comment }}
                                        </p>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Rating only (no written review)</span>
                                    @endif

                                    @if ($review->instructor_reply)
                                        <div class="mt-1 flex items-center gap-1.5 text-[10px] text-emerald-700 font-semibold bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-none w-fit">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                            <span>Has official response</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="text-[10px] font-bold uppercase tracking-[0.12em] px-2.5 py-1 rounded-none border {{ $statusBadge[$review->status] ?? 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                        {{ $review->status }}
                                    </span>
                                </td>

                                <!-- Date -->
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-500 text-[11px]">
                                    <p>{{ $review->created_at->format('M d, Y') }}</p>
                                    <p class="text-slate-400 text-[10px]">{{ $review->created_at->diffForHumans() }}</p>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Quick Approve (if not approved) -->
                                        @if ($review->status !== 'approved')
                                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" title="Approve Review" class="px-2 py-1 rounded bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200 text-[11px] font-bold transition-colors">
                                                    ✓ Approve
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Quick Reject (if not rejected) -->
                                        @if ($review->status !== 'rejected')
                                            <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" title="Reject Review" class="px-2 py-1 rounded bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white border border-amber-200 text-[11px] font-bold transition-colors">
                                                    ✕ Reject
                                                </button>
                                            </form>
                                        @endif

                                        <!-- View / Reply Modal Trigger -->
                                        <button type="button"
                                                onclick="openReviewModal({{ json_encode([
                                                    'id' => $review->id,
                                                    'student' => $review->student?->name ?? 'Student',
                                                    'student_email' => $review->student?->email ?? '—',
                                                    'course' => $review->course?->title ?? 'Course',
                                                    'rating' => $review->rating,
                                                    'comment' => $review->comment ?? '',
                                                    'reply' => $review->instructor_reply ?? '',
                                                    'status' => $review->status,
                                                    'created_at' => $review->created_at->format('M d, Y H:i'),
                                                    'reply_url' => route('admin.reviews.reply', $review),
                                                    'approve_url' => route('admin.reviews.approve', $review),
                                                    'reject_url' => route('admin.reviews.reject', $review),
                                                ]) }})"
                                                class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-[11px] font-semibold transition-colors">
                                            Inspect & Reply
                                        </button>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review permanently?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete Review" class="p-1 rounded text-slate-400 hover:text-rose-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                    <p class="font-semibold text-slate-700">No reviews found</p>
                                    <p class="text-xs text-slate-400 mt-1">Try resetting the filters or search query.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-5 py-4 border-t border-slate-100 bg-white">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Review Inspection & Response Modal -->
    <div id="review-inspect-modal" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white max-w-xl w-full border border-slate-200 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Review Details & Moderation</h3>
                    <p class="text-[11px] text-slate-500" id="modal-date">—</p>
                </div>
                <button type="button" onclick="closeReviewModal()" class="text-slate-400 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                <!-- Student & Course Info -->
                <div class="grid grid-cols-2 gap-4 pb-4 border-b border-slate-100 text-xs">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Student</span>
                        <p class="font-bold text-slate-900 mt-0.5" id="modal-student">—</p>
                        <p class="text-slate-500 text-[11px]" id="modal-email">—</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Course</span>
                        <p class="font-bold text-slate-900 mt-0.5 line-clamp-2" id="modal-course">—</p>
                    </div>
                </div>

                <!-- Star Rating Display -->
                <div class="flex items-center justify-between pb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-amber-400 text-xl" id="modal-stars">★★★★★</span>
                        <span class="font-bold text-slate-800 text-sm" id="modal-rating-num">5.0 / 5</span>
                    </div>
                    <span id="modal-status-badge" class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-none border">
                        APPROVED
                    </span>
                </div>

                <!-- Review Comment Box -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Student Review Content</label>
                    <div class="bg-slate-50 border border-slate-200 p-3.5 text-xs text-slate-800 leading-relaxed min-h-[60px]" id="modal-comment">
                        —
                    </div>
                </div>

                <!-- Official Response Form -->
                <form id="modal-reply-form" method="POST" action="" class="space-y-3 pt-2">
                    @csrf
                    <div>
                        <label for="modal-reply-input" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Official Administrator / Instructor Reply
                        </label>
                        <textarea id="modal-reply-input"
                                  name="instructor_reply"
                                  rows="3"
                                  placeholder="Type an official response. This will be publicly shown under the review and notify the student..."
                                  class="w-full bg-white border border-slate-200 rounded-lg p-3 text-xs focus:outline-none focus:border-[#344C3D]"></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <button type="submit" class="px-4 py-2 bg-[#344C3D] hover:bg-[#25372C] text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-xs">
                            Save Official Reply
                        </button>

                        <div class="flex items-center gap-2">
                            <form id="modal-approve-form" method="POST" action="" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors">
                                    Approve
                                </button>
                            </form>
                            <form id="modal-reject-form" method="POST" action="" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-colors">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Action & Modal Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('check-all');
            const rowChecks = document.querySelectorAll('.row-check');
            const counter = document.getElementById('selected-counter');
            const bulkBtns = document.querySelectorAll('.bulk-submit-btn');

            function updateBulkState() {
                const checked = Array.from(rowChecks).filter(c => c.checked);
                const count = checked.length;
                if (counter) counter.textContent = count + ' selected';

                bulkBtns.forEach(btn => btn.disabled = (count === 0));

                // Append hidden input ids to all bulk forms
                ['bulk-approve-form', 'bulk-reject-form', 'bulk-delete-form'].forEach(formId => {
                    const form = document.getElementById(formId);
                    if (!form) return;
                    form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
                    checked.forEach(c => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = c.value;
                        form.appendChild(input);
                    });
                });
            }

            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    rowChecks.forEach(c => c.checked = checkAll.checked);
                    updateBulkState();
                });
            }

            rowChecks.forEach(c => {
                c.addEventListener('change', function () {
                    if (checkAll) {
                        checkAll.checked = Array.from(rowChecks).every(c => c.checked);
                    }
                    updateBulkState();
                });
            });
        });

        function openReviewModal(data) {
            document.getElementById('modal-student').textContent = data.student;
            document.getElementById('modal-email').textContent = data.student_email;
            document.getElementById('modal-course').textContent = data.course;
            document.getElementById('modal-date').textContent = 'Submitted on ' + data.created_at;

            const stars = '★'.repeat(data.rating) + '☆'.repeat(5 - data.rating);
            document.getElementById('modal-stars').textContent = stars;
            document.getElementById('modal-rating-num').textContent = data.rating + '.0 / 5';

            document.getElementById('modal-comment').textContent = data.comment ? data.comment : '(No written comment provided)';

            const statusBadge = document.getElementById('modal-status-badge');
            statusBadge.textContent = data.status.toUpperCase();
            statusBadge.className = 'px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-none border ' + (
                data.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                (data.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200')
            );

            document.getElementById('modal-reply-form').action = data.reply_url;
            document.getElementById('modal-reply-input').value = data.reply || '';

            const approveForm = document.getElementById('modal-approve-form');
            if (approveForm) approveForm.action = data.approve_url;

            const rejectForm = document.getElementById('modal-reject-form');
            if (rejectForm) rejectForm.action = data.reject_url;

            document.getElementById('review-inspect-modal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('review-inspect-modal').classList.add('hidden');
        }
    </script>

</x-layouts.dashboard>
