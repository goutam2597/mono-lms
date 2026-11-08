<x-layouts.dashboard title="Contact Messages - Mono LMS" active="messages" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Inbound Messages</h1>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $unreadCount > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-600' }}">
                        {{ $unreadCount }} Unread
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Review contact form submissions, enterprise inquiries, and prospective partnership requests.</p>
            </div>
        </div>

        <!-- Messages List -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($messages as $msg)
                    <div class="p-5 hover:bg-slate-50/60 transition-colors space-y-2 {{ ! $msg->is_read ? 'bg-emerald-50/20' : '' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                @if(! $msg->is_read)
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
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
                                <button type="submit" class="text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 transition-colors">
                                    {{ $msg->is_read ? 'Mark Unread' : 'Mark as Read' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" onsubmit="return confirm('Delete message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-slate-200 text-rose-600 hover:bg-rose-50 transition-colors">
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
