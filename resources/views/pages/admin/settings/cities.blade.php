<x-layouts.dashboard title="Cities — {{ $state->name }} - Mono LMS" active="locations" userType="admin">

<style>
    /* ---------------- Row actions dropdown (same as mono_market) ---------------- */
    .row-menu { position: fixed; z-index: 130; display: none; padding: 4px; background: #fff; border: 1px solid #e5e5e5; box-shadow: 0 12px 32px rgba(10,10,10,.14); }
    .row-menu.open { display: block; }
    .row-menu .menu-item { display: block; width: 100%; padding: 4px 12px; font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: .12em; text-align: start; white-space: nowrap; color: #525252; background: transparent; border: 0; cursor: pointer; font-family: inherit; text-decoration: none; transition: background .12s, color .12s; }
    .row-menu .menu-item:hover { background: #f5f5f5; color: #0a0a0a; }
    .row-menu .menu-item.danger { color: #b91c1c; }
    .row-menu .menu-item.danger:hover { background: #fef2f2; color: #991b1b; }
    .row-menu .menu-sep { height: 1px; margin: 4px 0; background: #f0f0f0; }

    /* ---------------- Add/Edit modal (same as mono_market) ---------------- */
    #city-modal { position: fixed; inset: 0; z-index: 120; display: none; align-items: center; justify-content: center; background: rgba(10,10,10,.6); padding: 20px; }
    #city-modal.open { display: flex; }
    #city-modal .panel { background: #fff; width: 420px; max-width: 100%; max-height: calc(100vh - 40px); overflow-y: auto; padding: 24px; }
</style>

<div class="mb-5">
    <a href="{{ route('admin.settings.countries.states', $country) }}" class="text-sm text-neutral-500 transition hover:text-neutral-900">← Back to States</a>
</div>

{{-- Same full-width card frame and table anatomy as the Courses/Students pages --}}
<div class="bg-white border border-[#e7e7e7] overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-[#191919]">Cities</h3>
            <p class="text-xs text-gray-400 mt-0.5">Cities of {{ $state->name }}, {{ $country->name }}.</p>
        </div>
        <button type="button" id="add-btn" class="action-btn action-btn-primary h-9 px-4 text-[10px] cursor-pointer">
            + Add City
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                    <th class="px-6 py-3 font-semibold">Name</th>
                    <th class="px-6 py-3 font-semibold">Status</th>
                    <th class="px-6 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($cities as $city)
                    <tr class="hover:bg-[#f8fafb] transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-[#191919]">{{ $city->name }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center justify-center h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] {{ $city->is_active ? 'bg-[#03c95a]/10 text-[#0d7a38] border border-[#03c95a]/20' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                {{ $city->is_active ? 'Enabled' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <button type="button" class="action-btn h-7 px-3" data-menu-toggle="row-menu-{{ $city->id }}" aria-haspopup="true">
                                Actions ▾
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-6 py-12 text-center text-xs text-gray-400">No cities for {{ $state->name }} yet. Add one above.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($cities->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $cities->links() }}</div>
    @endif
</div>

{{-- Row action menus --}}
@foreach ($cities as $city)
    <div class="row-menu" id="row-menu-{{ $city->id }}" role="menu">
        <button type="button" class="menu-item edit-btn"
                data-action="{{ route('admin.settings.countries.states.cities.update', [$country, $state, $city]) }}"
                data-name="{{ $city->name }}"
                data-is-active="{{ $city->is_active ? '1' : '0' }}">
            Edit
        </button>
        <div class="menu-sep"></div>
        <form method="POST" action="{{ route('admin.settings.countries.states.cities.destroy', [$country, $state, $city]) }}" onsubmit="return confirm('Delete {{ $city->name }}?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="menu-item danger">Delete</button>
        </form>
    </div>
@endforeach

{{-- Add/Edit modal --}}
<div id="city-modal" role="dialog" aria-modal="true">
    <div class="panel">
        <h3 class="text-[13px] font-medium uppercase tracking-[0.14em]" id="modal-heading">Add City</h3>

        <form method="POST" id="city-form" action="{{ route('admin.settings.countries.states.cities.store', [$country, $state]) }}" class="mt-5 space-y-5">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST" disabled>

            <div>
                <label class="mb-1.5 block text-[11px] font-medium uppercase tracking-[0.14em] text-neutral-500">Name</label>
                <input type="text" name="name" id="f-name" required maxlength="120"
                       class="h-11 w-full border border-neutral-300 px-4 text-sm placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none">
            </div>

            <label class="flex cursor-pointer items-center gap-3 text-sm text-neutral-600">
                <input type="checkbox" name="is_active" id="f-is-active" value="1" checked class="control">
                Enabled
            </label>

            <div class="flex items-center justify-end gap-3 border-t border-neutral-100 pt-5">
                <button type="button" id="modal-cancel" class="border border-neutral-300 px-5 py-2.5 text-[11px] font-medium uppercase tracking-[0.14em] text-neutral-600 transition hover:border-neutral-900 hover:text-neutral-900">Cancel</button>
                <button type="submit" class="bg-neutral-900 px-5 py-2.5 text-[11px] font-medium uppercase tracking-[0.14em] text-white transition hover:bg-neutral-700">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    /* ---------------- Row action menus ---------------- */
    var openMenu = null;

    function closeMenu() {
        if (openMenu) { openMenu.classList.remove('open'); openMenu = null; }
    }

    document.querySelectorAll('[data-menu-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var menu = document.getElementById(btn.dataset.menuToggle);
            if (! menu) return;
            if (openMenu === menu) { closeMenu(); return; }
            closeMenu();
            menu.style.minWidth = btn.offsetWidth + 'px';
            menu.classList.add('open');
            var r    = btn.getBoundingClientRect();
            var left = r.right - menu.offsetWidth;
            var top  = r.bottom + 6;
            if (left < 8) left = 8;
            if (top + menu.offsetHeight > window.innerHeight - 8) top = r.top - menu.offsetHeight - 6;
            menu.style.left = left + 'px';
            menu.style.top  = top + 'px';
            openMenu = menu;
        });
    });

    document.addEventListener('click', function (e) {
        if (openMenu && ! openMenu.contains(e.target)) closeMenu();
    });
    window.addEventListener('scroll', closeMenu, true);
    window.addEventListener('resize', closeMenu);

    /* ---------------- Modal ---------------- */
    var modal    = document.getElementById('city-modal');
    var form     = document.getElementById('city-form');
    var methodEl = document.getElementById('form-method');
    var heading  = document.getElementById('modal-heading');
    var nameEl   = document.getElementById('f-name');
    var activeEl = document.getElementById('f-is-active');

    var storeAction = form.action;
    var addHeading  = heading.textContent;
    var editHeading = 'Edit City';

    function openModal(opts) {
        form.action = opts.action;
        if (opts.method === 'PUT') {
            methodEl.value = 'PUT';
            methodEl.disabled = false;
        } else {
            methodEl.value = 'POST';
            methodEl.disabled = true;
        }
        var d = opts.data || {};
        heading.textContent = opts.method === 'PUT' ? editHeading : addHeading;
        nameEl.value   = d.name || '';
        activeEl.checked = d.is_active === true || d.is_active === '1' || d.is_active === 1;
        modal.classList.add('open');
        nameEl.focus();
    }

    function closeModal() { modal.classList.remove('open'); }

    document.getElementById('add-btn').addEventListener('click', function () {
        openModal({ action: storeAction, method: 'POST', data: {} });
    });

    document.querySelectorAll('.edit-btn').forEach(function (b) {
        b.addEventListener('click', function () {
            openModal({
                action: b.dataset.action,
                method: 'PUT',
                data: {
                    name:       b.dataset.name,
                    is_active:  b.dataset.isActive,
                },
            });
        });
    });

    document.getElementById('modal-cancel').addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
})();
</script>

</x-layouts.dashboard>
