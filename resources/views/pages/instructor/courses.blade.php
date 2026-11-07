<x-layouts.dashboard :title="__('instructor.courses.title').' - Mono LMS'" active="courses" userType="instructor">

    <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[#191919]">{{ __('instructor.courses.title') }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.instructors.count', ['count' => $courses->total()]) }}</p>
            </div>
            <a href="{{ route('instructor.courses.create') }}" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs font-semibold px-5 py-2.5 rounded-full transition-colors">+ {{ __('instructor.courses.create') }}</a>
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
                        <th class="px-4 py-3 font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($courses as $course)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="px-4 py-3.5 font-semibold text-[#191919]">{{ \Illuminate\Support\Str::limit($course->title, 40) }}</td>
                            <td class="px-4 py-3.5 text-[#5d5d5d]">{{ $course->enrollments_count }}</td>
                            <td class="px-4 py-3.5 text-[#5d5d5d]">{{ $course->lessons_count }}</td>
                            <td class="px-4 py-3.5 font-semibold text-[#392c7d]">{{ __('app.common.currency') }}{{ number_format($course->price, 2) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $course->is_published ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $course->is_published ? __('app.common.published') : __('app.common.draft') }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('instructor.courses.edit', $course) }}" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('app.common.edit') }}</a>
                                    <a href="{{ route('courses.show', $course->slug) }}" target="_blank" class="text-xs font-semibold text-[#5d5d5d] hover:underline">{{ __('student.dashboard.view') }}</a>
                                    <form method="POST" action="{{ route('instructor.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">{{ __('app.common.delete') }}</button>
                                    </form>
                                </div>
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

</x-layouts.dashboard>
