<x-layouts.dashboard :title="($course->exists ? __('instructor.courses.edit') : __('instructor.courses.create')).' - Mono LMS'" active="courses" userType="instructor">

    <div class="space-y-6">
        <!-- Course Form -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8">
            <h3 class="font-bold text-[#191919] mb-6">{{ $course->exists ? __('instructor.courses.edit') : __('instructor.courses.create') }}</h3>

            <form action="{{ $course->exists ? route('instructor.courses.update', $course) : route('instructor.courses.store') }}" method="POST" class="space-y-5">
                @csrf
                @if ($course->exists) @method('PUT') @endif

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.title') }} *</label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    @error('title')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.subtitle') }}</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $course->subtitle) }}" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.category') }}</label>
                        <x-select name="category_id"
                            :options="$categories->pluck('name', 'id')->prepend('—', '')"
                            :selected="old('category_id', $course->category_id)"
                            placeholder="—" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.level') }}</label>
                        <x-select name="level"
                            :options="['beginner' => __('app.levels.beginner'), 'intermediate' => __('app.levels.intermediate'), 'advanced' => __('app.levels.advanced')]"
                            :selected="old('level', $course->level ?? 'beginner')"
                            :searchable="false" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.image') }}</label>
                        <input type="text" name="image" value="{{ old('image', $course->image) }}" placeholder="https://..." class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.price') }} *</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $course->price ?? 0) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        @error('price')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.old_price') }}</label>
                        <input type="number" step="0.01" min="0" name="old_price" value="{{ old('old_price', $course->old_price) }}" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        @error('old_price')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.description') }}</label>
                    <textarea name="description" rows="5" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="flex flex-wrap items-center gap-6">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none text-sm text-[#191919]">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $course->is_published)) class="control mt-0.5">
                        {{ __('instructor.courses.fields.is_published') }}
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">{{ __('app.common.save') }}</button>
                    <a href="{{ route('instructor.courses.index') }}" class="text-sm font-semibold text-[#5d5d5d] hover:text-[#191919] px-4 py-2.5">{{ __('app.common.cancel') }}</a>
                </div>
            </form>
        </div>

        @if ($course->exists)
            <!-- Lessons Manager -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8">
                <h3 class="font-bold text-[#191919] mb-6">{{ __('instructor.lessons.title') }} ({{ $course->lessons->count() }})</h3>

                <form action="{{ route('instructor.lessons.store', $course) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-[1fr_140px_1fr_auto] gap-3 items-end">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.lessons.fields.title') }} *</label>
                        <input type="text" name="title" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.lessons.fields.duration') }}</label>
                        <input type="number" name="duration_minutes" min="0" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.lessons.fields.video_url') }}</label>
                        <input type="text" name="video_url" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    </div>
                    <button type="submit" class="bg-[#08131e] hover:bg-[#1b2737] text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition-colors">+ {{ __('instructor.lessons.add') }}</button>
                </form>

                <div class="mt-6 divide-y divide-gray-100 border border-gray-100 rounded-xl overflow-hidden">
                    @forelse ($course->lessons as $lesson)
                        <div class="px-4 py-3 flex items-center justify-between gap-3 hover:bg-[#f8fafb] transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-7 h-7 rounded-full bg-[#392c7d]/10 text-[#392c7d] text-xs font-bold flex items-center justify-center shrink-0">{{ $lesson->sort_order }}</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-[#191919] truncate">{{ $lesson->title }}</p>
                                    @if ($lesson->video_url)
                                        <p class="text-[11px] text-gray-400 truncate">{{ $lesson->video_url }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs text-gray-400">{{ $lesson->duration_minutes }} {{ __('app.common.minutes') }}</span>
                                <form method="POST" action="{{ route('instructor.lessons.destroy', $lesson) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">{{ __('app.common.delete') }}</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="px-4 py-8 text-center text-sm text-gray-400">{{ __('instructor.lessons.no_lessons') }}</p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>

</x-layouts.dashboard>
