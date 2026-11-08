<x-layouts.dashboard :title="($category->exists ? __('admin.categories.edit') : __('admin.categories.create')).' - Mono LMS'" active="categories" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8 max-w-xl">
        <h3 class="font-bold text-[#191919] mb-6">{{ $category->exists ? __('admin.categories.edit') : __('admin.categories.create') }}</h3>

        <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if ($category->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <!-- Category Image / Logo Upload -->
            <div class="space-y-2">
                <label class="text-xs font-semibold text-[#191919]">Category Image / Logo (PNG, JPG, SVG, WebP)</label>
                @if ($category->imageUrl())
                    <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl">
                        <img src="{{ $category->imageUrl() }}" alt="{{ $category->name }}" class="w-12 h-12 object-contain bg-white rounded-lg border border-gray-200 p-1">
                        <div class="text-xs">
                            <span class="font-medium text-gray-700 block">Current Image</span>
                            <label class="inline-flex items-center gap-1.5 text-red-600 hover:text-red-700 cursor-pointer mt-1">
                                <input type="checkbox" name="remove_image" value="1" class="rounded text-red-600">
                                <span>Remove this image</span>
                            </label>
                        </div>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2 text-xs focus:outline-none focus:border-[#738a6e] file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#edf3ec] file:text-[#137547] hover:file:bg-[#dce7da] cursor-pointer">
                <p class="text-[11px] text-gray-400">Used inside the central square on category cards. Leave blank to use the icon below.</p>
                @error('image')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <!-- Category Accent Color -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">Accent Color</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="color" value="{{ old('color', $category->color ?? '#137547') }}" onchange="document.getElementById('color_preview').value = this.value" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer p-0.5">
                    <input type="text" id="color_preview" value="{{ old('color', $category->color ?? '#137547') }}" readonly class="w-28 border border-[#e7e7e7] rounded-lg px-3 py-2 text-xs text-gray-600 bg-gray-50 font-mono">
                    <span class="text-xs text-gray-400">Used for the card icon container, divider bar & action button</span>
                </div>
                @error('color')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <!-- Fallback Icon / Emoji / SVG Path -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('admin.categories.icon') }} (Fallback Emoji or SVG Path)</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="💻 or SVG Path (M...)" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                @error('icon')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors cursor-pointer">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-[#5d5d5d] hover:text-[#191919] px-4 py-2.5">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>
