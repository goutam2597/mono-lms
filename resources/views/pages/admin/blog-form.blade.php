<x-layouts.dashboard :title="($post->exists ? __('admin.blog.edit') : __('admin.blog.create')).' - Dreams LMS'" active="blog" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8 max-w-3xl">
        <h3 class="font-bold text-[#191919] mb-6">{{ $post->exists ? __('admin.blog.edit') : __('admin.blog.create') }}</h3>

        <form action="{{ $post->exists ? route('admin.blog.update', $post) : route('admin.blog.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($post->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.title') }} *</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                @error('title')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('admin.blog.excerpt') }}</label>
                <textarea name="excerpt" rows="2" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('admin.blog.content') }}</label>
                <textarea name="content" rows="12" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:border-[#ff4667]">{{ old('content', $post->content) }}</textarea>
                <p class="text-[11px] text-gray-400">{{ __('admin.blog.content_hint') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.image') }}</label>
                    <input type="text" name="image" value="{{ old('image', $post->image) }}" placeholder="https://..." class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.status') }}</label>
                    <x-select name="status"
                        :options="['draft' => __('app.common.draft'), 'published' => __('app.common.published')]"
                        :selected="old('status', $post->status ?? 'draft')"
                        :searchable="false" />
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold px-6 py-2.5 rounded-full transition-colors">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.blog.index') }}" class="text-sm font-semibold text-[#5d5d5d] hover:text-[#191919] px-4 py-2.5">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>
