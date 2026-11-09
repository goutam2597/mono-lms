<x-layouts.dashboard title="Instructor Payouts - Mono LMS" active="payouts" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Instructor Payouts</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-none bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $stats['pending_count'] }} Pending Requests
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Review revenue share withdrawal requests, approve disbursements, and track payouts.</p>
            </div>
        </div>

        <!-- 2 Summary KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white border border-slate-200 rounded-none p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Payouts Queue</span>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">${{ number_format($stats['pending_amount'], 2) }}</p>
                    <span class="text-[11px] text-slate-400">{{ $stats['pending_count'] }} instructors waiting for disbursement</span>
                </div>
                <div class="w-12 h-12 rounded-none bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-none p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Processed & Disbursed</span>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">${{ number_format($stats['processed_amount'], 2) }}</p>
                    <span class="text-[11px] text-slate-400">{{ $stats['processed_count'] }} completed transfers</span>
                </div>
                <div class="w-12 h-12 rounded-none bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Payout Requests Table -->
        <div class="bg-white border border-slate-200 rounded-none shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between gap-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Withdrawal Requests</span>
                <div class="flex items-center gap-1.5 flex-wrap">
                    <a href="{{ route('admin.payouts.index') }}" class="text-[10px] uppercase tracking-[0.12em] font-semibold px-3 py-1.5 rounded-none border transition-colors {{ !request('status') ? 'bg-[#08131e] border-[#08131e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">All</a>
                    <a href="{{ route('admin.payouts.index', ['status' => 'pending']) }}" class="text-[10px] uppercase tracking-[0.12em] font-semibold px-3 py-1.5 rounded-none border transition-colors {{ request('status') === 'pending' ? 'bg-[#08131e] border-[#08131e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">Pending</a>
                    <a href="{{ route('admin.payouts.index', ['status' => 'processed']) }}" class="text-[10px] uppercase tracking-[0.12em] font-semibold px-3 py-1.5 rounded-none border transition-colors {{ request('status') === 'processed' ? 'bg-[#08131e] border-[#08131e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">Processed</a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 font-semibold text-[10px] uppercase tracking-wider">
                            <th class="py-3.5 px-4">Recipient</th>
                            <th class="py-3.5 px-4">Method</th>
                            <th class="py-3.5 px-4">Account / IBAN</th>
                            <th class="py-3.5 px-4">Amount</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Processed Date</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payouts as $payout)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        @if(!empty($payout->instructor?->avatar))
                                            <img src="{{ $payout->instructor->avatar }}" alt="" class="w-7 h-7 rounded-full object-cover shrink-0 ring-1 ring-slate-200">
                                        @else
                                            <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px] flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($payout->instructor?->name ?? 'I', 0, 1)) }}
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900 truncate max-w-[150px]">{{ $payout->instructor?->name ?? 'Instructor' }}</p>
                                            <p class="text-[10px] text-slate-400 truncate max-w-[150px]">{{ $payout->instructor?->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 uppercase text-[10px] font-semibold text-slate-600">
                                    {{ $payout->payment_method }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-700">
                                    {{ $payout->payout_account }}
                                </td>
                                <td class="py-3.5 px-4 font-extrabold text-slate-900">
                                    ${{ number_format($payout->amount, 2) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold uppercase tracking-[0.12em] border {{ $payout->status === 'processed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($payout->status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200') }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-400 whitespace-nowrap text-[11px]">
                                    {{ $payout->processed_at ? $payout->processed_at->format('M d, Y') : '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($payout->status === 'pending')
                                            <!-- Mark Processed -->
                                            <form method="POST" action="{{ route('admin.payouts.update', $payout) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="processed">
                                                <button type="submit" class="action-btn action-btn-primary h-7 px-3 text-[10px]">
                                                    Mark Paid
                                                </button>
                                            </form>

                                            <!-- Reject -->
                                            <form method="POST" action="{{ route('admin.payouts.update', $payout) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="action-btn action-btn-ghost h-7 px-3 text-[10px]">
                                                    Reject
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Completed</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">No payout records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payouts->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $payouts->links() }}
                </div>
            @endif
        </div>
    </div>

</x-layouts.dashboard>
