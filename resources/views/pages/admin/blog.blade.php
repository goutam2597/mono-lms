<x-layouts.dashboard :title="__('admin.blog.title').' - Mono LMS'" active="blog" userType="admin">

    <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919] text-base">{{ __('admin.blog.title') }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ __('admin.blog.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-2">
                @foreach (['' => __('app.common.all'), 'published' => __('app.common.published'), 'draft' => __('app.common.draft')] as $key => $label)
                    <a href="{{ route('admin.blog.index', array_filter(['status' => $key])) }}"
                       class="h-8 px-3.5 inline-flex items-center border text-[10px] uppercase font-bold tracking-[0.12em] transition-colors {{ request('status') == $key ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('admin.blog.create') }}" class="action-btn action-btn-primary whitespace-nowrap">
                    + {{ __('admin.blog.create') }}
                </a>
            </div>
        </div>

        <x-bulk-toolbar action="{{ route('admin.blog.bulk-destroy') }}" />

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[10px] uppercase font-bold tracking-[0.14em] text-slate-400 bg-slate-50 border-b border-slate-200">
                        <th class="w-10 px-6 py-3">
                            <input type="checkbox" id="check-all" class="control" aria-label="{{ __('admin.common.select_all') ?? 'Select All' }}">
                        </th>
                        <th class="px-6 py-3">{{ __('instructor.courses.fields.title') }}</th>
                        <th class="px-6 py-3">{{ __('student.dashboard.date') }}</th>
                        <th class="px-6 py-3">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="w-10 px-6 py-3.5">
                                <input type="checkbox" class="control row-check" name="ids[]" value="{{ $post->id }}" form="bulk-form" aria-label="{{ $post->trans('title') }}">
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="font-bold text-[#191919] text-xs">
                                    <span>{{ \Illuminate\Support\Str::limit($post->trans('title'), 50) }}</span>
                                    @if ($post->trans('title') !== $post->title)
                                        <span class="text-[10px] text-gray-400 italic">({{ \Illuminate\Support\Str::limit($post->title, 25) }})</span>
                                    @endif
                                    @if (!empty($post->title_translations))
                                        <span class="inline-flex items-center gap-1 ml-1.5 align-middle">
                                            @foreach (array_keys($post->title_translations) as $code)
                                                <span class="px-1.5 py-0.2 text-[9px] font-mono font-semibold rounded bg-neutral-100 text-neutral-600 border border-neutral-200 uppercase">{{ $code }}</span>
                                            @endforeach
                                        </span>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ \Illuminate\Support\Str::limit($post->trans('excerpt') ?? '', 60) }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-600 font-mono">{{ $post->published_at?->format('d M Y') ?? '—' }}</td>
                            <td class="px-6 py-3.5">
                                @if ($post->status === 'published')
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ __('app.common.published') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ __('app.common.draft') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <button type="button" class="action-btn" data-menu-toggle="post-menu-{{ $post->id }}" aria-haspopup="true">
                                    {{ __('app.common.actions') }} ▾
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 text-xs">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200">{{ $posts->appends(request()->query())->links() }}</div>
    </div>

    {{-- Row action menus (fixed position via JS, kept outside table overflow) --}}
    @foreach ($posts as $post)
        <div class="row-action-menu" id="post-menu-{{ $post->id }}" role="menu">
            @if ($post->status === 'published')
                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener" class="menu-item">{{ __('student.dashboard.view') }}</a>
            @endif
            <a href="{{ route('admin.blog.edit', $post) }}" class="menu-item">{{ __('app.common.edit') }}</a>
            <div class="menu-sep"></div>
            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="menu-item danger">{{ __('app.common.delete') }}</button>
            </form>
        </div>
    @endforeach

</x-layouts.dashboard>