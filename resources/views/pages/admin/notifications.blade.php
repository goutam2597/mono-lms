<x-layouts.dashboard title="Notifications - Mono LMS" active="notifications" userType="admin">

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Notifications</h1>
                <p class="text-sm text-slate-500 dark:text-neutral-400 mt-0.5">
                    Platform-wide live alerts: instructor verification requests, contact inquiries, support tickets, and SMTP services.
                </p>
            </div>
            @if ($counts['unread'] > 0)
                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
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
                <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'all' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    All <span class="ml-1 opacity-75">({{ $counts['all'] }})</span>
                </a>
                <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'unread' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Unread <span class="ml-1 opacity-75">({{ $counts['unread'] }})</span>
                </a>
                <a href="{{ route('admin.notifications.index', ['filter' => 'verification']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'verification' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Verifications <span class="ml-1 opacity-75">({{ $counts['verification'] }})</span>
                </a>
                <a href="{{ route('admin.notifications.index', ['filter' => 'support']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'support' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Support & Messages <span class="ml-1 opacity-75">({{ $counts['support'] }})</span>
                </a>
                <a href="{{ route('admin.notifications.index', ['filter' => 'smtp']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'smtp' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    SMTP Alerts <span class="ml-1 opacity-75">({{ $counts['smtp'] }})</span>
                </a>
                <a href="{{ route('admin.notifications.index', ['filter' => 'enrollment']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'enrollment' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Orders & Enrollments <span class="ml-1 opacity-75">({{ $counts['enrollment'] }})</span>
                </a>
            </div>

            <form method="GET" action="{{ route('admin.notifications.index') }}" class="relative shrink-0">
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
                    $channels = $data['channels'] ?? ['In-App'];
                    $catColorClass = match($cat) {
                        'verification' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/50 dark:border-amber-900/40',
                        'support' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200/50 dark:border-blue-900/40',
                        'smtp' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400 border border-purple-200/50 dark:border-purple-900/40',
                        'enrollment' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-900/40',
                        'review' => 'bg-amber-50 text-amber-500 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/50 dark:border-amber-900/40',
                        default => 'bg-slate-100 text-slate-600 dark:bg-neutral-800 dark:text-neutral-300 border border-slate-200 dark:border-neutral-700',
                    };
                @endphp
                <div class="bg-white dark:bg-[#111111] border {{ $isUnread ? 'border-emerald-500/40 dark:border-emerald-500/30 ring-1 ring-emerald-500/10' : 'border-slate-200 dark:border-neutral-800' }} rounded-xl p-4 sm:p-5 shadow-xs transition-all hover:border-slate-300 dark:hover:border-neutral-700">
                    <div class="flex items-start gap-4">
                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 mt-0.5 {{ $catColorClass }}">
                            @if ($cat === 'verification')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif ($cat === 'support')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                            @elseif ($cat === 'smtp')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            @elseif ($cat === 'enrollment')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <h2 class="text-sm font-semibold {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-neutral-300' }}">
                                        {{ $data['title'] ?? 'Notification' }}
                                    </h2>
                                    @if ($isUnread)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            NEW
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400 dark:text-neutral-500">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-xs sm:text-[13px] text-slate-600 dark:text-neutral-400 mt-1.5 leading-relaxed">
                                {{ $data['message'] ?? '' }}
                            </p>

                            <div class="flex flex-wrap items-center justify-between gap-3 mt-3 pt-3 border-t border-slate-100 dark:border-neutral-800/80">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-slate-100 dark:bg-neutral-800 text-slate-600 dark:text-neutral-400">
                                        {{ ucfirst($cat) }}
                                    </span>
                                    @foreach ($channels as $channel)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-800 text-slate-500 dark:text-neutral-400">
                                            {{ $channel }}
                                        </span>
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-3">
                                    @if (!empty($data['action_url']))
                                        <a href="{{ $data['action_url'] }}" class="text-xs font-semibold text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                                            <span>{{ $data['action_label'] ?? 'View Details' }}</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                        </a>
                                    @endif

                                    @if ($isUnread)
                                        <form method="POST" action="{{ route('admin.notifications.read', $notif->id) }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-medium text-slate-500 hover:text-slate-800 dark:text-neutral-400 dark:hover:text-white cursor-pointer hover:underline">
                                                Mark Read
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.notifications.destroy', $notif->id) }}" onsubmit="return confirm('Delete this notification?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-slate-400 hover:text-red-600 dark:hover:text-red-400 cursor-pointer" title="Delete notification">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl p-12 text-center shadow-xs">
                    <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-neutral-600 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    <h3 class="text-base font-semibold text-slate-800 dark:text-white">No notifications found</h3>
                    <p class="text-xs text-slate-500 dark:text-neutral-400 mt-1 max-w-sm mx-auto">
                        No notifications match the selected filter. Platform services are running normally!
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</x-layouts.dashboard>
