<x-layouts.dashboard :title="__('admin.reviews.title').' - Mono LMS'" active="reviews" userType="admin">

    <div class="bg-white border border-slate-200 rounded-none shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.reviews.title') }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ __('admin.reviews.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap">
                @foreach (['' => __('app.common.all'), 'pending' => __('app.common.pending'), 'approved' => __('app.common.approved'), 'rejected' => __('app.common.rejected')] as $key => $label)
                    <a href="{{ route('admin.reviews.index', array_filter(['status' => $key])) }}" class="text-[10px] uppercase tracking-[0.12em] font-semibold px-3 py-1.5 rounded-none border transition-colors {{ request('status') == $key ? 'bg-[#08131e] border-[#08131e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        <x-bulk-toolbar action="{{ route('admin.reviews.bulk-destroy') }}" />

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-[10px] uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-100">
                        <th class="w-10 px-6 py-3.5">
                            <input type="checkbox" id="check-all" class="control" aria-label="{{ __('admin.common.select_all') ?? 'Select All' }}">
                        </th>
                        <th class="px-6 py-3.5 font-semibold">{{ __('admin.reviews.student') }}</th>
                        <th class="px-6 py-3.5 font-semibold">{{ __('admin.reviews.course') }}</th>
                        <th class="px-6 py-3.5 font-semibold">{{ __('app.common.rating') }}</th>
                        <th class="px-6 py-3.5 font-semibold">{{ __('admin.reviews.comment') }}</th>
                        <th class="px-6 py-3.5 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3.5 text-right font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $statusClasses = [
                            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                    @endphp
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="w-10 px-6 py-3.5">
                                <input type="checkbox" class="control row-check" name="ids[]" value="{{ $review->id }}" form="bulk-form" aria-label="Review {{ $review->id }}">
                            </td>
                            <td class="px-6 py-3.5 font-semibold text-[#191919]">{{ $review->student?->name ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ \Illuminate\Support\Str::limit($review->course?->title, 30) }}</td>
                            <td class="px-6 py-3.5">
                                <span class="text-amber-500 font-bold tracking-widest">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                            </td>
                            <td class="px-6 py-3.5 text-slate-600 max-w-xs">{{ \Illuminate\Support\Str::limit($review->comment, 80) }}</td>
                            <td class="px-6 py-3.5">
                                <span class="text-[10px] font-bold uppercase tracking-[0.12em] px-2 py-0.5 rounded-none border {{ $statusClasses[$review->status] ?? 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                    {{ __('app.common.'.$review->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if ($review->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn-primary h-7 px-2.5 text-[10px]">{{ __('admin.reviews.approve') }}</button>
                                        </form>
                                    @endif
                                    @if ($review->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn-ghost h-7 px-2.5 text-[10px]">{{ __('admin.reviews.reject') }}</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-danger h-7 px-2.5 text-[10px]">{{ __('app.common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">{{ $reviews->appends(request()->query())->links() }}</div>
    </div>

</x-layouts.dashboard>
