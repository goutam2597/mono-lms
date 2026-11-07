<x-layouts.dashboard :title="__('admin.reviews.title').' - Mono LMS'" active="reviews" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.reviews.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.reviews.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-2">
                @foreach (['' => __('app.common.all'), 'pending' => __('app.common.pending'), 'approved' => __('app.common.approved'), 'rejected' => __('app.common.rejected')] as $key => $label)
                    <a href="{{ route('admin.reviews.index', array_filter(['status' => $key])) }}" class="text-xs font-semibold px-4 py-2 rounded-full transition-colors {{ request('status') == $key ? 'bg-[#08131e] text-white' : 'bg-[#f4f6f9] text-[#5d5d5d] hover:bg-gray-200' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="px-6 py-3 font-semibold">{{ __('admin.reviews.student') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.reviews.course') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.rating') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.reviews.comment') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $statusClasses = [
                            'approved' => 'bg-[#03c95a]/10 text-[#0d7a38]',
                            'pending' => 'bg-[#ffc107]/10 text-[#a07800]',
                            'rejected' => 'bg-red-100 text-red-500',
                        ];
                    @endphp
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="px-6 py-3.5 font-semibold text-[#191919]">{{ $review->student?->name ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ \Illuminate\Support\Str::limit($review->course?->title, 30) }}</td>
                            <td class="px-6 py-3.5">
                                <span class="text-[#ffc107] font-semibold">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                            </td>
                            <td class="px-6 py-3.5 text-[#5d5d5d] max-w-xs">{{ \Illuminate\Support\Str::limit($review->comment, 80) }}</td>
                            <td class="px-6 py-3.5">
                                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $statusClasses[$review->status] ?? 'bg-gray-100 text-gray-500' }}">
                                    {{ __('app.common.'.$review->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    @if ($review->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-[#03c95a] hover:underline">{{ __('admin.reviews.approve') }}</button>
                                        </form>
                                    @endif
                                    @if ($review->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-[#ff4667] hover:underline">{{ __('admin.reviews.reject') }}</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
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

        <div class="px-6 py-4 border-t border-gray-100">{{ $reviews->appends(request()->query())->links() }}</div>
    </div>

</x-layouts.dashboard>
