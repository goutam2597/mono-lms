<x-layouts.dashboard :title="__('admin.courses.title').' - Mono LMS'" active="courses" userType="admin">

    <div class="bg-white border border-[#e7e7e7] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.courses.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.courses.subtitle') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.courses.index') }}" class="h-8 inline-flex items-center text-[10px] font-semibold uppercase tracking-[0.12em] px-3.5 border transition-colors {{ !request('status') ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-neutral-600 border-neutral-300 hover:border-neutral-900' }}">{{ __('app.common.all') }}</a>
                <a href="{{ route('admin.courses.index', ['status' => 'published']) }}" class="h-8 inline-flex items-center text-[10px] font-semibold uppercase tracking-[0.12em] px-3.5 border transition-colors {{ request('status') === 'published' ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-neutral-600 border-neutral-300 hover:border-neutral-900' }}">{{ __('app.common.published') }}</a>
                <a href="{{ route('admin.courses.index', ['status' => 'draft']) }}" class="h-8 inline-flex items-center text-[10px] font-semibold uppercase tracking-[0.12em] px-3.5 border transition-colors {{ request('status') === 'draft' ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-neutral-600 border-neutral-300 hover:border-neutral-900' }}">{{ __('app.common.draft') }}</a>
                <a href="{{ route('admin.courses.index', ['status' => 'featured']) }}" class="h-8 inline-flex items-center text-[10px] font-semibold uppercase tracking-[0.12em] px-3.5 border transition-colors {{ request('status') === 'featured' ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-neutral-600 border-neutral-300 hover:border-neutral-900' }}">★ Featured</a>
                <a href="{{ route('admin.courses.index', ['status' => 'trending']) }}" class="h-8 inline-flex items-center text-[10px] font-semibold uppercase tracking-[0.12em] px-3.5 border transition-colors {{ request('status') === 'trending' ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-neutral-600 border-neutral-300 hover:border-neutral-900' }}">🔥 Trending</a>
                <a href="{{ route('admin.courses.create') }}" class="action-btn action-btn-primary h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">
                    + {{ __('instructor.courses.create') ?: 'Create Course' }}
                </a>
            </div>
        </div>

        <x-bulk-toolbar action="{{ route('admin.courses.bulk-destroy') }}" />

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="w-10 px-6 py-3">
                            <input type="checkbox" id="check-all" class="control" aria-label="Select All">
                        </th>
                        <th class="px-6 py-3 font-semibold">{{ __('instructor.courses.fields.title') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.courses.instructor') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.courses.price') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.courses.enrolled') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 font-semibold text-right">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($courses as $course)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="w-10 px-6 py-3.5">
                                <input type="checkbox" class="control row-check" name="ids[]" value="{{ $course->id }}" form="bulk-form" aria-label="{{ $course->title }}">
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="font-semibold text-[#191919]">{{ \Illuminate\Support\Str::limit($course->title, 40) }}</p>
                                <p class="text-xs text-gray-400">{{ $course->category?->name ?? '—' }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $course->instructor?->name ?? '—' }}</td>
                            <td class="px-6 py-3.5 font-semibold text-[#392c7d]">{{ __('app.common.currency') }}{{ number_format($course->price, 2) }}</td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $course->enrollments_count }}</td>
                            <td class="px-6 py-3.5">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center justify-center h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] {{ $course->is_published ? 'bg-[#03c95a]/10 text-[#0d7a38] border border-[#03c95a]/20' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                        {{ $course->is_published ? __('app.common.published') : __('app.common.draft') }}
                                    </span>
                                    @if ($course->is_featured)
                                        <span class="inline-flex items-center justify-center h-6 px-2 text-[10px] font-semibold uppercase tracking-[0.08em] bg-amber-500/10 text-amber-700 dark:text-amber-300 border border-amber-500/25" title="Featured Course on Homepage">
                                            ★ Featured
                                        </span>
                                    @endif
                                    @if ($course->is_trending)
                                        <span class="inline-flex items-center justify-center h-6 px-2 text-[10px] font-semibold uppercase tracking-[0.08em] bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-500/25" title="Trending Course on Homepage">
                                            🔥 Trending
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <button type="button" class="action-btn" data-menu-toggle="course-menu-{{ $course->id }}" aria-haspopup="true">
                                    {{ __('app.common.actions') }} ▾
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">{{ $courses->appends(request()->query())->links() }}</div>
    </div>

    {{-- Row action menus (fixed position via JS, kept outside table overflow) --}}
    @foreach ($courses as $course)
        <div class="row-action-menu" id="course-menu-{{ $course->id }}" role="menu">
            <a href="{{ route('courses.show', $course->slug) }}" target="_blank" rel="noopener" class="menu-item">{{ __('student.dashboard.view') }}</a>
            <a href="{{ route('admin.courses.edit', $course) }}" class="menu-item">{{ __('app.common.edit') }}</a>
            @canany(['courses.publish'])
                @if ($course->is_published)
                    <form method="POST" action="{{ route('admin.courses.unpublish', $course) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="menu-item">{{ __('admin.courses.unpublish') }}</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.courses.publish', $course) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="menu-item">{{ __('admin.courses.approve') }}</button>
                    </form>
                @endif
            @endcanany
            @canany(['courses.manage'])
                <form method="POST" action="{{ route('admin.courses.toggle-featured', $course) }}">
                    @csrf
                    <button type="submit" class="menu-item">
                        {{ $course->is_featured ? '★ Remove from Featured' : '★ Feature on Home' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.courses.toggle-trending', $course) }}">
                    @csrf
                    <button type="submit" class="menu-item">
                        {{ $course->is_trending ? '🔥 Remove from Trending' : '🔥 Mark as Trending' }}
                    </button>
                </form>
            @endcanany
            <div class="menu-sep"></div>
            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="menu-item danger">{{ __('app.common.delete') }}</button>
            </form>
        </div>
    @endforeach

</x-layouts.dashboard>
