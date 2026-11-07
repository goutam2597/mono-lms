<x-layouts.dashboard :title="__('admin.categories.title').' - Dreams LMS'" active="categories" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.categories.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.categories.subtitle') }}</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs font-semibold px-4 py-2.5 rounded-full transition-colors whitespace-nowrap">+ {{ __('admin.categories.create') }}</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.name') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.categories.icon') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.courses') ?? __('admin.dashboard.total_courses') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="px-6 py-3.5 font-semibold text-[#191919]">{{ $category->name }} <span class="text-xs text-gray-400 font-normal">({{ $category->slug }})</span></td>
                            <td class="px-6 py-3.5 text-xl">{{ $category->icon ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $category->courses_count }}</td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('app.common.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
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

        <div class="px-6 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
    </div>

</x-layouts.dashboard>
