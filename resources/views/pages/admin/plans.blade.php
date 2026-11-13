<x-layouts.dashboard :title="__('admin.plans.title').' - Mono LMS'" active="plans" userType="admin">

    <style>
        #plan-modal { position: fixed; inset: 0; z-index: 120; display: none; align-items: center; justify-content: center; background: rgba(10, 10, 10, 0.65); backdrop-filter: blur(4px); padding: 24px; box-sizing: border-box; }
        #plan-modal.open { display: flex !important; }
        #plan-modal .panel { background: #ffffff; width: 540px; max-width: 100%; max-height: calc(100vh - 48px); overflow-y: auto; padding: 24px; border-radius: 0 !important; border: 1px solid #e5e5e5; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15); box-sizing: border-box; }
    </style>

    <div class="space-y-6">
        <!-- Membership master switch (admin-dependent) -->
        <div class="bg-white border border-[#e7e7e7] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.plans.membership') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.plans.membership_hint') }}</p>
            </div>
            <form method="POST" action="{{ route('admin.plans.membership-toggle') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="relative inline-flex h-7 w-14 items-center transition-colors cursor-pointer {{ $membershipEnabled ? 'bg-[#03c95a]' : 'bg-gray-300' }}" aria-label="{{ __('admin.plans.membership') }}">
                    <span class="inline-block h-5 w-5 transform bg-white shadow transition-transform {{ $membershipEnabled ? 'translate-x-8' : 'translate-x-1' }}"></span>
                </button>
            </form>
        </div>

        <div class="bg-white border border-[#e7e7e7] overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-[#191919]">{{ __('admin.plans.title') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.plans.subtitle') }}</p>
                </div>
                <button type="button" id="add-plan-btn" class="action-btn action-btn-primary h-10 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">+ {{ __('admin.plans.create') }}</button>
            </div>

            <x-bulk-toolbar action="{{ route('admin.plans.bulk-destroy') }}" />

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                            <th class="w-10 px-6 py-3">
                                <input type="checkbox" id="check-all" class="control" aria-label="Select All">
                            </th>
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
                                <td class="w-10 px-6 py-3.5">
                                    <input type="checkbox" class="control row-check" name="ids[]" value="{{ $plan->id }}" form="bulk-form" aria-label="{{ $plan->name }}">
                                </td>
                                <td class="px-6 py-3.5">
                                    <p class="font-semibold text-[#191919]">
                                        {{ $plan->name }}
                                        @if ($plan->is_popular)
                                            <span class="ml-1 text-[10px] font-bold text-[#738a6e]">★ {{ __('membership.most_popular') }}</span>
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
                                    <span class="inline-flex items-center justify-center h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] {{ $plan->is_active ? 'bg-[#03c95a]/10 text-[#0d7a38] border border-[#03c95a]/20' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                        {{ $plan->is_active ? __('app.common.active') : __('app.common.inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                class="edit-plan-btn action-btn action-btn-ghost h-7 px-3 text-[10px]"
                                                data-action="{{ route('admin.plans.update', $plan) }}"
                                                data-plan="{{ json_encode([
                                                    'id' => $plan->id,
                                                    'name' => $plan->name,
                                                    'description' => $plan->description,
                                                    'price' => $plan->price,
                                                    'billing_period' => $plan->billing_period,
                                                    'sort_order' => $plan->sort_order,
                                                    'features' => implode("\n", $plan->features ?? []),
                                                    'is_active' => (bool)$plan->is_active,
                                                    'is_popular' => (bool)$plan->is_popular,
                                                ]) }}">
                                            {{ __('app.common.edit') }}
                                        </button>
                                        <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px]">{{ __('app.common.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Plan Add / Edit Modal (mono_market pattern) -->
    <div id="plan-modal" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="panel panel-md space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
                <h3 class="text-base font-bold text-neutral-900" id="plan-modal-heading">{{ __('admin.plans.create') }}</h3>
                <button type="button" id="plan-modal-close" class="text-neutral-400 hover:text-neutral-900 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" id="plan-form" action="{{ route('admin.plans.store') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" id="plan-form-method" value="POST" disabled>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.name') }} *</label>
                    <input type="text" name="name" id="plan-name" required maxlength="100" class="h-11 w-full border border-neutral-300 rounded-lg px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('admin.plans.description') }}</label>
                    <textarea name="description" id="plan-description" rows="2" class="w-full border border-neutral-300 rounded-lg p-4 text-sm focus:outline-none focus:border-neutral-900"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('admin.courses.price') }} *</label>
                        <input type="number" step="0.01" min="0" name="price" id="plan-price" required class="h-11 w-full border border-neutral-300 rounded-lg px-4 text-sm focus:outline-none focus:border-neutral-900">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('admin.plans.billing_period') }}</label>
                        <select name="billing_period" id="plan-billing-period" data-mselect data-mselect-no-search class="h-11 w-full border border-neutral-300 rounded-lg px-4 text-sm focus:outline-none focus:border-neutral-900 bg-white">
                            <option value="monthly">{{ __('membership.monthly') }}</option>
                            <option value="yearly">{{ __('membership.yearly') }}</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('instructor.lessons.fields.sort_order') }}</label>
                        <input type="number" name="sort_order" id="plan-sort-order" min="0" value="0" class="h-11 w-full border border-neutral-300 rounded-lg px-4 text-sm focus:outline-none focus:border-neutral-900">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('admin.plans.features') }}</label>
                    <textarea name="features" id="plan-features" rows="4" placeholder="One feature per line" class="w-full border border-neutral-300 rounded-lg p-4 text-sm focus:outline-none focus:border-neutral-900"></textarea>
                    <p class="text-[11px] text-neutral-400">{{ __('admin.plans.features_hint') }}</p>
                </div>

                <div class="flex flex-wrap items-center gap-6 pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none text-xs text-neutral-800 font-medium">
                        <input type="checkbox" name="is_active" id="plan-active" value="1" class="control">
                        <span>{{ __('app.common.active') }}</span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer select-none text-xs text-neutral-800 font-medium">
                        <input type="checkbox" name="is_popular" id="plan-popular" value="1" class="control">
                        <span>{{ __('membership.most_popular') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-neutral-200">
                    <button type="button" id="plan-modal-cancel" class="action-btn action-btn-ghost h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">{{ __('app.common.cancel') }}</button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">{{ __('app.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal       = document.getElementById('plan-modal');
        var form        = document.getElementById('plan-form');
        var heading     = document.getElementById('plan-modal-heading');
        var method      = document.getElementById('plan-form-method');
        var addBtn      = document.getElementById('add-plan-btn');
        var closeBtn    = document.getElementById('plan-modal-close');
        var cancelBtn   = document.getElementById('plan-modal-cancel');
        var nameIn      = document.getElementById('plan-name');
        var descIn      = document.getElementById('plan-description');
        var priceIn     = document.getElementById('plan-price');
        var periodIn    = document.getElementById('plan-billing-period');
        var sortIn      = document.getElementById('plan-sort-order');
        var featIn      = document.getElementById('plan-features');
        var activeIn    = document.getElementById('plan-active');
        var popularIn   = document.getElementById('plan-popular');
        var storeUrl    = @json(route('admin.plans.store'));

        function openAdd() {
            heading.textContent = @json(__('admin.plans.create'));
            form.action = storeUrl;
            method.disabled = true;
            nameIn.value = '';
            descIn.value = '';
            priceIn.value = '';
            periodIn.value = 'monthly';
            sortIn.value = '0';
            featIn.value = '';
            activeIn.checked = true;
            popularIn.checked = false;
            modal.classList.add('open');
            setTimeout(function () { nameIn.focus(); }, 100);
        }

        function openEdit(data, action) {
            heading.textContent = @json(__('admin.plans.edit'));
            form.action = action;
            method.disabled = false;
            method.value = 'PUT';
            nameIn.value = data.name || '';
            descIn.value = data.description || '';
            priceIn.value = data.price || 0;
            periodIn.value = data.billing_period || 'monthly';
            sortIn.value = data.sort_order || 0;
            featIn.value = data.features || '';
            activeIn.checked = Boolean(data.is_active);
            popularIn.checked = Boolean(data.is_popular);
            modal.classList.add('open');
            setTimeout(function () { nameIn.focus(); }, 100);
        }

        function closeModal() {
            modal.classList.remove('open');
        }

        if (addBtn) addBtn.addEventListener('click', openAdd);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        document.querySelectorAll('.edit-plan-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var data = JSON.parse(btn.dataset.plan || '{}');
                openEdit(data, btn.dataset.action);
            });
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });
    });
    </script>

</x-layouts.dashboard>
