<x-layouts.dashboard title="Pages & Permalinks - Mono LMS" active="pages" userType="admin">

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Pages & Permalinks</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-neutral-400 mt-1">
                    Manage static pages (About, Contact, Privacy, Terms, Instructors), configure custom permalinks, and author custom pages.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.pages.create') }}" class="action-btn action-btn-primary text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    <span>Create New Page</span>
                </a>
            </div>
        </div>

        <!-- Filter Tabs & Search -->
        <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                <a href="{{ route('admin.pages.index', ['filter' => 'all']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'all' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    All Pages <span class="ml-1 opacity-75">({{ $counts['all'] }})</span>
                </a>
                <a href="{{ route('admin.pages.index', ['filter' => 'published']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'published' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Published <span class="ml-1 opacity-75">({{ $counts['published'] }})</span>
                </a>
                <a href="{{ route('admin.pages.index', ['filter' => 'draft']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'draft' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Drafts <span class="ml-1 opacity-75">({{ $counts['draft'] }})</span>
                </a>
                <a href="{{ route('admin.pages.index', ['filter' => 'system']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'system' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    System Pages <span class="ml-1 opacity-75">({{ $counts['system'] }})</span>
                </a>
                <a href="{{ route('admin.pages.index', ['filter' => 'custom']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $currentFilter === 'custom' ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs' : 'text-slate-600 dark:text-neutral-300 hover:bg-slate-100 dark:hover:bg-neutral-800' }}">
                    Custom Pages <span class="ml-1 opacity-75">({{ $counts['custom'] }})</span>
                </a>
            </div>

            <form method="GET" action="{{ route('admin.pages.index') }}" class="relative shrink-0">
                <input type="hidden" name="filter" value="{{ $currentFilter }}">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search title or permalink..."
                       class="w-full md:w-64 pl-8 pr-3 py-1.5 text-xs bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200 dark:border-neutral-800 rounded-lg text-slate-800 dark:text-neutral-200 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary,#344c3d)]">
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </form>
        </div>

        <!-- Pages Table -->
        <div class="bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50 dark:bg-neutral-900/80 border-b border-slate-100 dark:border-neutral-800">
                            <th class="px-5 py-3.5">Page Title</th>
                            <th class="px-5 py-3.5">Permalink / URL</th>
                            <th class="px-5 py-3.5">Template Type</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Last Updated</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-neutral-800/80">
                        @forelse ($pages as $p)
                            @php
                                $publicUrl = url($p->slug);
                                $templateColor = match($p->template) {
                                    'about' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border-purple-200/50',
                                    'contact' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border-blue-200/50',
                                    'privacy' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border-amber-200/50',
                                    'tnc' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border-emerald-200/50',
                                    'instructors' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 border-indigo-200/50',
                                    default => 'bg-slate-100 text-slate-700 dark:bg-neutral-800 dark:text-neutral-300 border-slate-200/50',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-neutral-900/40 transition-colors">
                                <!-- Title -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-neutral-800 flex items-center justify-center shrink-0 text-slate-600 dark:text-neutral-300">
                                            @if ($p->template === 'about')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                                            @elseif ($p->template === 'contact')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                            @elseif ($p->template === 'privacy' || $p->template === 'tnc')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                                            @elseif ($p->template === 'instructors')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.pages.edit', $p) }}" class="font-bold text-slate-900 dark:text-white hover:text-[var(--theme-primary,#344c3d)] dark:hover:text-emerald-400 transition-colors truncate block">
                                                {{ $p->title }}
                                            </a>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                @if ($p->is_system)
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 dark:bg-neutral-800 px-1.5 py-0.2 rounded" title="Core System Page">
                                                        <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                        System
                                                    </span>
                                                @endif
                                                @if ($p->meta_title)
                                                    <span class="text-[11px] text-slate-400 truncate max-w-xs">{{ $p->meta_title }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Permalink -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <code class="px-2 py-0.5 text-xs font-mono bg-slate-100 dark:bg-neutral-800/80 text-slate-800 dark:text-neutral-200 rounded border border-slate-200/60 dark:border-neutral-700/60">
                                            /{{ $p->slug }}
                                        </code>
                                        <a href="{{ $publicUrl }}"
                                           target="_blank"
                                           title="Preview live page in new tab"
                                           class="p-1 rounded text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-neutral-800 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        </a>
                                        <button type="button"
                                                onclick="navigator.clipboard.writeText('{{ $publicUrl }}'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy', 1500);"
                                                class="text-[10px] text-slate-400 hover:text-slate-700 dark:hover:text-neutral-200 underline cursor-pointer">
                                            Copy
                                        </button>
                                    </div>
                                </td>

                                <!-- Template -->
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $templateColor }}">
                                        {{ ucfirst($p->template) }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-4">
                                    @if ($p->isPublished())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <!-- Last Updated -->
                                <td class="px-5 py-4 text-xs text-slate-500 dark:text-neutral-400 whitespace-nowrap">
                                    <div>{{ $p->updated_at->format('M d, Y') }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $p->updated_at->diffForHumans() }}</div>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('admin.pages.edit', $p) }}"
                                           class="px-2.5 py-1 text-xs font-semibold rounded bg-slate-100 hover:bg-slate-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-slate-700 dark:text-neutral-200 transition-colors">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.pages.duplicate', $p) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    title="Duplicate page"
                                                    class="p-1 rounded hover:bg-slate-100 dark:hover:bg-neutral-800 text-slate-400 hover:text-slate-700 dark:hover:text-neutral-200 transition-colors cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v2.25A2.25 2.25 0 0113.5 21.75h-7.5A2.25 2.25 0 013.75 19.5V7.5A2.25 2.25 0 016 5.25h2.25m3 3h7.5A2.25 2.25 0 0121 10.5v7.5A2.25 2.25 0 0118.75 20.25h-7.5A2.25 2.25 0 019 18v-7.5A2.25 2.25 0 0111.25 8.25z"/></svg>
                                            </button>
                                        </form>

                                        @if (! $p->is_system)
                                            <form method="POST" action="{{ route('admin.pages.destroy', $p) }}" class="inline" onsubmit="return confirm('Delete this page permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        title="Delete page"
                                                        class="p-1 rounded hover:bg-red-50 dark:hover:bg-red-500/10 text-slate-400 hover:text-red-500 transition-colors cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="p-1 text-slate-300 dark:text-neutral-600" title="System pages are protected from deletion">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 dark:text-neutral-500">
                                    No pages found matching the selected filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pages->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-neutral-800">
                    {{ $pages->links() }}
                </div>
            @endif
        </div>
    </div>

</x-layouts.dashboard>
