<x-layouts.dashboard title="Support Tickets - Mono LMS" active="tickets" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Support Help Desk</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-none bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $stats['open'] }} Open Inquiries
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage and resolve learner questions, technical issues, and billing requests.</p>
            </div>
        </div>

        <!-- 3 KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-slate-200 rounded-none p-5 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Open Queue</span>
                <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $stats['open'] }}</p>
                <span class="text-[11px] text-slate-400">Needs staff attention</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-none p-5 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">In Progress</span>
                <p class="text-2xl font-extrabold text-amber-500 mt-1">{{ $stats['in_progress'] }}</p>
                <span class="text-[11px] text-slate-400">Being handled</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-none p-5 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Resolved</span>
                <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['resolved'] }}</p>
                <span class="text-[11px] text-slate-400">Successfully closed</span>
            </div>
        </div>

        <!-- Tickets List -->
        <div class="bg-white border border-slate-200 rounded-none shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between gap-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Support Queue</span>
                <div class="flex items-center gap-1.5 flex-wrap">
                    <a href="{{ route('admin.tickets.index') }}" class="text-[10px] uppercase tracking-[0.12em] font-semibold px-3 py-1.5 rounded-none border transition-colors {{ !request('status') ? 'bg-[#08131e] border-[#08131e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">All</a>
                    <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="text-[10px] uppercase tracking-[0.12em] font-semibold px-3 py-1.5 rounded-none border transition-colors {{ request('status') === 'open' ? 'bg-[#08131e] border-[#08131e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">Open</a>
                    <a href="{{ route('admin.tickets.index', ['status' => 'in_progress']) }}" class="text-[10px] uppercase tracking-[0.12em] font-semibold px-3 py-1.5 rounded-none border transition-colors {{ request('status') === 'in_progress' ? 'bg-[#08131e] border-[#08131e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">In Progress</a>
                    <a href="{{ route('admin.tickets.index', ['status' => 'resolved']) }}" class="text-[10px] uppercase tracking-[0.12em] font-semibold px-3 py-1.5 rounded-none border transition-colors {{ request('status') === 'resolved' ? 'bg-[#08131e] border-[#08131e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">Resolved</a>
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($tickets as $ticket)
                    <div class="p-5 hover:bg-slate-50/60 transition-colors space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-xs font-bold text-[#344c3d]">#{{ $ticket->ticket_number }}</span>
                                <h3 class="text-xs font-bold text-slate-900">{{ $ticket->subject }}</h3>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-none uppercase tracking-wider {{ $ticket->priority === 'high' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ $ticket->priority }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] text-slate-400">{{ $ticket->created_at?->diffForHumans() }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold uppercase tracking-[0.12em] border {{ $ticket->status === 'resolved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($ticket->status === 'in_progress' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-blue-50 text-blue-700 border-blue-200') }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="text-xs text-slate-600 bg-slate-50 p-3 rounded-none border border-slate-200">
                            <p class="font-semibold text-slate-800 text-[11px] mb-0.5">{{ $ticket->user_name }} ({{ $ticket->user_email }}):</p>
                            <p>{{ $ticket->message }}</p>
                        </div>

                        @if($ticket->reply)
                            <div class="text-xs text-emerald-900 bg-emerald-50/80 p-3 rounded-none border border-emerald-200">
                                <p class="font-semibold text-emerald-800 text-[11px] mb-0.5">Staff Reply:</p>
                                <p>{{ $ticket->reply }}</p>
                            </div>
                        @endif

                        <!-- Quick Reply Form -->
                        <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" class="flex flex-col sm:flex-row items-center gap-2 pt-1">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="reply" placeholder="Type staff response..." required class="text-xs flex-1 px-3.5 py-2 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                            <select name="status" data-mselect data-mselect-no-search class="text-[10px] font-bold uppercase tracking-wider border border-slate-200 rounded-none px-3 py-2 bg-white focus:outline-none focus:border-slate-900">
                                <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            <button type="submit" class="action-btn action-btn-primary h-8 px-4 text-[10px]">
                                Send Reply
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 text-xs">
                        No support tickets in this queue.
                    </div>
                @endforelse
            </div>

            @if($tickets->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>

</x-layouts.dashboard>
