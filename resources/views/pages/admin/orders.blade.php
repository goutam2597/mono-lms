<x-layouts.dashboard title="Orders & Sales - Mono LMS" active="orders" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Orders & Sales</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-none bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $orders->total() }} Total
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Track course enrollments, subscription invoices, and payment statuses.</p>
            </div>
        </div>

        <!-- 4 Summary KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200 rounded-none p-4 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Gross Sales</span>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">${{ number_format($stats['total_sales'], 2) }}</p>
                <span class="text-[10px] text-emerald-600 font-semibold uppercase tracking-wider">Cleared revenue</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-none p-4 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Paid Orders</span>
                <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['paid_count'] }}</p>
                <span class="text-[11px] text-slate-400">Successful transactions</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-none p-4 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Orders</span>
                <p class="text-2xl font-extrabold text-amber-500 mt-1">{{ $stats['pending_count'] }}</p>
                <span class="text-[11px] text-slate-400">Awaiting confirmation</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-none p-4 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Refunded</span>
                <p class="text-2xl font-extrabold text-rose-500 mt-1">{{ $stats['refunded_count'] }}</p>
                <span class="text-[11px] text-slate-400">Returned transactions</span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="bg-white border border-slate-200 rounded-none p-4 shadow-xs flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5 flex-1 min-w-[260px]">
                <div class="relative flex-1 max-w-sm">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order #, student or course..." class="h-9 w-full pl-9 pr-3.5 text-xs border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                </div>

                <select name="status" onchange="this.form.submit()" data-mselect data-mselect-no-search class="h-9 w-auto flex-none text-xs border border-slate-200 rounded-none px-3 bg-white focus:outline-none focus:border-slate-900">
                    <option value="">All Statuses</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>

                <select name="method" onchange="this.form.submit()" data-mselect data-mselect-no-search class="h-9 w-auto flex-none text-xs border border-slate-200 rounded-none px-3 bg-white focus:outline-none focus:border-slate-900">
                    <option value="">All Methods</option>
                    <option value="stripe" {{ request('method') === 'stripe' ? 'selected' : '' }}>Stripe</option>
                    <option value="paypal" {{ request('method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                    <option value="razorpay" {{ request('method') === 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                    <option value="offline" {{ request('method') === 'offline' ? 'selected' : '' }}>Bank / Manual</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                @if(request()->anyFilled(['search', 'status', 'method']))
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold px-3 py-2 uppercase tracking-wider">Reset</a>
                @endif
                <button type="submit" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                    Filter
                </button>
            </div>
        </form>

        <!-- Orders Table -->
        <div class="bg-white border border-slate-200 rounded-none shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 font-semibold text-[10px] uppercase tracking-wider">
                            <th class="py-3.5 px-4">Order #</th>
                            <th class="py-3.5 px-4">Customer</th>
                            <th class="py-3.5 px-4">Item / Plan</th>
                            <th class="py-3.5 px-4">Total</th>
                            <th class="py-3.5 px-4">Method</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Date</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-[#344c3d]">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        @if($order->student?->avatarUrl())
                                            <img src="{{ $order->student->avatarUrl() }}" alt="" class="w-7 h-7 rounded-full object-cover shrink-0 ring-1 ring-slate-200" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                            <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px] items-center justify-center shrink-0 hidden">
                                                {{ strtoupper(substr($order->student?->name ?? 'S', 0, 1)) }}
                                            </span>
                                        @else
                                            <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px] flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($order->student?->name ?? 'S', 0, 1)) }}
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900 truncate max-w-[140px]">{{ $order->student?->name ?? 'Guest User' }}</p>
                                            <p class="text-[10px] text-slate-400 truncate max-w-[140px]">{{ $order->student?->email ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-slate-700 max-w-[200px] truncate">
                                    {{ $order->course?->trans('title') ?? ($order->plan?->name ? $order->plan->name.' Membership' : 'Curriculum Access') }}
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    ${{ number_format($order->amount, 2) }}
                                </td>
                                <td class="py-3.5 px-4 uppercase text-[10px] font-semibold text-slate-500">
                                    {{ $order->payment_method }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $badgeClass = match($order->payment_status) {
                                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                             'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'refunded' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'failed', 'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                                            default => 'bg-slate-100 text-slate-600 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold uppercase tracking-[0.12em] border {{ $badgeClass }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                    @if ($order->isAwaitingVerification())
                                        <span class="mt-1 block text-[10px] font-mono text-blue-700">Trx: {{ $order->payment_reference }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-400 whitespace-nowrap text-[11px]">
                                    {{ $order->created_at?->format('M d, Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" class="action-btn" data-menu-toggle="order-menu-{{ $order->id }}" aria-haspopup="true">
                                        Actions ▾
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    No orders found matching your search criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Row action menus (fixed position via JS, kept outside table overflow) --}}
    @foreach ($orders as $order)
        <div class="row-action-menu" id="order-menu-{{ $order->id }}" role="menu">
            <a href="{{ route('admin.orders.show', $order) }}" class="menu-item">View Receipt</a>
            <div class="menu-sep"></div>

            {{-- Course purchases with a submitted payment go through verification (activates enrollment) --}}
            @if ($order->course_id && $order->payment_status !== 'paid' && $order->payment_status !== 'cancelled')
                <form method="POST" action="{{ route('admin.orders.verify', $order) }}">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="menu-item font-semibold">✓ Approve Payment (enroll)</button>
                </form>
                @if ($order->payment_status === 'pending')
                    <form method="POST" action="{{ route('admin.orders.verify', $order) }}" onsubmit="return confirm('Reject this payment? The student will be notified.')">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="menu-item danger">✕ Reject Payment</button>
                    </form>
                @endif
                <div class="menu-sep"></div>
            @endif

            @if ($order->payment_status !== 'paid' && ! $order->course_id)
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="payment_status" value="paid">
                    <button type="submit" class="menu-item">Mark Paid</button>
                </form>
            @endif
            @if ($order->payment_status !== 'pending' && $order->payment_status !== 'paid')
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="payment_status" value="pending">
                    <button type="submit" class="menu-item">Mark Pending</button>
                </form>
            @endif
            @if ($order->payment_status !== 'refunded')
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" onsubmit="return confirm('Refund this order?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="payment_status" value="refunded">
                    <button type="submit" class="menu-item danger">Mark Refunded</button>
                </form>
            @endif
        </div>
    @endforeach

</x-layouts.dashboard>
