<x-layouts.dashboard :title="__('admin.languages.title').' - Mono LMS'" active="languages" userType="admin">

    <div class="space-y-6">
        <div class="bg-[#0dcaf0]/5 border border-[#0dcaf0]/25 px-5 py-3.5 text-xs text-[#0891b2] font-medium flex items-center gap-2.5">
            <svg class="w-4 h-4 text-[#0891b2] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ __('admin.languages.hint') }}</span>
        </div>

        <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="font-bold text-[#191919] text-base">{{ __('admin.languages.title') }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ __('admin.languages.subtitle') }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[10px] uppercase font-bold tracking-[0.14em] text-slate-400 bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3">{{ __('admin.languages.code') }}</th>
                            <th class="px-6 py-3">{{ __('app.common.name') }}</th>
                            <th class="px-6 py-3">{{ __('admin.languages.direction') }}</th>
                            <th class="px-6 py-3">{{ __('app.common.status') }}</th>
                            <th class="px-6 py-3">{{ __('admin.languages.default') }}</th>
                            <th class="px-6 py-3">{{ __('app.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($languages as $language)
                            <tr class="hover:bg-slate-50/60 transition-colors" dir="{{ $language->direction }}">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ asset('images/flags/'.$language->flag.'.png') }}" alt="{{ $language->name }} flag" class="w-6 h-4 object-cover border border-slate-200" />
                                        <span class="font-mono font-bold text-xs text-[#392c7d]">{{ strtoupper($language->code) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <p class="font-bold text-[#191919] text-xs">{{ $language->native_name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $language->name }}</p>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-slate-600 font-mono uppercase">{{ $language->direction }}</td>
                                <td class="px-6 py-3.5">
                                    @if ($language->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            {{ __('app.common.active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ __('app.common.inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    @if ($language->is_default)
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] bg-[#344C3D]/10 text-[#344C3D] border border-[#344C3D]/20">
                                            ★ {{ __('admin.languages.default') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        @unless ($language->is_default)
                                            <form method="POST" action="{{ route('admin.languages.default', $language) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn action-btn-ghost text-xs cursor-pointer">
                                                    {{ __('admin.languages.make_default') }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.languages.toggle', $language) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn {{ $language->is_active ? 'action-btn-danger' : 'action-btn-primary' }} text-xs cursor-pointer">
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