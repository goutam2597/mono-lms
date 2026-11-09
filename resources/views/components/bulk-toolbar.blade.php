@props([
    'action',
    'formId' => 'bulk-form',
    'confirm' => null,
    'deleteLabel' => null,
    'selectedLabel' => null,
])

<form id="{{ $formId }}" method="POST" action="{{ $action }}" onsubmit="return confirm('{{ $confirm ?? __('app.common.confirm_delete') }}')">
    @csrf
</form>

<div data-bulk-toolbar class="px-5 py-2.5 bg-neutral-50 border-b border-neutral-200 flex items-center justify-between transition-colors">
    <div class="flex items-center gap-2 text-xs text-neutral-500">
        <span class="font-bold text-neutral-900" data-bulk-count>0</span>
        <span class="text-[11px] uppercase tracking-[0.12em]">{{ $selectedLabel ?? __('admin.common.selected') ?? 'selected' }}</span>
    </div>
    <button type="submit" form="{{ $formId }}" data-bulk-btn disabled
        class="action-btn action-btn-danger inline-flex items-center gap-1.5 h-8 px-4 text-[10px] font-semibold uppercase tracking-[0.12em] bg-red-600 hover:bg-red-700 disabled:opacity-40 text-white transition-colors cursor-pointer disabled:cursor-not-allowed">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
        </svg>
        <span>{{ $deleteLabel ?? __('admin.common.delete_selected') ?? 'Delete Selected' }}</span>
    </button>
</div>

<script>
if (!window.__bulkToolbarInitialized) {
    window.__bulkToolbarInitialized = true;

    function syncBulkToolbars() {
        document.querySelectorAll('[data-bulk-toolbar]').forEach(function (toolbar) {
            var formId = toolbar.querySelector('[data-bulk-btn]')?.getAttribute('form') || 'bulk-form';
            var checks = document.querySelectorAll('input.row-check[form="' + formId + '"]');
            if (!checks.length) {
                var container = toolbar.closest('.bg-white') || document;
                checks = container.querySelectorAll('input.row-check');
            }
            var checkedCount = 0;
            checks.forEach(function (c) {
                if (c.checked) checkedCount++;
            });

            var countEl = toolbar.querySelector('[data-bulk-count]');
            var btnEl = toolbar.querySelector('[data-bulk-btn]');

            if (countEl) countEl.textContent = checkedCount;
            if (btnEl) btnEl.disabled = (checkedCount === 0);

            var checkAll = document.querySelector('input#check-all, input[data-check-all]');
            if (checkAll && checks.length) {
                checkAll.checked = (checkedCount === checks.length);
                checkAll.indeterminate = (checkedCount > 0 && checkedCount < checks.length);
            }
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.matches('input#check-all, input[data-check-all]')) {
            var isChecked = e.target.checked;
            var container = e.target.closest('table') || document;
            container.querySelectorAll('input.row-check').forEach(function (c) {
                c.checked = isChecked;
            });
            syncBulkToolbars();
        } else if (e.target.matches('input.row-check')) {
            syncBulkToolbars();
        }
    });

    document.addEventListener('DOMContentLoaded', syncBulkToolbars);
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        syncBulkToolbars();
    }
}
</script>
