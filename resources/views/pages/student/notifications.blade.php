<x-layouts.dashboard title="Notifications - Mono LMS" active="notifications" userType="student">

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Notifications</h1>
                <p class="text-sm text-slate-500 dark:text-neutral-400 mt-0.5">
                    Live updates on coursework, active enrollments, helpdesk replies, and account alerts.
                </p>
            </div>
            @if ($counts['unread'] > 0)
                <form method="POST" action="{{ route('student.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-neutral-800 text-slate-700 dark:text-neutral-200 hover:bg-slate-50 dark:hover:bg-neutral-800 transition-colors shadow-xs cursor-pointer">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        <span>Mark All as Read ({{ $counts['unread'] }})</span>
                    </button>
                </form>
            @endif
        </div>

        {{-- Filter Tabs & Search --}}
        <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                <a href="{{ route('student.notifications.index', ['filter' => 'all']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'all' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    All <span class="ml-1 opacity-75">({{ $counts['all'] }})</span>
                </a>
                <a href="{{ route('student.notifications.index', ['filter' => 'unread']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'unread' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Unread <span class="ml-1 opacity-75">({{ $counts['unread'] }})</span>
                </a>
                <a href="{{ route('student.notifications.index', ['filter' => 'enrollment']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'enrollment' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Enrollments <span class="ml-1 opacity-75">({{ $counts['enrollment'] }})</span>
                </a>
                <a href="{{ route('student.notifications.index', ['filter' => 'support']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'support' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Support <span class="ml-1 opacity-75">({{ $counts['support'] }})</span>
                </a>
                <a href="{{ route('student.notifications.index', ['filter' => 'smtp']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'smtp' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    SMTP Alerts <span class="ml-1 opacity-75">({{ $counts['smtp'] }})</span>
                </a>
            </div>

            <form method="GET" action="{{ route('student.notifications.index') }}" class="relative shrink-0">
                <input type="hidden" name="filter" value="{{ $currentFilter }}">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search notifications..."
                       class="w-full md:w-64 pl-8 pr-3 py-1.5 text-xs bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200 dark:border-neutral-800 rounded-lg text-slate-800 dark:text-neutral-200 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </form>
        </div>

        {{-- Notifications List --}}
        <div class="space-y-3">
            @forelse ($notifications as $notif)
                @php
                    $data = $notif->data;
                    $isUnread = is_null($notif->read_at);
                    $cat = $data['category'] ?? 'system';
                    $catColorClass = match($cat) {
                        'support' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200/50 dark:border-blue-900/40',
                        'smtp' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400 border border-purple-200/50 dark:border-purple-900/40',
                        'enrollment' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-900/40',
                        default => 'bg-slate-100 text-slate-600 dark:bg-neutral-800 dark:text-neutral-300 border border-slate-200 dark:border-neutral-700',
                    };
                @endphp
                <div class="bg-white dark:bg-[#111111] border {{ $isUnread ? 'border-emerald-500/40 dark:border-emerald-500/30 ring-1 ring-emerald-500/10' : 'border-slate-200 dark:border-neutral-800' }} rounded-xl p-4 sm:p-5 shadow-xs transition-all hover:border-slate-300 dark:hover:border-neutral-700">
                    <div class="flex items-start gap-4">
                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 mt-0.5 {{ $catColorClass }}">
                            @if ($cat === 'support')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                            @elseif ($cat === 'smtp')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            @elseif ($cat === 'enrollment')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-sm {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-neutral-300' }}">
                                        {{ $data['title'] ?? 'Notification' }}
                                    </h3>
                                    @if ($isUnread)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400">
                                            NEW
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400 dark:text-neutral-500 whitespace-nowrap">
                                    {{ $notif->created_at->format('M d, Y · h:i A') }} ({{ $notif->created_at->diffForHumans() }})
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-neutral-400 mt-1.5 leading-relaxed">
                                {{ $data['message'] ?? '' }}
                            </p>

                            <div class="flex flex-wrap items-center justify-between gap-3 mt-3 pt-3 border-t border-slate-100 dark:border-neutral-800/80">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-slate-100 dark:bg-neutral-800 text-slate-600 dark:text-neutral-400">
                                        {{ ucfirst($cat) }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if (!empty($data['action_url']))
                                        <form method="POST" action="{{ route('student.notifications.read', ['id' => $notif->id, 'redirect' => 1]) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline cursor-pointer">
                                                <span>{{ $data['action_label'] ?? 'View Details' }}</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($isUnread)
                                        <form method="POST" action="{{ route('student.notifications.read', ['id' => $notif->id]) }}">
                                            @csrf
                                            <button type="submit" class="p-1 rounded hover:bg-slate-100 dark:hover:bg-neutral-800 text-slate-400 hover:text-slate-700 dark:hover:text-neutral-200 text-xs font-medium cursor-pointer" title="Mark as read">
                                                Mark read
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('student.notifications.destroy', $notif->id) }}" onsubmit="return confirm('Delete this notification?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded hover:bg-red-50 dark:hover:bg-red-500/10 text-slate-400 hover:text-red-500 text-xs font-medium cursor-pointer" title="Delete notification">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl p-12 text-center shadow-xs">
                    <svg class="w-12 h-12 text-slate-300 dark:text-neutral-600 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    <h3 class="text-base font-semibold text-slate-800 dark:text-white">No notifications found</h3>
                    <p class="text-xs text-slate-500 dark:text-neutral-400 mt-1">There are no notifications matching the current filter criteria.</p>
                </div>
            @endforelse
        </div>

        @if ($notifications->hasPages())
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</x-layouts.dashboard>
