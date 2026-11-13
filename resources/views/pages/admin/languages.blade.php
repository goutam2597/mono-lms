<x-layouts.dashboard :title="__('admin.languages.heading') . ' - Mono LMS'" active="languages" userType="admin">

<style>
    /* ---------------- Action buttons ---------------- */
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 32px; padding: 0 14px; font-size: 10px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn-danger { color: #fff; background: #0a0a0a; border-color: #0a0a0a; }
    .action-btn-danger:hover { color: #fff; background: #dc2626; border-color: #dc2626; }

    /* ---------------- Row actions dropdown ---------------- */
    .lang-menu { position: fixed; z-index: 130; display: none; padding: 4px; background: #fff; border: 1px solid #e5e5e5; box-shadow: 0 12px 32px rgba(10,10,10,.14); }
    .lang-menu.open { display: block; }
    .lang-menu .menu-item { display: block; width: 100%; padding: 6px 14px; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: .12em; text-align: start; white-space: nowrap; color: #525252; background: transparent; border: 0; cursor: pointer; font-family: inherit; text-decoration: none; transition: background .12s, color .12s; }
    .lang-menu .menu-item:hover { background: #f5f5f5; color: #0a0a0a; }
    .lang-menu .menu-item.danger { color: #b91c1c; }
    .lang-menu .menu-item.danger:hover { background: #fef2f2; color: #991b1b; }
    .lang-menu .menu-sep { height: 1px; margin: 4px 0; background: #f0f0f0; }
</style>

<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-medium tracking-tight text-neutral-900">{{ __('admin.languages.heading') }}</h2>
            <p class="mt-1 text-sm text-neutral-500">{{ __('admin.languages.sub') }}</p>
        </div>
        <button type="button" id="btn-open-add-lang" class="action-btn h-10 px-5 text-[11px]">
            + {{ __('admin.languages.add') }}
        </button>
    </div>

    {{-- Add language card (collapsible / toggleable or open) --}}
    <div id="card-add-lang" class="hidden border border-neutral-200 bg-white p-6 shadow-xs">
        <div class="flex items-center justify-between border-b border-neutral-100 pb-3 mb-4">
            <h3 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">{{ __('admin.languages.add') }}</h3>
            <button type="button" id="btn-close-add-lang" class="text-xs text-neutral-400 hover:text-neutral-700">✕ {{ __('admin.common.cancel') }}</button>
        </div>
        <form method="POST" action="{{ route('admin.languages.store') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
            @csrf
            <div>
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ __('admin.languages.code') }} *</label>
                <input type="text" name="code" required value="{{ old('code') }}" placeholder="e.g. fr, es, de" maxlength="10"
                       class="h-10 w-full border border-neutral-300 rounded-lg px-3 text-sm placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ __('admin.languages.name') }} *</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. French"
                       class="h-10 w-full border border-neutral-300 rounded-lg px-3 text-sm placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ __('admin.languages.native') }} *</label>
                <input type="text" name="native_name" required value="{{ old('native_name') }}" placeholder="e.g. Français"
                       class="h-10 w-full border border-neutral-300 rounded-lg px-3 text-sm placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ __('admin.languages.direction') }} *</label>
                <select name="direction" data-mselect data-mselect-no-search class="h-10 w-full cursor-pointer border border-neutral-300 rounded-lg bg-white px-3 text-sm focus:border-neutral-900 focus:outline-none">
                    <option value="ltr">{{ __('admin.languages.ltr') }}</option>
                    <option value="rtl">{{ __('admin.languages.rtl') }}</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ __('admin.common.flag') }}</label>
                <input type="text" name="flag" value="{{ old('flag') }}" placeholder="e.g. fr, es, us" maxlength="10"
                       class="h-10 w-full border border-neutral-300 rounded-lg px-3 text-sm placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit" class="action-btn h-10 w-full">{{ __('admin.common.add') }}</button>
            </div>
            @if ($errors->any())
                <p class="text-xs text-red-600 sm:col-span-2 lg:col-span-6">{{ $errors->first() }}</p>
            @endif
        </form>
    </div>

    @if ($languages->isEmpty())
        <div class="border border-neutral-200 bg-white px-7 py-16 text-center text-sm text-neutral-400">{{ __('admin.common.no_results') }}</div>
    @else
        <div class="overflow-x-auto border border-neutral-200 bg-white shadow-xs">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200 text-left text-[10px] font-bold uppercase tracking-[0.14em] text-neutral-400 bg-neutral-50/60">
                        <th class="px-6 py-3.5">{{ __('admin.languages.code') }}</th>
                        <th class="px-6 py-3.5">{{ __('admin.languages.name') }}</th>
                        <th class="px-6 py-3.5">{{ __('admin.languages.native') }}</th>
                        <th class="px-6 py-3.5">{{ __('admin.languages.direction') }}</th>
                        <th class="px-6 py-3.5">{{ __('admin.common.status') }}</th>
                        <th class="px-6 py-3.5">{{ __('admin.languages.default') }}</th>
                        <th class="px-6 py-3.5 text-right">{{ __('admin.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($languages as $language)
                        <tr class="hover:bg-neutral-50/50 transition-colors">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    @if ($language->flag && file_exists(public_path('images/flags/'.$language->flag.'.png')))
                                        <img src="{{ asset('images/flags/'.$language->flag.'.png') }}" alt="{{ $language->name }}" class="h-4 w-6 border border-neutral-200 object-cover" />
                                    @endif
                                    <span class="font-mono font-bold text-xs text-neutral-900">{{ strtoupper($language->code) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <form method="POST" action="{{ route('admin.languages.update', $language) }}" id="lang-{{ $language->id }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $language->name }}" required
                                           class="h-8 w-36 border border-transparent bg-transparent rounded-lg px-2 text-xs font-medium text-neutral-900 transition hover:border-neutral-200 focus:border-neutral-900 focus:bg-white focus:outline-none">
                                </form>
                            </td>
                            <td class="px-6 py-3.5">
                                <input type="text" name="native_name" form="lang-{{ $language->id }}" value="{{ $language->native_name }}" required
                                       class="h-8 w-36 border border-transparent bg-transparent rounded-lg px-2 text-xs text-neutral-600 transition hover:border-neutral-200 focus:border-neutral-900 focus:bg-white focus:outline-none">
                            </td>
                            <td class="px-6 py-3.5">
                                <select name="direction" form="lang-{{ $language->id }}" data-mselect data-mselect-no-search
                                        class="h-8 w-20 cursor-pointer border border-neutral-200 bg-white rounded-lg px-2 text-[10px] font-bold uppercase tracking-wider text-neutral-600 transition hover:border-neutral-900 focus:border-neutral-900 focus:outline-none">
                                    <option value="ltr" @selected($language->direction === 'ltr')>LTR</option>
                                    <option value="rtl" @selected($language->direction === 'rtl')>RTL</option>
                                </select>
                            </td>
                            <td class="px-6 py-3.5">
                                @if ($language->is_active)
                                    <span class="inline-flex h-6 items-center justify-center whitespace-nowrap bg-emerald-50 px-2.5 text-[10px] font-bold uppercase tracking-wider text-emerald-800 border border-emerald-200">
                                        {{ __('admin.common.enabled') }}
                                    </span>
                                @else
                                    <span class="inline-flex h-6 items-center justify-center whitespace-nowrap bg-neutral-100 px-2.5 text-[10px] font-bold uppercase tracking-wider text-neutral-500 border border-neutral-200">
                                        {{ __('admin.common.disabled') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                @if ($language->is_default)
                                    <span class="inline-flex h-6 items-center justify-center whitespace-nowrap bg-blue-50 px-2.5 text-[10px] font-bold uppercase tracking-wider text-blue-700 border border-blue-200">
                                        ★ {{ __('admin.languages.default') }}
                                    </span>
                                @else
                                    <span class="text-xs text-neutral-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <button type="button" class="action-btn" data-menu-toggle="lang-menu-{{ $language->id }}" aria-haspopup="true">
                                    {{ __('admin.common.actions') }} ▾
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Floating row action menus --}}
        @foreach ($languages as $language)
            <div class="lang-menu" id="lang-menu-{{ $language->id }}" role="menu">
                <button type="submit" form="lang-{{ $language->id }}" class="menu-item">{{ __('admin.common.save') }}</button>
                <a href="{{ route('admin.languages.translations', $language) }}" class="menu-item">{{ __('admin.languages.translations') }}</a>
                <div class="menu-sep"></div>
                <form method="POST" action="{{ route('admin.languages.toggle', $language) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="menu-item">
                        {{ $language->is_active ? __('admin.languages.disable') : __('admin.languages.enable') }}
                    </button>
                </form>
                @unless ($language->is_default)
                    <form method="POST" action="{{ route('admin.languages.default', $language) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="menu-item">{{ __('admin.languages.set_default') }}</button>
                    </form>
                    <div class="menu-sep"></div>
                    <form method="POST" action="{{ route('admin.languages.destroy', $language) }}" onsubmit="return confirm('{{ __('admin.common.confirm_delete') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="menu-item danger">{{ __('admin.common.delete') }}</button>
                    </form>
                @endunless
            </div>
        @endforeach
    @endif
</div>

<script>
(function () {
    var addBtn = document.getElementById('btn-open-add-lang');
    var closeBtn = document.getElementById('btn-close-add-lang');
    var addCard = document.getElementById('card-add-lang');

    if (addBtn && addCard) {
        addBtn.addEventListener('click', function () {
            addCard.classList.toggle('hidden');
            if (! addCard.classList.contains('hidden')) {
                var codeInput = addCard.querySelector('input[name="code"]');
                if (codeInput) codeInput.focus();
            }
        });
    }

    if (closeBtn && addCard) {
        closeBtn.addEventListener('click', function () {
            addCard.classList.add('hidden');
        });
    }

    var openMenu = null;

    function closeMenu() {
        if (openMenu) {
            openMenu.classList.remove('open');
            openMenu = null;
        }
    }

    document.querySelectorAll('[data-menu-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var menu = document.getElementById(btn.dataset.menuToggle);
            if (! menu) return;
            if (openMenu === menu) {
                closeMenu();
                return;
            }
            closeMenu();
            menu.style.minWidth = Math.max(btn.offsetWidth, 160) + 'px';
            menu.classList.add('open');
            var r = btn.getBoundingClientRect();
            var left = r.right - menu.offsetWidth;
            var top = r.bottom + 4;
            if (left < 8) left = 8;
            if (top + menu.offsetHeight > window.innerHeight - 8) {
                top = r.top - menu.offsetHeight - 4;
            }
            menu.style.left = left + 'px';
            menu.style.top = top + 'px';
            openMenu = menu;
        });
    });

    document.addEventListener('click', function (e) {
        if (openMenu && ! openMenu.contains(e.target)) {
            closeMenu();
        }
    });

    window.addEventListener('scroll', closeMenu, true);
    window.addEventListener('resize', closeMenu);
})();
</script>

</x-layouts.dashboard>
