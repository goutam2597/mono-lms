<x-layouts.dashboard :title="__('admin.courses.title').' - Mono LMS'" active="courses" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.courses.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.courses.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold px-4 py-2 rounded-xl transition-colors {{ !request('status') ? 'bg-[#08131e] text-white' : 'bg-[#f4f6f9] text-[#5d5d5d] hover:bg-gray-200' }}">{{ __('app.common.all') }}</a>
                <a href="{{ route('admin.courses.index', ['status' => 'published']) }}" class="text-xs font-semibold px-4 py-2 rounded-xl transition-colors {{ request('status') === 'published' ? 'bg-[#08131e] text-white' : 'bg-[#f4f6f9] text-[#5d5d5d] hover:bg-gray-200' }}">{{ __('app.common.published') }}</a>
                <a href="{{ route('admin.courses.index', ['status' => 'draft']) }}" class="text-xs font-semibold px-4 py-2 rounded-xl transition-colors {{ request('status') === 'draft' ? 'bg-[#08131e] text-white' : 'bg-[#f4f6f9] text-[#5d5d5d] hover:bg-gray-200' }}">{{ __('app.common.draft') }}</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="px-6 py-3 font-semibold">{{ __('instructor.courses.fields.title') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.courses.instructor') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.courses.price') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.courses.enrolled') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($courses as $course)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="px-6 py-3.5">
                                <p class="font-semibold text-[#191919]">{{ \Illuminate\Support\Str::limit($course->title, 40) }}</p>
                                <p class="text-xs text-gray-400">{{ $course->category?->name ?? '—' }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $course->instructor?->name ?? '—' }}</td>
                            <td class="px-6 py-3.5 font-semibold text-[#392c7d]">{{ __('app.common.currency') }}{{ number_format($course->price, 2) }}</td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $course->enrollments_count }}</td>
                            <td class="px-6 py-3.5">
                                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $course->is_published ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $course->is_published ? __('app.common.published') : __('app.common.draft') }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('courses.show', $course->slug) }}" target="_blank" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('student.dashboard.view') }}</a>
                                    @canany(['courses.publish'])
                                        @if ($course->is_published)
                                            <form method="POST" action="{{ route('admin.courses.unpublish', $course) }}">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold text-[#738a6e] hover:underline">{{ __('admin.courses.unpublish') }}</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.courses.publish', $course) }}">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold text-[#03c95a] hover:underline">{{ __('admin.courses.approve') }}</button>
                                            </form>
                                        @endif
                                    @endcanany
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">{{ __('app.common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">{{ $courses->appends(request()->query())->links() }}</div>
    </div>

</x-layouts.dashboard>
