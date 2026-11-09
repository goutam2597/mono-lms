<x-layouts.dashboard title="Contact Messages - Mono LMS" active="messages" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Inbound Messages</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-none {{ $unreadCount > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                        {{ $unreadCount }} Unread
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Review contact form submissions, enterprise inquiries, and prospective partnership requests.</p>
            </div>
        </div>

        <!-- Messages List -->
        <div class="bg-white border border-slate-200 rounded-none shadow-xs overflow-hidden">
            <x-bulk-toolbar action="{{ route('admin.messages.bulk-destroy') }}" />
            <div class="px-5 py-2.5 bg-slate-50/50 border-b border-slate-100 flex items-center gap-3">
                <input type="checkbox" id="check-all" class="control" aria-label="Select All Messages">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Select All</span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($messages as $msg)
                    <div class="p-5 hover:bg-slate-50/60 transition-colors space-y-2 {{ ! $msg->is_read ? 'bg-emerald-50/20' : '' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox" class="control row-check shrink-0" name="ids[]" value="{{ $msg->id }}" form="bulk-form" aria-label="{{ $msg->subject }}">
                                @if(! $msg->is_read)
                                    <span class="w-2 h-2 rounded-none bg-emerald-500"></span>
                                @endif
                                <h3 class="text-xs font-bold text-slate-900">{{ $msg->subject }}</h3>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] text-slate-400">
                                <span>{{ $msg->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">{{ $msg->name }}</span>
                            <span>&bull;</span>
                            <a href="mailto:{{ $msg->email }}" class="text-[#344c3d] hover:underline">{{ $msg->email }}</a>
                            @if($msg->phone)
                                <span>&bull;</span>
                                <span>{{ $msg->phone }}</span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-600 pt-1 leading-relaxed">
                            {{ $msg->message }}
                        </p>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <form method="POST" action="{{ route('admin.messages.toggle', $msg) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn action-btn-ghost h-7 px-3 text-[10px]">
                                    {{ $msg->is_read ? 'Mark Unread' : 'Mark as Read' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" onsubmit="return confirm('Delete message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px]">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 text-xs">
                        Inbox is empty. No contact messages recorded.
                    </div>
                @endforelse
            </div>

            @if($messages->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>

</x-layouts.dashboard>
