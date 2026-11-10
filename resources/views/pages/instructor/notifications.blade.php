<x-layouts.dashboard :title="'Notifications - Mono LMS'" active="notifications" userType="instructor">

    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-bold text-[#191919] dark:text-white">Notifications</h2>
            <p class="text-xs text-gray-400 mt-0.5">Stay updated with your account activity, student enrollments, and reviews.</p>
        </div>

        {{-- Verification Status Card --}}
        @php $instructor = auth('instructor')->user(); @endphp
        <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl p-5 shadow-xs">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0
                    {{ $verification['status'] === 'verified' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : ($verification['status'] === 'pending' ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400' : ($verification['status'] === 'rejected' ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400')) }}">
                    @if ($verification['status'] === 'verified')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif ($verification['status'] === 'pending')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif ($verification['status'] === 'rejected')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-[15px] text-slate-900 dark:text-white">Account Verification</h3>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full
                            {{ $verification['status'] === 'verified' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400' : ($verification['status'] === 'pending' ? 'bg-blue-100 text-blue-700 dark:bg-blue-950/50 dark:text-blue-400' : ($verification['status'] === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-950/50 dark:text-red-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400')) }}">
                            {{ ucfirst($verification['status']) }}
                        </span>
                    </div>
                    <p class="text-[13px] text-slate-500 dark:text-neutral-400 mt-1">
                        @if ($verification['status'] === 'verified')
                            Your identity and credentials are approved. Course creation and directory listing are active.
                        @elseif ($verification['status'] === 'pending')
                            Your documents have been submitted and are queued for administrator review.
                        @elseif ($verification['status'] === 'rejected')
                            {{ $verification['rejection_reason'] ?: 'Your credentials could not be verified. Please review feedback and resubmit.' }}
                        @else
                            Submit your instructor certificate and national ID to unlock course creation.
                        @endif
                    </p>
                    @if ($verification['status'] !== 'verified')
                        <a href="{{ route('instructor.verification.show') }}" class="inline-block mt-2 text-[12px] font-medium text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline">
                            Go to verification &rarr;
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Activity Summary --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Total Students</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total_students'] }}</p>
            </div>
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Total Reviews</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total_reviews'] }}</p>
            </div>
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Average Rating</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['avg_rating'] ?: '—' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Enrollments --}}
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl shadow-xs">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-neutral-800">
                    <h3 class="font-semibold text-[14px] text-slate-900 dark:text-white">Recent Enrollments</h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-neutral-800">
                    @forelse ($recentEnrollments as $enrollment)
                        <div class="px-5 py-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-neutral-800 flex items-center justify-center shrink-0 text-xs font-bold text-slate-600 dark:text-neutral-300">
                                {{ strtoupper(substr($enrollment->student?->name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-medium text-slate-900 dark:text-white truncate">{{ $enrollment->student?->name ?? 'Student' }}</p>
                                <p class="text-[11px] text-slate-400 truncate">Enrolled in {{ $enrollment->course?->title ?? 'Course' }}</p>
                            </div>
                            <span class="text-[11px] text-slate-400 shrink-0">{{ $enrollment->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-[13px] text-slate-400">No enrollments yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Reviews --}}
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl shadow-xs">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-neutral-800">
                    <h3 class="font-semibold text-[14px] text-slate-900 dark:text-white">Recent Reviews</h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-neutral-800">
                    @forelse ($recentReviews as $review)
                        <div class="px-5 py-3 flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-neutral-800 flex items-center justify-center shrink-0 text-xs font-bold text-slate-600 dark:text-neutral-300">
                                {{ strtoupper(substr($review->student?->name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-[13px] font-medium text-slate-900 dark:text-white truncate">{{ $review->student?->name ?? 'Student' }}</p>
                                    <span class="text-[11px] text-amber-500 font-semibold">★ {{ $review->rating }}</span>
                                </div>
                                <p class="text-[11px] text-slate-400 truncate">{{ $review->course?->title ?? 'Course' }}</p>
                                @if ($review->comment)
                                    <p class="text-[12px] text-slate-500 dark:text-neutral-400 mt-1 line-clamp-2">{{ $review->comment }}</p>
                                @endif
                            </div>
                            <span class="text-[11px] text-slate-400 shrink-0">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-[13px] text-slate-400">No reviews yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
