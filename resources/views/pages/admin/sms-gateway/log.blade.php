<x-layouts.dashboard title="SMS Logs - Mono LMS" active="sms_logs" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 36px; padding: 0 14px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn-ghost { color: #171717; background: #ffffff; border-color: #e5e5e5; }
    .action-btn-ghost:hover { color: #0a0a0a; background: #f5f5f5; border-color: #d4d4d4; }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900 dark:text-white">SMS Transmission Logs</h1>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Monitor outbound message delivery statuses, retry counts, errors, and forwarded inbound SMS.
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.sms-gateway.index') }}" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                Gateway Devices
            </a>
            <a href="{{ route('admin.settings.sms') }}" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                SMS Settings
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4 rounded-sm">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 block">Pending Queue</span>
            <span class="text-2xl font-bold text-neutral-900 dark:text-white mt-1 block">{{ $counts['pending'] }}</span>
        </div>
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4 rounded-sm">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-600 block">Claimed / Processing</span>
            <span class="text-2xl font-bold text-amber-600 mt-1 block">{{ $counts['processing'] }}</span>
        </div>
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4 rounded-sm">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600 block">Delivered / Sent</span>
            <span class="text-2xl font-bold text-emerald-600 mt-1 block">{{ $counts['sent'] }}</span>
        </div>
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4 rounded-sm">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-red-600 block">Failed / Exhausted</span>
            <span class="text-2xl font-bold text-red-600 mt-1 block">{{ $counts['failed'] }}</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="flex items-center justify-between gap-4 flex-wrap bg-white dark:bg-[#111111] border border-neutral-200 dark:border-neutral-800 p-4">
        <form method="GET" action="{{ route('admin.sms-gateway.log') }}" class="flex items-center gap-3 flex-wrap">
            <div>
                <select name="status" onchange="this.form.submit()" class="text-xs p-2 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded">
                    <option value="" @selected($status === 'all')>All Statuses</option>
                    <option value="pending" @selected($status === 'pending')>Pending</option>
                    <option value="processing" @selected($status === 'processing')>Processing</option>
                    <option value="sent" @selected($status === 'sent')>Sent</option>
                    <option value="failed" @selected($status === 'failed')>Failed</option>
                </select>
            </div>
            <div>
                <select name="kind" onchange="this.form.submit()" class="text-xs p-2 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded">
                    <option value="" @selected($kind === 'all')>All Kinds</option>
                    <option value="otp" @selected($kind === 'otp')>OTP Codes</option>
                    <option value="notice" @selected($kind === 'notice')>Course Notices</option>
                    <option value="test" @selected($kind === 'test')>Test Messages</option>
                    <option value="other" @selected($kind === 'other')>Other</option>
                </select>
            </div>
            @if ($status !== 'all' || $kind !== 'all')
                <a href="{{ route('admin.sms-gateway.log') }}" class="text-xs text-neutral-500 hover:text-neutral-900 dark:hover:text-white underline">Clear Filters</a>
            @endif
        </form>
    </div>

    <!-- Outbound Messages Table -->
    <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] shadow-xs overflow-hidden">
        <div class="p-4 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
            <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white">Outbound SMS Queue</h2>
            <span class="text-xs text-neutral-400">{{ $messages->total() }} total messages</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-[#161616] text-[11px] font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Recipient</th>
                        <th class="py-3 px-4">Kind</th>
                        <th class="py-3 px-4">Body</th>
                        <th class="py-3 px-4">Transport</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Attempts</th>
                        <th class="py-3 px-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-[#161616] transition-colors">
                            <td class="py-3 px-4 font-mono font-medium text-neutral-900 dark:text-white whitespace-nowrap">
                                {{ $msg->to_phone }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300">
                                    {{ $msg->kind }}
                                </span>
                            </td>
                            <td class="py-3 px-4 max-w-xs truncate text-neutral-700 dark:text-neutral-300 font-mono text-[11px]" title="{{ $msg->body }}">
                                {{ $msg->body }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-neutral-600 dark:text-neutral-400">
                                {{ $msg->device ? $msg->device->name : ucfirst($msg->provider) }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if ($msg->status === 'sent')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        &check; Sent
                                    </span>
                                @elseif ($msg->status === 'processing')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 animate-pulse">
                                        &bull; Processing
                                    </span>
                                @elseif ($msg->status === 'failed')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300" title="{{ $msg->error }}">
                                        &times; Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                        Pending
                                    </span>
                                @endif
                                @if ($msg->error)
                                    <span class="block text-[10px] text-red-500 truncate max-w-xs mt-0.5">{{ $msg->error }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-neutral-500">
                                {{ $msg->attempts }} / {{ $msg->max_attempts }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-neutral-500 text-[11px]">
                                {{ $msg->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-4 text-center text-neutral-400">No outbound SMS messages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($messages->hasPages())
            <div class="p-4 border-t border-neutral-100 dark:border-neutral-800">
                {{ $messages->links() }}
            </div>
        @endif
    </div>

    <!-- Inbound Messages Table -->
    @if ($inbound->isNotEmpty())
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] shadow-xs overflow-hidden">
            <div class="p-4 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white">Recent Inbound SMS Received by Phones</h2>
                <span class="text-xs text-neutral-400">Latest 25 incoming</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-[#161616] text-[11px] font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                            <th class="py-3 px-4">From</th>
                            <th class="py-3 px-4">Message Body</th>
                            <th class="py-3 px-4">Receiving SIM</th>
                            <th class="py-3 px-4">Device</th>
                            <th class="py-3 px-4">Received Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                        @foreach($inbound as $in)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-[#161616] transition-colors">
                                <td class="py-3 px-4 font-mono font-medium text-neutral-900 dark:text-white whitespace-nowrap">
                                    {{ $in->from_phone }}
                                </td>
                                <td class="py-3 px-4 text-neutral-800 dark:text-neutral-200 font-mono text-[11px]">
                                    {{ $in->body }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-neutral-600 dark:text-neutral-400 font-mono">
                                    {{ $in->sim_number ?: 'N/A' }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-neutral-600 dark:text-neutral-400">
                                    {{ $in->device?->name }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-neutral-500 text-[11px]">
                                    {{ $in->received_at ? $in->received_at->format('M d, Y H:i') : $in->created_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

</x-layouts.dashboard>
