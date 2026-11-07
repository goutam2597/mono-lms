<x-layouts.dashboard :title="__('admin.plans.title').' - Dreams LMS'" active="plans" userType="admin">

    <div class="space-y-6">
        <!-- Membership master switch (admin-dependent) -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.plans.membership') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.plans.membership_hint') }}</p>
            </div>
            <form method="POST" action="{{ route('admin.plans.membership-toggle') }}">
                @csrf
                <button type="submit" class="relative inline-flex h-7 w-14 items-center rounded-full transition-colors cursor-pointer {{ $membershipEnabled ? 'bg-[#03c95a]' : 'bg-gray-300' }}" aria-label="{{ __('admin.plans.membership') }}">
                    <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform {{ $membershipEnabled ? 'translate-x-8' : 'translate-x-1' }}"></span>
                </button>
            </form>
        </div>

        <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-[#191919]">{{ __('admin.plans.title') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.plans.subtitle') }}</p>
                </div>
                <a href="{{ route('admin.plans.create') }}" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs font-semibold px-4 py-2.5 rounded-full transition-colors whitespace-nowrap">+ {{ __('admin.plans.create') }}</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                            <th class="px-6 py-3 font-semibold">{{ __('app.common.name') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('admin.courses.price') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('admin.plans.subscribers') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('app.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($plans as $plan)
                            <tr class="hover:bg-[#f8fafb] transition-colors">
                                <td class="px-6 py-3.5">
                                    <p class="font-semibold text-[#191919]">
                                        {{ $plan->name }}
                                        @if ($plan->is_popular)
                                            <span class="ml-1 text-[10px] font-bold text-[#ff4667]">★ {{ __('membership.most_popular') }}</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">{{ \Illuminate\Support\Str::limit($plan->description ?? '', 60) }}</p>
                                </td>
                                <td class="px-6 py-3.5 font-semibold text-[#392c7d]">
                                    {{ __('app.common.currency') }}{{ number_format($plan->price, 2) }}
                                    <span class="text-xs text-gray-400 font-normal">/ {{ __('membership.'.$plan->billing_period) }}</span>
                                </td>
                                <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $plan->subscriptions_count }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $plan->is_active ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $plan->is_active ? __('app.common.active') : __('app.common.inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('app.common.edit') }}</a>
                                        <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">{{ __('app.common.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
