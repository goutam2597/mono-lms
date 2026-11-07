<x-layouts.dashboard :title="($category->exists ? __('admin.categories.edit') : __('admin.categories.create')).' - Dreams LMS'" active="categories" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8 max-w-xl">
        <h3 class="font-bold text-[#191919] mb-6">{{ $category->exists ? __('admin.categories.edit') : __('admin.categories.create') }}</h3>

        <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($category->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('admin.categories.icon') }}</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="💻" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold px-6 py-2.5 rounded-full transition-colors">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-[#5d5d5d] hover:text-[#191919] px-4 py-2.5">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>
