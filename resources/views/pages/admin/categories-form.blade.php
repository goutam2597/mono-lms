<x-layouts.dashboard :title="($category->exists ? __('admin.categories.edit') : __('admin.categories.create')).' - Mono LMS'" active="categories" userType="admin">

    <div class="bg-white border border-slate-200 shadow-xs p-6 sm:p-8 max-w-xl">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="font-bold text-[#191919] text-base">{{ $category->exists ? __('admin.categories.edit') : __('admin.categories.create') }}</h3>
            <p class="text-xs text-slate-500 mt-1">Configure category taxonomy, icon media, and card highlight styling.</p>
        </div>

        <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($category->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <!-- Category Image / Logo Media Selector -->
            <x-media-selector
                name="image"
                :value="old('image', $category->image)"
                label="Category Image / Logo"
                shape="square"
                hint="Used inside the central square on category cards. Leave blank to use the fallback icon below."
            />

            <!-- Category Accent Color -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">Accent Color</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="color" value="{{ old('color', $category->color ?? '#137547') }}" onchange="document.getElementById('color_preview').value = this.value" class="w-10 h-10 border border-slate-200 cursor-pointer p-0.5">
                    <input type="text" id="color_preview" value="{{ old('color', $category->color ?? '#137547') }}" readonly class="w-28 border border-slate-200 px-3.5 py-2 text-xs text-slate-600 bg-slate-50 font-mono">
                    <span class="text-[11px] text-slate-400">Used for the card icon container, divider bar & action button</span>
                </div>
                @error('color')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <!-- Fallback Icon / Emoji / SVG Path -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.categories.icon') }} (Fallback Emoji or SVG Path)</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="💻 or SVG Path (M...)" class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                @error('icon')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="action-btn action-btn-primary cursor-pointer">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.categories.index') }}" class="action-btn action-btn-ghost">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>