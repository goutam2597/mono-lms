<x-layouts.dashboard :title="($plan->exists ? __('admin.plans.edit') : __('admin.plans.create')).' - Dreams LMS'" active="plans" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8 max-w-2xl">
        <h3 class="font-bold text-[#191919] mb-6">{{ $plan->exists ? __('admin.plans.edit') : __('admin.plans.create') }}</h3>

        <form action="{{ $plan->exists ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($plan->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $plan->name) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('admin.plans.description') }}</label>
                <textarea name="description" rows="2" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">{{ old('description', $plan->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('admin.courses.price') }} *</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $plan->price ?? 0) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('admin.plans.billing_period') }}</label>
                    <x-select name="billing_period"
                        :options="['monthly' => __('membership.monthly'), 'yearly' => __('membership.yearly')]"
                        :selected="old('billing_period', $plan->billing_period ?? 'monthly')"
                        :searchable="false" />
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.lessons.fields.sort_order') }}</label>
                    <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $plan->sort_order ?? 0) }}" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('admin.plans.features') }}</label>
                <textarea name="features" rows="5" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">{{ old('features', implode("\n", $plan->features ?? [])) }}</textarea>
                <p class="text-[11px] text-gray-400">{{ __('admin.plans.features_hint') }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-6">
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-sm text-[#191919]">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true)) class="control">
                    {{ __('app.common.active') }}
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer select-none text-sm text-[#191919]">
                    <input type="checkbox" name="is_popular" value="1" @checked(old('is_popular', $plan->is_popular)) class="control">
                    {{ __('membership.most_popular') }}
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold px-6 py-2.5 rounded-full transition-colors">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.plans.index') }}" class="text-sm font-semibold text-[#5d5d5d] hover:text-[#191919] px-4 py-2.5">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>
