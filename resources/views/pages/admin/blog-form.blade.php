<x-layouts.dashboard :title="($post->exists ? __('admin.blog.edit') : __('admin.blog.create')).' - Mono LMS'" active="blog" userType="admin">

    <div class="bg-white border border-slate-200 shadow-xs p-6 sm:p-8 max-w-3xl">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="font-bold text-[#191919] text-base">{{ $post->exists ? __('admin.blog.edit') : __('admin.blog.create') }}</h3>
            <p class="text-xs text-slate-500 mt-1">Compose editorial stories, releases, and platform updates.</p>
        </div>

        <form action="{{ $post->exists ? route('admin.blog.update', $post) : route('admin.blog.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($post->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('instructor.courses.fields.title') }} *</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                @error('title')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.blog.excerpt') }}</label>
                <textarea name="excerpt" rows="2" class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.blog.content') }}</label>
                <textarea name="content" rows="12" class="w-full border border-slate-200 px-3.5 py-2.5 text-xs font-mono focus:outline-none focus:border-[#344C3D]">{{ old('content', $post->content) }}</textarea>
                <p class="text-[11px] text-slate-400">{{ __('admin.blog.content_hint') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-start">
                <x-media-selector
                    name="image"
                    :value="old('image', $post->image)"
                    :label="__('instructor.courses.fields.image')"
                    shape="wide"
                />

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.status') }}</label>
                    <x-select name="status"
                        :options="['draft' => __('app.common.draft'), 'published' => __('app.common.published')]"
                        :selected="old('status', $post->status ?? 'draft')"
                        :searchable="false" />
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="action-btn action-btn-primary cursor-pointer">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.blog.index') }}" class="action-btn action-btn-ghost">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>