<x-layouts.dashboard :title="__('instructor.courses.title').' - Mono LMS'" active="courses" userType="instructor">

    <div class="bg-white border border-[#e7e7e7] rounded-none p-6 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[#191919]">{{ __('instructor.courses.title') }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.instructors.count', ['count' => $courses->total()]) }}</p>
            </div>
            @if (auth('instructor')->user()?->canAddCourses())
                <a href="{{ route('instructor.courses.create') }}" class="action-btn action-btn-primary h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">+ {{ __('instructor.courses.create') }}</a>
            @else
                <a href="{{ route('instructor.verification.show') }}" class="inline-flex items-center gap-1.5 h-9 px-4 text-[11px] font-semibold rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 transition-colors" title="Complete instructor verification to unlock course creation">
                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span>Verification Required to Add Courses</span>
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="px-4 py-3 font-semibold">{{ __('instructor.courses.fields.title') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('admin.courses.enrolled') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('instructor.courses.lessons') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('admin.courses.price') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-4 py-3 font-semibold text-right">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($courses as $course)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="px-4 py-3.5 font-semibold text-[#191919]">
                                <a href="{{ route('instructor.courses.edit', $course) }}" class="hover:underline">{{ \Illuminate\Support\Str::limit($course->trans('title'), 50) }}</a>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                    <span class="text-xs text-gray-400">{{ $course->category?->trans('name') ?? '—' }}</span>
                                    @if ($course->trans('title') !== $course->title)
                                        <span class="text-[10px] text-gray-400 italic">({{ \Illuminate\Support\Str::limit($course->title, 25) }})</span>
                                    @endif
                                    @if (!empty($course->title_translations))
                                        <span class="inline-flex items-center gap-1">
                                            @foreach (array_keys($course->title_translations) as $code)
                                                <span class="text-[9px] font-bold uppercase tracking-wider px-1 py-0.2 rounded bg-neutral-100 text-neutral-500">{{ $code }}</span>
                                            @endforeach
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-[#5d5d5d]">{{ $course->enrollments_count }}</td>
                            <td class="px-4 py-3.5 text-[#5d5d5d]">{{ $course->lessons_count }}</td>
                            <td class="px-4 py-3.5 font-semibold text-[#392c7d]">{{ __('app.common.currency') }}{{ number_format($course->price, 2) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-none {{ $course->is_published ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $course->is_published ? __('app.common.published') : __('app.common.draft') }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <button type="button" class="action-btn" data-menu-toggle="inst-course-menu-{{ $course->id }}" aria-haspopup="true">
                                    {{ __('app.common.actions') }} ▾
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-gray-400">{{ __('instructor.courses.no_courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">{{ $courses->links() }}</div>
    </div>

    {{-- Row action menus (fixed position via JS, kept outside table overflow) --}}
    @foreach ($courses as $course)
        <div class="row-action-menu" id="inst-course-menu-{{ $course->id }}" role="menu">
            <a href="{{ route('courses.show', $course->slug) }}" target="_blank" rel="noopener" class="menu-item">{{ __('student.dashboard.view') }}</a>
            <a href="{{ route('instructor.courses.edit', $course) }}" class="menu-item">{{ __('app.common.edit') }}</a>
            <div class="menu-sep"></div>
            <form method="POST" action="{{ route('instructor.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="menu-item danger">{{ __('app.common.delete') }}</button>
            </form>
        </div>
    @endforeach

</x-layouts.dashboard>
