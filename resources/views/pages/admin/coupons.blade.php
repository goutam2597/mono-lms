<x-layouts.dashboard title="Coupons & Discounts - Mono LMS" active="coupons" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Coupons & Discounts</h1>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $stats['active'] }} Active
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage promotional discount codes, percentage cuts, and redemption limits.</p>
            </div>

            <!-- Trigger Modal / Open creation -->
            <button type="button" onclick="document.getElementById('createCouponModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2.5 rounded-xl bg-[#344c3d] hover:bg-[#283a2f] text-white transition-colors shadow-xs shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Create Coupon</span>
            </button>
        </div>

        <!-- 3 KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Coupons</span>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</p>
                <span class="text-[11px] text-slate-400">Campaigns created</span>
            </div>
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Active Codes</span>
                <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['active'] }}</p>
                <span class="text-[11px] text-slate-400">Available at checkout</span>
            </div>
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Redemptions</span>
                <p class="text-2xl font-extrabold text-[#344c3d] mt-1">{{ $stats['total_redemptions'] }}</p>
                <span class="text-[11px] text-slate-400">Student uses so far</span>
            </div>
        </div>

        <!-- Coupons Table -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 font-semibold text-[11px] uppercase tracking-wider">
                            <th class="py-3.5 px-4">Coupon Code</th>
                            <th class="py-3.5 px-4">Campaign Title</th>
                            <th class="py-3.5 px-4">Type</th>
                            <th class="py-3.5 px-4">Value</th>
                            <th class="py-3.5 px-4">Usage Count</th>
                            <th class="py-3.5 px-4">Valid Until</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($coupons as $coupon)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-[#344c3d]">
                                    <span class="px-2 py-1 bg-emerald-50 border border-emerald-200/70 rounded-md">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">
                                    {{ $coupon->title }}
                                </td>
                                <td class="py-3.5 px-4 uppercase text-[10px] font-semibold text-slate-500">
                                    {{ $coupon->discount_type }}
                                </td>
                                <td class="py-3.5 px-4 font-extrabold text-slate-900">
                                    {{ $coupon->discount_type === 'percent' ? $coupon->discount_value.'%' : '$'.number_format($coupon->discount_value, 2) }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    <span class="font-bold text-slate-900">{{ $coupon->used_count }}</span>
                                    <span class="text-slate-400">/ {{ $coupon->max_uses ?? '∞' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                                    {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'No expiry' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $coupon->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                        {{ $coupon->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Toggle Status -->
                                        <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 transition-colors">
                                                {{ $coupon->is_active ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">No coupons created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($coupons->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $coupons->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Coupon Modal -->
    <div id="createCouponModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-5 animate-in fade-in zoom-in duration-150">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Create New Coupon</h3>
                <button type="button" onclick="document.getElementById('createCouponModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.coupons.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Coupon Code *</label>
                    <input type="text" name="code" required placeholder="e.g. SUMMER2026" class="w-full uppercase font-mono px-3.5 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Campaign Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Summer Special 25% Off" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Discount Type *</label>
                        <select name="discount_type" class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white focus:outline-none focus:border-[#344c3d]">
                            <option value="percent">Percentage (%)</option>
                            <option value="fixed">Fixed Dollar ($)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Discount Value *</label>
                        <input type="number" step="0.01" name="discount_value" required placeholder="e.g. 25" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Max Redemptions</label>
                        <input type="number" name="max_uses" placeholder="e.g. 100 (Blank = ∞)" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Expiration Date</label>
                        <input type="date" name="expires_at" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('createCouponModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#344c3d] text-white font-semibold rounded-xl hover:bg-[#283a2f] shadow-xs">
                        Create Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.dashboard>
