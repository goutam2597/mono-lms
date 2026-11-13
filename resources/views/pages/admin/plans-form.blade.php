<x-layouts.dashboard :title="($plan->exists ? __('admin.plans.edit') : __('admin.plans.create')).' - Mono LMS'" active="plans" userType="admin">

    <div class="bg-white border border-slate-200 shadow-xs p-6 sm:p-8 max-w-2xl">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="font-bold text-[#191919] text-base">{{ $plan->exists ? __('admin.plans.edit') : __('admin.plans.create') }}</h3>
            <p class="text-xs text-slate-500 mt-1">Configure subscription pricing tier, recurring frequency, and included member benefits.</p>
        </div>

        <form action="{{ $plan->exists ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($plan->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $plan->name) }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.plans.description') }}</label>
                <textarea name="description" rows="2" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">{{ old('description', $plan->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.courses.price') }} *</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $plan->price ?? 0) }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs font-mono focus:outline-none focus:border-[#344C3D]">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.plans.billing_period') }}</label>
                    <x-select name="billing_period"
                        :options="['monthly' => __('membership.monthly'), 'yearly' => __('membership.yearly')]"
                        :selected="old('billing_period', $plan->billing_period ?? 'monthly')"
                        :searchable="false" />
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('instructor.lessons.fields.sort_order') }}</label>
                    <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $plan->sort_order ?? 0) }}" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs font-mono focus:outline-none focus:border-[#344C3D]">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.plans.features') }}</label>
                <textarea name="features" rows="5" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">{{ old('features', implode("\n", $plan->features ?? [])) }}</textarea>
                <p class="text-[11px] text-slate-400">{{ __('admin.plans.features_hint') }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-6">
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-xs text-slate-800">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true)) class="control">
                    <span>{{ __('app.common.active') }}</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-xs text-slate-800">
                    <input type="checkbox" name="is_popular" value="1" @checked(old('is_popular', $plan->is_popular)) class="control">
                    <span>{{ __('membership.most_popular') }}</span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="action-btn action-btn-primary cursor-pointer">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.plans.index') }}" class="action-btn action-btn-ghost">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>