<x-layouts.dashboard title="Contact Messages - Mono LMS" active="messages" userType="admin">

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

    /* Modal Backdrop */
    .modal-backdrop { position: fixed; inset: 0; z-index: 100; display: none; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(2px); padding: 16px; }
    .modal-backdrop.open { display: flex; }
</style>

<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900 dark:text-white">Inbound Messages</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $stats['unread'] > 0 ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : 'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700' }} rounded-sm">
                    @if($stats['unread'] > 0)
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    @endif
                    {{ $stats['unread'] }} Unread
                </span>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Review contact form submissions, enterprise inquiries, and direct partnership requests.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.messages.export') }}" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
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
        <a href="{{ route('admin.messages.index') }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-neutral-400 dark:hover:border-neutral-600 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400">Total Inquiries</span>
            <p class="text-2xl font-bold text-neutral-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
            <span class="text-[11px] text-neutral-400">All contact submissions</span>
        </a>
        <a href="{{ route('admin.messages.index', ['tab' => 'unread']) }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-amber-400 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Unread</span>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['unread'] }}</p>
            <span class="text-[11px] text-neutral-400">Awaiting review</span>
        </a>
        <a href="{{ route('admin.messages.index', ['tab' => 'replied']) }}" class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs transition hover:border-emerald-400 block">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Replied</span>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['replied'] }}</p>
            <span class="text-[11px] text-neutral-400">Answered via email</span>
        </a>
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4.5 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Received Today</span>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['today'] }}</p>
            <span class="text-[11px] text-neutral-400">New leads &amp; requests</span>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] shadow-xs">
        <!-- Tabs & Search Controls -->
        <div class="p-4 border-b border-neutral-100 dark:border-neutral-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Tabs -->
            <div class="flex items-center gap-1 overflow-x-auto pb-1 md:pb-0">
                <a href="{{ route('admin.messages.index', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $currentTab === 'all' ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 border-neutral-900 dark:border-white' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    All ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.messages.index', array_merge(request()->except('tab', 'page'), ['tab' => 'unread'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $currentTab === 'unread' ? 'bg-amber-600 text-white border-amber-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    Unread ({{ $stats['unread'] }})
                </a>
                <a href="{{ route('admin.messages.index', array_merge(request()->except('tab', 'page'), ['tab' => 'read'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $currentTab === 'read' ? 'bg-neutral-600 text-white border-neutral-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    Read ({{ $stats['read'] }})
                </a>
                <a href="{{ route('admin.messages.index', array_merge(request()->except('tab', 'page'), ['tab' => 'replied'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider border rounded-sm transition {{ $currentTab === 'replied' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-transparent border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400' }}">
                    Replied ({{ $stats['replied'] }})
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.messages.index') }}" class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                @if(request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
                <div class="relative flex-1 min-w-[220px]">
                    <svg class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search sender, email, subject..."
                           class="h-9 w-full pl-9 pr-3 text-xs bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 text-neutral-900 dark:text-white rounded-sm focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>
                <button type="submit" class="action-btn action-btn-ghost h-9 px-3 text-xs">
                    Search
                </button>
                @if(request('search') || (request('tab') && request('tab') !== 'all'))
                    <a href="{{ route('admin.messages.index') }}" class="action-btn action-btn-ghost h-9 px-3 text-xs" title="Reset Filters">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Bulk Action Bar -->
        <form id="bulk-messages-form" method="POST" action="{{ route('admin.messages.bulk-action') }}">
            @csrf
            <div class="px-5 py-2.5 bg-neutral-50/80 dark:bg-neutral-900/60 border-b border-neutral-100 dark:border-neutral-800 flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="check-all-messages" class="control" aria-label="Select all messages">
                    <label for="check-all-messages" class="text-[11px] font-bold uppercase tracking-wider text-neutral-600 dark:text-neutral-400 cursor-pointer">
                        Select All In View
                    </label>
                </div>
                <div class="flex items-center gap-2">
                    <select name="action" id="bulk-msg-action" class="h-8 text-[11px] bg-white dark:bg-[#151515] border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white px-2.5 rounded-sm focus:outline-none">
                        <option value="">Bulk Actions...</option>
                        <option value="mark_read">Mark as Read</option>
                        <option value="mark_unread">Mark as Unread</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="button" onclick="submitBulkMessages()" class="action-btn action-btn-ghost h-8 px-3 text-[10px]">
                        Apply
                    </button>
                </div>
            </div>

            <!-- Messages List -->
            <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @forelse($messages as $msg)
                    <div class="p-6 hover:bg-neutral-50/50 dark:hover:bg-neutral-900/30 transition-colors space-y-3.5 {{ ! $msg->is_read ? 'bg-amber-50/20 dark:bg-amber-950/10' : '' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                            <div class="flex items-center gap-3 flex-wrap">
                                <input type="checkbox" name="ids[]" value="{{ $msg->id }}" class="msg-row-check control" aria-label="Select message #{{ $msg->id }}">
                                @if(! $msg->is_read)
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse flex-none" title="Unread Message"></span>
                                @endif
                                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $msg->subject }}</h3>
                                @if(! $msg->is_read)
                                    <span class="text-[10px] font-bold px-2 py-0.5 uppercase tracking-wider bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800 rounded-xs">
                                        Unread
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold px-2 py-0.5 uppercase tracking-wider bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700 rounded-xs">
                                        Read
                                    </span>
                                @endif

                                @if($msg->isReplied())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 uppercase tracking-wider bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xs">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Replied
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-xs text-neutral-400">
                                <span>{{ $msg->created_at?->diffForHumans() }}</span>
                                <span>&bull;</span>
                                <span class="font-mono text-[11px]">{{ $msg->created_at?->format('M d, Y H:i') }}</span>
                            </div>
                        </div>

                        <!-- Sender Details -->
                        <div class="flex items-center gap-3 text-xs text-neutral-600 dark:text-neutral-400 flex-wrap">
                            <span class="font-semibold text-neutral-900 dark:text-white">{{ $msg->name }}</span>
                            <span>&bull;</span>
                            <a href="mailto:{{ $msg->email }}" class="text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline">
                                {{ $msg->email }}
                            </a>
                            @if($msg->phone)
                                <span>&bull;</span>
                                <a href="tel:{{ $msg->phone }}" class="flex items-center gap-1 text-neutral-700 dark:text-neutral-300 hover:underline">
                                    <svg class="w-3 h-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <span>{{ $msg->phone }}</span>
                                </a>
                            @endif
                        </div>

                        <!-- Inbound Message Body -->
                        <div class="text-xs text-neutral-700 dark:text-neutral-300 bg-neutral-50 dark:bg-[#151515] p-4 border border-neutral-200 dark:border-neutral-800 rounded-sm leading-relaxed whitespace-pre-line">
                            {{ $msg->message }}
                        </div>

                        <!-- Existing Staff Reply -->
                        @if($msg->reply)
                            <div class="text-xs text-emerald-900 dark:text-emerald-200 bg-emerald-50/70 dark:bg-emerald-950/30 p-4 border border-emerald-200 dark:border-emerald-800/80 rounded-sm space-y-1">
                                <div class="flex items-center justify-between text-[11px] font-semibold text-emerald-800 dark:text-emerald-300 mb-1">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                        Staff Email Response:
                                    </span>
                                    <span class="font-mono text-[10px] opacity-75">
                                        {{ $msg->replied_at ? $msg->replied_at->diffForHumans() : 'Sent' }}
                                    </span>
                                </div>
                                <p class="leading-relaxed whitespace-pre-line">{{ $msg->reply }}</p>
                            </div>
                        @endif

                        <!-- Action Buttons & Quick Reply -->
                        <div class="pt-2 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-between gap-3 flex-wrap">
                            <button type="button" onclick="openReplyModal({{ $msg->id }}, '{{ addslashes($msg->name) }}', '{{ addslashes($msg->email) }}', '{{ addslashes($msg->subject) }}')" class="action-btn action-btn-primary h-8 px-3.5 text-[10px]">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                {{ $msg->reply ? 'Send Another Reply' : 'Reply via Email' }}
                            </button>

                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.messages.toggle', $msg) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn action-btn-ghost h-8 px-3 text-[10px]">
                                        {{ $msg->is_read ? 'Mark as Unread' : 'Mark as Read' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" onsubmit="return confirm('Delete this message?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger h-8 px-3 text-[10px]">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center text-neutral-400 dark:text-neutral-500 text-xs space-y-2">
                        <svg class="w-10 h-10 mx-auto text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <p class="font-medium text-neutral-600 dark:text-neutral-400">Inbox is empty or no messages match the selected filter.</p>
                        <a href="{{ route('admin.messages.index') }}" class="inline-block text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline">
                            Clear filters &amp; view all messages
                        </a>
                    </div>
                @endforelse
            </div>
        </form>

        @if($messages->hasPages())
            <div class="p-4 border-t border-neutral-100 dark:border-neutral-800">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Reply via Email Modal -->
<div id="reply-message-modal" class="modal-backdrop">
    <div class="bg-white dark:bg-[#111111] border border-neutral-200 dark:border-neutral-800 w-full max-w-lg p-6 shadow-xl space-y-4 rounded-sm">
        <div class="flex items-center justify-between border-b border-neutral-100 dark:border-neutral-800 pb-3">
            <div>
                <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Reply to Inbound Inquiry</h2>
                <p id="reply-recipient-info" class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeReplyModal()" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="reply-message-form" method="POST" action="" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Email Subject *</label>
                <input type="text" name="subject" id="reply-subject-input" required
                       class="h-9 w-full text-xs px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none focus:border-neutral-900">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-1">Your Response Message *</label>
                <textarea name="reply" id="reply-body-input" rows="5" required placeholder="Type the email response to send..."
                          class="w-full text-xs p-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-white rounded-sm focus:outline-none focus:border-neutral-900"></textarea>
            </div>

            <label class="flex items-center gap-2 cursor-pointer text-xs text-neutral-700 dark:text-neutral-300">
                <input type="checkbox" name="send_email" value="1" checked class="control">
                <span>Dispatch real email notification via SMTP to sender</span>
            </label>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-neutral-100 dark:border-neutral-800">
                <button type="button" onclick="closeReplyModal()" class="action-btn action-btn-ghost text-xs">
                    Cancel
                </button>
                <button type="submit" class="action-btn action-btn-primary text-xs">
                    Send Reply
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReplyModal(msgId, name, email, subject) {
        var modal = document.getElementById('reply-message-modal');
        var form = document.getElementById('reply-message-form');
        var info = document.getElementById('reply-recipient-info');
        var subjectInput = document.getElementById('reply-subject-input');

        form.action = "{{ url('admin/messages') }}/" + msgId + "/reply";
        info.textContent = "Sending to " + name + " (" + email + ")";
        subjectInput.value = subject.startsWith('Re:') ? subject : 'Re: ' + subject;

        modal.classList.add('open');
    }

    function closeReplyModal() {
        document.getElementById('reply-message-modal').classList.remove('open');
    }

    // Check all functionality
    var checkAll = document.getElementById('check-all-messages');
    var rowChecks = document.querySelectorAll('.msg-row-check');

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            rowChecks.forEach(function (cb) {
                cb.checked = checkAll.checked;
            });
        });
    }

    function submitBulkMessages() {
        var action = document.getElementById('bulk-msg-action').value;
        if (!action) {
            alert('Please select a bulk action.');
            return;
        }

        var checkedCount = document.querySelectorAll('.msg-row-check:checked').length;
        if (checkedCount === 0) {
            alert('Please select at least one message.');
            return;
        }

        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete ' + checkedCount + ' messages?')) {
                return;
            }
        }

        document.getElementById('bulk-messages-form').submit();
    }
</script>

</x-layouts.dashboard>
