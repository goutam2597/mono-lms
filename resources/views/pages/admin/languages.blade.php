<x-layouts.dashboard :title="__('admin.languages.title').' - Dreams LMS'" active="languages" userType="admin">

    <div class="space-y-6">
        <div class="bg-[#0dcaf0]/5 border border-[#0dcaf0]/20 rounded-xl px-5 py-3.5 text-xs text-[#0891b2] font-medium">
            {{ __('admin.languages.hint') }}
        </div>

        <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-[#191919]">{{ __('admin.languages.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.languages.subtitle') }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                            <th class="px-6 py-3 font-semibold">{{ __('admin.languages.code') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('app.common.name') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('admin.languages.direction') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('admin.languages.default') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('app.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($languages as $language)
                            <tr class="hover:bg-[#f8fafb] transition-colors" dir="{{ $language->direction }}">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ asset('images/flags/'.$language->flag.'.png') }}" alt="{{ $language->name }} flag" class="w-6 h-4 rounded-xs object-cover border border-gray-200" />
                                        <span class="font-mono font-semibold text-[#392c7d]">{{ strtoupper($language->code) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <p class="font-semibold text-[#191919]">{{ $language->native_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $language->name }}</p>
                                </td>
                                <td class="px-6 py-3.5 text-[#5d5d5d] uppercase">{{ $language->direction }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $language->is_active ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $language->is_active ? __('app.common.active') : __('app.common.inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5">
                                    @if ($language->is_default)
                                        <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full bg-[#ff4667]/10 text-[#ff4667]">★ {{ __('admin.languages.default') }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        @unless ($language->is_default)
                                            <form method="POST" action="{{ route('admin.languages.default', $language) }}">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('admin.languages.make_default') }}</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.languages.toggle', $language) }}">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold {{ $language->is_active ? 'text-[#ff4667]' : 'text-[#03c95a]' }} hover:underline">
                                                    {{ $language->is_active ? __('admin.languages.disable') : __('admin.languages.enable') }}
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
