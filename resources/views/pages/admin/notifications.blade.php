<x-layouts.dashboard title="Notifications - Mono LMS" active="notifications" userType="admin">

    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-bold text-[#191919] dark:text-white">Notifications</h2>
            <p class="text-xs text-gray-400 mt-0.5">Platform activity overview — verifications, messages, enrollments, and reviews.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Pending Verifications</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['pending_verifications'] }}</p>
            </div>
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Unread Messages</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['unread_messages'] }}</p>
            </div>
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Enrollments Today</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['today_enrollments'] }}</p>
            </div>
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Pending Reviews</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['pending_reviews'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Pending Verifications --}}
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl shadow-xs">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-neutral-800 flex items-center justify-between">
                    <h3 class="font-semibold text-[14px] text-slate-900 dark:text-white">Pending Verifications</h3>
                    <a href="{{ route('admin.instructors.index', ['verification' => 'pending']) }}" class="text-[12px] font-medium text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline">View all &rarr;</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-neutral-800">
                    @forelse ($pendingVerifications as $inst)
                        <a href="{{ route('admin.instructors.edit', $inst) }}" class="px-5 py-3 flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-neutral-800/50 transition-colors block">
                            <div class="w-8 h-8 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 text-xs font-bold">
                                {{ strtoupper(substr($inst->name ?? 'I', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-medium text-slate-900 dark:text-white truncate">{{ $inst->name }}</p>
                                <p class="text-[11px] text-slate-400">{{ $inst->email }}</p>
                            </div>
                            <span class="text-[11px] text-slate-400 shrink-0">{{ $inst->created_at->diffForHumans() }}</span>
                        </a>
                    @empty
                        <div class="px-5 py-8 text-center text-[13px] text-slate-400">No pending verifications.</div>
                    @endforelse
                </div>
            </div>

            {{-- Unread Messages --}}
            <div class="bg-white dark:bg-[#111111] border border-[#e7e7e7] dark:border-neutral-800 rounded-xl shadow-xs">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-neutral-800 flex items-center justify-between">
                    <h3 class="font-semibold text-[14px] text-slate-900 dark:text-white">Unread Messages</h3>
                    <a href="{{ route('admin.messages.index') }}" class="text-[12px] font-medium text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline">View all &rarr;</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-neutral-800">
                    @forelse ($unreadMessages as $msg)
                        <div class="px-5 py-3 flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 text-xs font-bold">
                                {{ strtoupper(substr($msg->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-medium text-slate-900 dark:text-white truncate">{{ $msg->name }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $msg->subject ?? $msg->message }}</p>
                            </div>
                            <span class="text-[11px] text-slate-400 shrink-0">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-[13px] text-slate-400">All messages have been read.</div>
                    @endforelse
                </div>
            </div>

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
                                <p class="text-[11px] text-slate-400 truncate">{{ $enrollment->course?->title ?? 'Course' }}</p>
                            </div>
                            <span class="text-[11px] text-slate-400 shrink-0">{{ $enrollment->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-[13px] text-slate-400">No recent enrollments.</div>
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
                            </div>
                            <span class="text-[11px] text-slate-400 shrink-0">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-[13px] text-slate-400">No recent reviews.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
