<x-layouts.dashboard :title="'Order #'.$order->order_number.' - Mono LMS'" active="orders" userType="admin">

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Back Navigation & Actions -->
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-600 hover:text-slate-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Orders</span>
            </a>

            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="action-btn action-btn-ghost h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Invoice</span>
                </button>
            </div>
        </div>

        <!-- Official Order Invoice Sheet -->
        <div class="bg-white border border-slate-200 rounded-none p-6 sm:p-10 shadow-sm space-y-8">
            <!-- Header bar with branding & invoice # -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-slate-100 pb-8">
                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="Mono LMS" class="h-8 w-auto mb-2">
                    <p class="text-xs text-slate-500">Official Curriculum Enrollment Receipt</p>
                </div>
                <div class="sm:text-right">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Order Reference</span>
                    <h2 class="text-xl sm:text-2xl font-mono font-extrabold text-[#344c3d]">#{{ $order->order_number }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Date: {{ $order->created_at?->format('F d, Y - h:i A') }}</p>
                </div>
            </div>

            <!-- Customer & Payment Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider block mb-1.5">Billed To</span>
                    <p class="font-bold text-slate-900 text-sm">{{ $order->student?->name ?? 'Student' }}</p>
                    <p class="text-slate-500">{{ $order->student?->email }}</p>
                    <p class="text-slate-400 mt-1">Student ID: #{{ $order->student_id ?? 'N/A' }}</p>
                </div>

                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider block mb-1.5">Payment Details</span>
                    <p class="font-semibold text-slate-800">Method: <span class="uppercase text-slate-900 font-bold">{{ $order->payment_method }}</span></p>
                    <p class="text-slate-500">Currency: {{ $order->currency }}</p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold uppercase tracking-[0.12em] border {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider block mb-1.5">Fulfillment Status</span>
                    <p class="font-semibold text-emerald-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Immediate Course Access Granted</span>
                    </p>
                    <p class="text-slate-400 text-[11px] mt-1">Enrolled into learning dashboard</p>
                </div>
            </div>

            <!-- Itemized Table -->
            <div class="border border-slate-200 rounded-none overflow-hidden">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-semibold text-[10px] uppercase tracking-wider border-b border-slate-200">
                            <th class="py-3 px-4">Item Description</th>
                            <th class="py-3 px-4">Instructor / Provider</th>
                            <th class="py-3 px-4 text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-4 px-4">
                                <p class="font-bold text-slate-900 text-sm">
                                    {{ $order->course?->title ?? ($order->plan?->name ? $order->plan->name.' Membership Plan' : 'Curriculum Enrollment') }}
                                </p>
                                <p class="text-slate-500 text-[11px] mt-0.5">Lifetime full access with certificates & projects.</p>
                            </td>
                            <td class="py-4 px-4 text-slate-600">
                                {{ $order->course?->instructor?->name ?? 'Mono LMS Global Academy' }}
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-slate-900 text-sm">
                                ${{ number_format($order->amount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-50/80 border-t border-slate-200">
                        <tr>
                            <td colspan="2" class="py-3 px-4 text-right font-semibold text-slate-500">Subtotal</td>
                            <td class="py-3 px-4 text-right font-semibold text-slate-800">${{ number_format($order->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="py-2 px-4 text-right font-semibold text-slate-500">Taxes / VAT (0%)</td>
                            <td class="py-2 px-4 text-right font-semibold text-slate-800">$0.00</td>
                        </tr>
                        <tr class="border-t border-slate-200 text-slate-900">
                            <td colspan="2" class="py-3.5 px-4 text-right font-extrabold text-sm">Total Paid</td>
                            <td class="py-3.5 px-4 text-right font-extrabold text-base text-[#344c3d]">
                                ${{ number_format($order->amount, 2) }} {{ $order->currency }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Admin Status Update Tool in Sheet -->
            <div class="bg-slate-50 border border-slate-200 rounded-none p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h4 class="text-xs font-bold text-slate-800">Admin Actions for this Order</h4>
                    <p class="text-[11px] text-slate-500">Update payment status or issue a refund directly from the control panel.</p>
                </div>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="payment_status" class="text-[10px] font-bold uppercase tracking-wider border border-slate-200 rounded-none px-3 py-2 bg-white">
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
