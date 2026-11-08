<x-layouts.dashboard :title="__('admin.blog.title').' - Mono LMS'" active="blog" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.blog.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.blog.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-2">
                @foreach (['' => __('app.common.all'), 'published' => __('app.common.published'), 'draft' => __('app.common.draft')] as $key => $label)
                    <a href="{{ route('admin.blog.index', array_filter(['status' => $key])) }}" class="text-xs font-semibold px-4 py-2 rounded-xl transition-colors {{ request('status') == $key ? 'bg-[#08131e] text-white' : 'bg-[#f4f6f9] text-[#5d5d5d] hover:bg-gray-200' }}">{{ $label }}</a>
                @endforeach
                <a href="{{ route('admin.blog.create') }}" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors whitespace-nowrap">+ {{ __('admin.blog.create') }}</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="px-6 py-3 font-semibold">{{ __('instructor.courses.fields.title') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('student.dashboard.date') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="px-6 py-3.5">
                                <p class="font-semibold text-[#191919]">{{ \Illuminate\Support\Str::limit($post->title, 50) }}</p>
                                <p class="text-xs text-gray-400">{{ \Illuminate\Support\Str::limit($post->excerpt ?? '', 60) }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $post->published_at?->format('d M Y') ?? '—' }}</td>
                            <td class="px-6 py-3.5">
                                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $post->status === 'published' ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $post->status === 'published' ? __('app.common.published') : __('app.common.draft') }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    @if ($post->status === 'published')
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('student.dashboard.view') }}</a>
                                    @endif
                                    <a href="{{ route('admin.blog.edit', $post) }}" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('app.common.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">{{ __('app.common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">{{ $posts->appends(request()->query())->links() }}</div>
    </div>

</x-layouts.dashboard>
