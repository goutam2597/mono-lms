<x-layouts.dashboard title="Help & Support - Instructor Portal" active="tickets" userType="instructor">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 36px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn-primary { color: #fff; background: var(--theme-primary, #344c3d); border: 1px solid var(--theme-primary, #344c3d); }
    .action-btn-primary:hover { color: #fff; background: var(--theme-primary-hover, #25372c); border-color: var(--theme-primary-hover, #25372c); }
    .action-btn-ghost { color: #171717; background: #ffffff; border-color: #e5e5e5; }
    .action-btn-ghost:hover { color: #0a0a0a; background: #f5f5f5; border-color: #d4d4d4; }
    .dark .action-btn-ghost { color: #f5f5f5; background: #171717; border-color: #262626; }
    .dark .action-btn-ghost:hover { color: #ffffff; background: #262626; border-color: #404040; }

    .modal-backdrop { position: fixed; inset: 0; z-index: 100; display: none; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(2px); padding: 16px; }
    .modal-backdrop.open { display: flex; }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900 dark:text-white">Instructor Help &amp; Support</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-sm">
                    Direct Support Desk
                </span>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Submit course approval queries, payout inquiries, or technical support requests directly to administrators.
            </p>
        </div>
        <div>
            <button type="button" onclick="openCreateModal()" class="action-btn action-btn-primary">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Submit New Ticket
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
        <a href="{{ route('instructor.tickets.index') }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-neutral-400 dark:hover:border-neutral-600 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400">Total Submitted</span>
            <p class="text-2xl font-bold text-neutral-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
            <span class="text-[11px] text-neutral-400">Your total support tickets</span>
        </a>
        <a href="{{ route('instructor.tickets.index', ['status' => 'open']) }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-blue-400 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Open Queue</span>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['open'] }}</p>
            <span class="text-[11px] text-neutral-400">Awaiting staff response</span>
        </a>
        <a href="{{ route('instructor.tickets.index', ['status' => 'in_progress']) }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-amber-400 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">In Progress</span>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['in_progress'] }}</p>
            <span class="text-[11px] text-neutral-400">Under staff review</span>
        </a>
        <a href="{{ route('instructor.tickets.index', ['status' => 'resolved']) }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-emerald-400 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Resolved / Closed</span>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['resolved'] }}</p>
            <span class="text-[11px] text-neutral-400">Completed inquiries</span>
        </a>
    </div>

    <!-- Ticket Container -->
    <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] shadow-xs">
        <!-- Search & Filter Controls -->
        <div class="p-4 border-b border-neutral-100 dark:border-neutral-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Status Tabs -->
            <div class="flex items-center gap-1 overflow-x-auto pb-1 md:pb-0">
                @php
                    $curStatus = request('status', 'all');
                @endphp
                <a href="{{ route('instructor.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $curStatus === 'all' ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 border-neutral-900 dark:border-white' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    All ({{ $stats['total'] }})
                </a>
                <a href="{{ route('instructor.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'open'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $curStatus === 'open' ? 'bg-blue-600 text-white border-blue-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    Open ({{ $stats['open'] }})
                </a>
                <a href="{{ route('instructor.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'in_progress'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $curStatus === 'in_progress' ? 'bg-amber-600 text-white border-amber-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    In Progress ({{ $stats['in_progress'] }})
                </a>
                <a href="{{ route('instructor.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'resolved'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $curStatus === 'resolved' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    Resolved ({{ $stats['resolved'] }})
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('instructor.tickets.index') }}" class="flex items-center gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative min-w-[220px]">
                    <svg class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ticket # or subject..."
                           class="h-9 w-full pl-9 pr-3 text-xs bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 text-neutral-900 dark:text-white rounded-sm focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>
                <button type="submit" class="action-btn action-btn-ghost h-9 px-3 text-xs">Search</button>
                @if(request('search') || (request('status') && request('status') !== 'all'))
                    <a href="{{ route('instructor.tickets.index') }}" class="action-btn action-btn-ghost h-9 px-3 text-xs" title="Reset Filters">Clear</a>
                @endif
            </form>
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
                @endphp

                <div class="p-6 hover:bg-neutral-50/50 dark:hover:bg-neutral-900/30 transition-colors space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                        <div class="flex items-center gap-3 flex-wrap">
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

                    <!-- Ticket Inquiry Body -->
                    <div class="text-xs text-neutral-700 dark:text-neutral-300 bg-neutral-50 dark:bg-[#151515] p-4 border border-neutral-200 dark:border-neutral-800 rounded-sm leading-relaxed whitespace-pre-line">
                        {{ $ticket->message }}
                    </div>

                    <!-- Staff Reply Thread -->
                    @if($ticket->reply)
                        <div class="text-xs text-emerald-900 dark:text-emerald-200 bg-emerald-50/70 dark:bg-emerald-950/30 p-4 border border-emerald-200 dark:border-emerald-800/80 rounded-sm space-y-1.5">
                            <div class="flex items-center justify-between text-[11px] font-semibold text-emerald-800 dark:text-emerald-300">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Official Staff Response:
                                </span>
                                <span class="font-mono text-[10px] opacity-75">
                                    {{ $ticket->replied_at ? $ticket->replied_at->diffForHumans() : 'Replied' }}
                                </span>
                            </div>
                            <p class="leading-relaxed whitespace-pre-line">{{ $ticket->reply }}</p>
                        </div>
                    @endif

                    <!-- Follow-up or Close Actions -->
                    <div class="pt-2 border-t border-neutral-100 dark:border-neutral-800/80 flex flex-col md:flex-row md:items-center justify-between gap-3">
                        @if($ticket->status !== 'closed')
                            <!-- Follow-up message form -->
                            <form method="POST" action="{{ route('instructor.tickets.reply', $ticket) }}" class="flex-1 flex flex-col sm:flex-row items-center gap-2">
                                @csrf
                                <input type="text" name="followup" placeholder="Add additional information or reply back..." required
                                       class="h-9 text-xs flex-1 min-w-0 w-full px-3.5 border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-[#151515] text-neutral-900 dark:text-white rounded-sm focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                                <button type="submit" class="action-btn action-btn-primary h-9 px-4 text-xs w-full sm:w-auto">
                                    Send Follow-up
                                </button>
                            </form>

                            <!-- Close Ticket Button -->
                            <form method="POST" action="{{ route('instructor.tickets.close', $ticket) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn action-btn-ghost h-9 px-3 text-xs w-full sm:w-auto" title="Mark inquiry as resolved">
                                    Close Ticket
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-neutral-400 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                This ticket is closed. If you need further help, please submit a new ticket.
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-16 text-center text-neutral-400 dark:text-neutral-500 text-xs space-y-3">
                    <svg class="w-10 h-10 mx-auto text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <p class="font-medium text-neutral-600 dark:text-neutral-400">You haven't submitted any support tickets yet.</p>
                    <button type="button" onclick="openCreateModal()" class="action-btn action-btn-primary text-xs">
                        Submit Your First Support Ticket
                    </button>
                </div>
            @endforelse
        </div>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-neutral-100 dark:border-neutral-800">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Submit Ticket Modal -->
<div id="create-ticket-modal" class="modal-backdrop">
    <div class="bg-white dark:bg-[#111111] border border-neutral-200 dark:border-neutral-800 w-full max-w-lg p-6 shadow-xl space-y-4 rounded-sm">
        <div class="flex items-center justify-between border-b border-neutral-100 dark:border-neutral-800 pb-3">
            <div>
                <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Submit Instructor Support Ticket</h2>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">Submitting as: {{ $instructor->name }} ({{ $instructor->email }})</p>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('instructor.tickets.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Subject / Issue Summary *</label>
                <input type="text" name="subject" required placeholder="e.g. Course video processing issue, Payout inquiry"
                       class="h-9 w-full text-xs px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none focus:border-neutral-900">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Priority Level *</label>
                <select name="priority" required class="h-9 w-full text-xs px-2.5 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none">
                    <option value="medium" selected>Medium (Standard inquiry)</option>
                    <option value="low">Low (General question)</option>
                    <option value="high">High (Course publication or student issue)</option>
                    <option value="urgent">Urgent (Platform blocking issue)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Inquiry Details *</label>
                <textarea name="message" rows="5" required placeholder="Please provide course title, lesson details, or specific error descriptions to help us resolve quickly..."
                          class="w-full text-xs p-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none focus:border-neutral-900"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-neutral-100 dark:border-neutral-800">
                <button type="button" onclick="closeCreateModal()" class="action-btn action-btn-ghost text-xs">
                    Cancel
                </button>
                <button type="submit" class="action-btn action-btn-primary text-xs">
                    Submit Ticket
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
</script>

</x-layouts.dashboard>
