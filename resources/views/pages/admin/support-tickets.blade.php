<x-layouts.dashboard title="Support Tickets - Mono LMS" active="tickets" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 36px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn-primary { color: #fff; background: var(--theme-primary, #344c3d); border: 1px solid var(--theme-primary, #344c3d); }
    .action-btn-primary:hover { color: #fff; background: var(--theme-primary-hover, #25372c); border-color: var(--theme-primary-hover, #25372c); }
    .action-btn-ghost { color: #171717; background: #ffffff; border-color: #e5e5e5; }
    .action-btn-ghost:hover { color: #0a0a0a; background: #f5f5f5; border-color: #d4d4d4; }
    .dark .action-btn-ghost { color: #f5f5f5; background: #171717; border-color: #262626; }
    .dark .action-btn-ghost:hover { color: #ffffff; background: #262626; border-color: #404040; }
    .action-btn-danger { color: #b91c1c; background: #fef2f2; border-color: #fecaca; }
    .action-btn-danger:hover { color: #991b1b; background: #fee2e2; border-color: #fca5a5; }
    .dark .action-btn-danger { color: #f87171; background: #450a0a; border-color: #7f1d1d; }
    .dark .action-btn-danger:hover { color: #fca5a5; background: #7f1d1d; }

    /* Modal System */
    .modal-backdrop { position: fixed; inset: 0; z-index: 100; display: none; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(2px); padding: 16px; }
    .modal-backdrop.open { display: flex; }
</style>

<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900 dark:text-white">Support Help Desk</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800 rounded-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    {{ $stats['open'] }} Open Inquiries
                </span>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Manage, prioritize, and resolve technical, billing, and account inquiries from students and instructors.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="openCreateModal()" class="action-btn action-btn-primary">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Ticket
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 text-xs font-medium space-y-1">
            @foreach ($errors->all() as $error)
                <p>&bull; {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- 4 KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.tickets.index') }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-neutral-400 dark:hover:border-neutral-600 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400">Total Inquiries</span>
            <p class="text-2xl font-bold text-neutral-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
            <span class="text-[11px] text-neutral-400">All recorded tickets</span>
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-blue-400 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Open Queue</span>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['open'] }}</p>
            <span class="text-[11px] text-neutral-400">Awaiting staff response</span>
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'in_progress']) }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-amber-400 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">In Progress</span>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['in_progress'] }}</p>
            <span class="text-[11px] text-neutral-400">Under investigation</span>
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'resolved']) }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-emerald-400 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Resolved</span>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['resolved'] }}</p>
            <span class="text-[11px] text-neutral-400">Successfully closed</span>
        </a>
    </div>

    <!-- Tickets Container -->
    <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] shadow-xs">
        <!-- Search & Filter Controls -->
        <div class="p-4 border-b border-neutral-100 dark:border-neutral-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Status Tabs -->
            <div class="flex items-center gap-1 overflow-x-auto pb-1 md:pb-0">
                @php
                    $curStatus = request('status', 'all');
                @endphp
                <a href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $curStatus === 'all' ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 border-neutral-900 dark:border-white' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    All ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'open'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $curStatus === 'open' ? 'bg-blue-600 text-white border-blue-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    Open ({{ $stats['open'] }})
                </a>
                <a href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'in_progress'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $curStatus === 'in_progress' ? 'bg-amber-600 text-white border-amber-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    In Progress ({{ $stats['in_progress'] }})
                </a>
                <a href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'resolved'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $curStatus === 'resolved' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    Resolved ({{ $stats['resolved'] }})
                </a>
                <a href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'closed'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $curStatus === 'closed' ? 'bg-neutral-600 text-white border-neutral-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    Closed ({{ $stats['closed'] }})
                </a>
            </div>

            <!-- Search & Priority Dropdown Form -->
            <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ticket #, user, subject..."
                           class="h-9 w-full pl-9 pr-3 text-xs bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 text-neutral-900 dark:text-white rounded-sm focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>
                <select name="priority" onchange="this.form.submit()" class="h-9 text-xs bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 text-neutral-900 dark:text-white rounded-sm px-2.5 focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                    <option value="all" {{ request('priority') === 'all' || !request('priority') ? 'selected' : '' }}>All Priorities</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                </select>
                @if(request('search') || request('priority') || (request('status') && request('status') !== 'all'))
                    <a href="{{ route('admin.tickets.index') }}" class="action-btn action-btn-ghost h-9 px-3 text-xs" title="Reset Filters">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Bulk Action Form Bar -->
        <form id="bulk-tickets-form" method="POST" action="{{ route('admin.tickets.bulk-action') }}">
            @csrf
            <div class="px-5 py-2.5 bg-neutral-50/80 dark:bg-neutral-900/60 border-b border-neutral-100 dark:border-neutral-800 flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="check-all-tickets" class="control" aria-label="Select all tickets">
                    <label for="check-all-tickets" class="text-[11px] font-bold uppercase tracking-wider text-neutral-600 dark:text-neutral-400 cursor-pointer">
                        Select All In View
                    </label>
                </div>
                <div class="flex items-center gap-2">
                    <select name="action" id="bulk-action-select" class="h-8 text-[11px] bg-white dark:bg-[#151515] border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white px-2.5 rounded-sm focus:outline-none">
                        <option value="">Bulk Actions...</option>
                        <option value="status_in_progress">Mark as In Progress</option>
                        <option value="status_resolved">Mark as Resolved</option>
                        <option value="status_closed">Mark as Closed</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="button" onclick="submitBulkTickets()" class="action-btn action-btn-ghost h-8 px-3 text-[10px]">
                        Apply
                    </button>
                </div>
            </div>

            <!-- Tickets List -->
            <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @forelse($tickets as $ticket)
                    @php
                        $priorityBadge = match($ticket->priority) {
                            'urgent' => 'bg-red-50 text-red-700 dark:bg-red-950/60 dark:text-red-300 border-red-200 dark:border-red-800',
                            'high' => 'bg-orange-50 text-orange-700 dark:bg-orange-950/60 dark:text-orange-300 border-orange-200 dark:border-orange-800',
                            'medium' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                            default => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400 border-neutral-200 dark:border-neutral-700',
                        };

                        $statusBadge = match($ticket->status) {
                            'resolved' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                            'in_progress' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                            'closed' => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400 border-neutral-200 dark:border-neutral-700',
                            default => 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                        };

                        $matchedUser = $ticket->findUser();
                    @endphp

                    <div class="p-6 hover:bg-neutral-50/50 dark:hover:bg-neutral-900/30 transition-colors space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                            <div class="flex items-center gap-3 flex-wrap">
                                <input type="checkbox" name="ids[]" value="{{ $ticket->id }}" class="ticket-row-check control" aria-label="Select ticket #{{ $ticket->ticket_number }}">
                                <span class="font-mono text-xs font-bold text-[var(--theme-primary,#344c3d)] dark:text-emerald-400">#{{ $ticket->ticket_number }}</span>
                                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $ticket->subject }}</h3>
                                <span class="text-[10px] font-bold px-2 py-0.5 uppercase tracking-wider border rounded-xs {{ $priorityBadge }}">
                                    {{ $ticket->priority }}
                                </span>
                                <span class="text-[10px] font-bold px-2 py-0.5 uppercase tracking-wider border rounded-xs {{ $statusBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-neutral-400">
                                <span>{{ $ticket->created_at?->diffForHumans() }}</span>
                                <span>&bull;</span>
                                <span class="font-mono text-[11px]">{{ $ticket->created_at?->format('M d, Y H:i') }}</span>
                            </div>
                        </div>

                        <!-- Requester Info Bar -->
                        <div class="flex items-center gap-3 text-xs text-neutral-600 dark:text-neutral-400 flex-wrap">
                            <div class="flex items-center gap-1.5 font-medium text-neutral-900 dark:text-white">
                                <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>{{ $ticket->user_name }}</span>
                            </div>
                            <span>&bull;</span>
                            <a href="mailto:{{ $ticket->user_email }}" class="text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline">
                                {{ $ticket->user_email }}
                            </a>
                            @if($matchedUser)
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 rounded-xs">
                                    {{ class_basename($matchedUser) }}
                                </span>
                            @endif
                        </div>

                        <!-- Inquiry Message -->
                        <div class="text-xs text-neutral-700 dark:text-neutral-300 bg-neutral-50 dark:bg-[#151515] p-4 border border-neutral-200 dark:border-neutral-800 rounded-sm leading-relaxed whitespace-pre-line">
                            {{ $ticket->message }}
                        </div>

                        <!-- Existing Staff Reply Thread -->
                        @if($ticket->reply)
                            <div class="text-xs text-emerald-900 dark:text-emerald-200 bg-emerald-50/70 dark:bg-emerald-950/30 p-4 border border-emerald-200 dark:border-emerald-800/80 rounded-sm space-y-1">
                                <div class="flex items-center justify-between text-[11px] font-semibold text-emerald-800 dark:text-emerald-300 mb-1">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                        Staff Response (Sent to Requester):
                                    </span>
                                    <span class="font-mono text-[10px] opacity-75">
                                        {{ $ticket->replied_at ? $ticket->replied_at->diffForHumans() : 'Replied' }}
                                    </span>
                                </div>
                                <p class="leading-relaxed whitespace-pre-line">{{ $ticket->reply }}</p>
                            </div>
                        @endif

                        <!-- Action Bar: Reply & Controls -->
                        <div class="pt-2 border-t border-neutral-100 dark:border-neutral-800/80 flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <!-- Quick Reply Form -->
                            <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" class="flex-1 flex flex-col sm:flex-row items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="reply" placeholder="Type staff response..." required
                                       class="h-9 text-xs flex-1 min-w-0 w-full px-3.5 border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-[#151515] text-neutral-900 dark:text-white rounded-sm focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                                <select name="status" data-mselect data-mselect-no-search class="h-9 w-full sm:w-36 text-xs font-semibold border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-[#151515] text-neutral-900 dark:text-white rounded-sm px-2.5 focus:outline-none">
                                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                </select>
                                <button type="submit" class="action-btn action-btn-primary h-9 px-4 text-xs w-full sm:w-auto">
                                    Send Reply
                                </button>
                            </form>

                            <!-- Secondary Actions -->
                            <div class="flex items-center gap-2 self-end sm:self-auto flex-none">
                                <!-- Quick Priority Change -->
                                <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="priority" onchange="this.form.submit()" title="Change Priority" class="h-9 text-xs font-semibold border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-[#151515] text-neutral-700 dark:text-neutral-300 rounded-sm px-2 focus:outline-none">
                                        <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                                        <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                    </select>
                                </form>

                                <!-- Delete Ticket -->
                                <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" onsubmit="return confirm('Delete ticket #{{ $ticket->ticket_number }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger h-9 px-3 text-xs" title="Delete Ticket">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center text-neutral-400 dark:text-neutral-500 text-xs space-y-2">
                        <svg class="w-10 h-10 mx-auto text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <p class="font-medium text-neutral-600 dark:text-neutral-400">No support tickets match the selected criteria.</p>
                        <a href="{{ route('admin.tickets.index') }}" class="inline-block text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline">
                            Clear filters &amp; view all tickets
                        </a>
                    </div>
                @endforelse
            </div>
        </form>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-neutral-100 dark:border-neutral-800">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create Ticket Modal -->
<div id="create-ticket-modal" class="modal-backdrop">
    <div class="bg-white dark:bg-[#111111] border border-neutral-200 dark:border-neutral-800 w-full max-w-lg p-6 shadow-xl space-y-4 rounded-sm">
        <div class="flex items-center justify-between border-b border-neutral-100 dark:border-neutral-800 pb-3">
            <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Create New Support Ticket</h2>
            <button type="button" onclick="closeCreateModal()" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.tickets.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Requester Name *</label>
                    <input type="text" name="user_name" required placeholder="John Doe"
                           class="h-9 w-full text-xs px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none focus:border-neutral-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Requester Email *</label>
                    <input type="email" name="user_email" required placeholder="john@example.com"
                           class="h-9 w-full text-xs px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none focus:border-neutral-900">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Subject / Issue Title *</label>
                <input type="text" name="subject" required placeholder="e.g. Cannot access course materials on lesson 4"
                       class="h-9 w-full text-xs px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none focus:border-neutral-900">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Priority *</label>
                    <select name="priority" required class="h-9 w-full text-xs px-2.5 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none">
                        <option value="medium" selected>Medium</option>
                        <option value="low">Low</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Initial Status</label>
                    <select name="status" class="h-9 w-full text-xs px-2.5 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none">
                        <option value="open" selected>Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Inquiry Details *</label>
                <textarea name="message" rows="4" required placeholder="Describe the issue or request in detail..."
                          class="w-full text-xs p-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none focus:border-neutral-900"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-neutral-100 dark:border-neutral-800">
                <button type="button" onclick="closeCreateModal()" class="action-btn action-btn-ghost text-xs">
                    Cancel
                </button>
                <button type="submit" class="action-btn action-btn-primary text-xs">
                    Create Ticket
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('create-ticket-modal').classList.add('open');
    }

    function closeCreateModal() {
        document.getElementById('create-ticket-modal').classList.remove('open');
    }

    // Check all functionality
    var checkAll = document.getElementById('check-all-tickets');
    var rowChecks = document.querySelectorAll('.ticket-row-check');

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            rowChecks.forEach(function (cb) {
                cb.checked = checkAll.checked;
            });
        });
    }

    function submitBulkTickets() {
        var action = document.getElementById('bulk-action-select').value;
        if (!action) {
            alert('Please select a bulk action.');
            return;
        }

        var checkedCount = document.querySelectorAll('.ticket-row-check:checked').length;
        if (checkedCount === 0) {
            alert('Please select at least one ticket.');
            return;
        }

        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete ' + checkedCount + ' tickets?')) {
                return;
            }
        }

        document.getElementById('bulk-tickets-form').submit();
    }
</script>

</x-layouts.dashboard>
