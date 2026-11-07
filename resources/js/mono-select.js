/**
 * MonoSelect — custom dropdown selector with optional type-to-search.
 * Used everywhere native <select> would be: forms, filters, the hero search.
 *
 * Search visibility:
 *   - searchable=false        → never shown (fixed, short lists)
 *   - searchable='auto'       → shown only above the threshold (default 10)
 *   - searchable=true         → always shown
 *
 * Keyboard:
 *   - Enter/Space/ArrowDown opens the panel
 *   - ArrowUp/ArrowDown move the active option, Enter selects it, Esc closes
 *   - Typing letters (typeahead) jumps to the first option starting with
 *     the typed prefix — like a native <select>
 *
 * Markup contract (see components/select.blade.php):
 *   [data-mono-select]        root
 *   [data-ms-trigger]         the visible trigger button
 *   [data-ms-panel]           the dropdown panel
 *   [data-ms-input]           hidden input carrying the real value
 *   [data-ms-label]           the label span inside the trigger
 *   [data-ms-search]          optional search input
 *   [data-ms-option]          option buttons (value + data-label)
 *   [data-ms-empty]           "no matches" message
 *   [data-ms-autosubmit]      submit closest form on change (filter bars)
 */
const ACTIVE_CLASS = ['bg-[#f4f6f9]', 'text-[#ff4667]'];

function initMonoSelects(root = document) {
    root.querySelectorAll('[data-mono-select]:not([data-ms-ready])').forEach((box) => {
        box.dataset.msReady = '1';

        const trigger = box.querySelector('[data-ms-trigger]');
        const panel = box.querySelector('[data-ms-panel]');
        const hidden = box.querySelector('[data-ms-input]');
        const label = box.querySelector('[data-ms-label]');
        const search = box.querySelector('[data-ms-search]');
        const chevron = box.querySelector('[data-ms-chevron]');
        const options = [...box.querySelectorAll('[data-ms-option]')];
        const empty = box.querySelector('[data-ms-empty]');

        let typeaheadBuffer = '';
        let typeaheadTimer = null;
        let activeIndex = -1;

        const visibleOptions = () => options.filter((o) => !o.classList.contains('hidden'));

        const filter = (query) => {
            const q = (query || '').trim().toLowerCase();
            let any = false;
            options.forEach((option) => {
                const hit = !q || (option.dataset.label || '').includes(q);
                option.classList.toggle('hidden', !hit);
                if (hit) any = true;
            });
            empty?.classList.toggle('hidden', any);
            activeIndex = -1;
        };

        const setActive = (index) => {
            const list = visibleOptions();
            if (!list.length) return;
            activeIndex = ((index % list.length) + list.length) % list.length;
            list.forEach((o) => o.classList.remove(...ACTIVE_CLASS));
            list[activeIndex].classList.add(...ACTIVE_CLASS);
            list[activeIndex].scrollIntoView({ block: 'nearest' });
        };

        const clearActive = () => {
            options.forEach((o) => o.classList.remove(...ACTIVE_CLASS));
            activeIndex = -1;
        };

        const open = () => {
            document.querySelectorAll('[data-mono-select][data-ms-open]').forEach((other) => {
                if (other !== box) other.dispatchEvent(new CustomEvent('ms-close'));
            });
            panel.classList.remove('hidden');
            chevron?.classList.add('rotate-180');
            box.dataset.msOpen = '1';
            clearActive();
            if (search) {
                search.value = '';
                filter('');
                search.focus();
            }
        };

        const close = () => {
            panel.classList.add('hidden');
            chevron?.classList.remove('rotate-180');
            delete box.dataset.msOpen;
            clearActive();
            if (search) {
                search.value = '';
                filter('');
            }
            typeaheadBuffer = '';
        };

        const select = (option) => {
            if (!option) return;
            hidden.value = option.getAttribute('value');
            label.textContent = option.querySelector('[data-ms-option-label]')?.textContent ?? option.textContent.trim();
            label.classList.remove('text-gray-400');
            options.forEach((other) => other.classList.remove('text-[#ff4667]', 'font-semibold'));
            option.classList.add('text-[#ff4667]', 'font-semibold');
            close();
            box.dispatchEvent(new Event('change', { bubbles: true }));
        };

        // First-letter typeahead: jump to the first visible option whose
        // label starts with the typed prefix (buffer resets after 600ms).
        const typeahead = (char) => {
            typeaheadBuffer += char.toLowerCase();
            clearTimeout(typeaheadTimer);
            typeaheadTimer = setTimeout(() => (typeaheadBuffer = ''), 600);

            const list = visibleOptions();
            const hit = list.find((o) => (o.dataset.label || '').startsWith(typeaheadBuffer))
                ?? list.find((o) => (o.dataset.label || '').includes(typeaheadBuffer));
            if (hit) setActive(list.indexOf(hit));
        };

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            panel.classList.contains('hidden') ? open() : close();
        });

        box.addEventListener('ms-close', close);
        document.addEventListener('click', (e) => {
            if (!box.contains(e.target)) close();
        });

        options.forEach((option) => option.addEventListener('click', () => select(option)));

        if (search) {
            search.addEventListener('input', () => filter(search.value));
            search.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    select(visibleOptions().find((o) => !o.classList.contains('hidden')));
                }
            });
        }

        // Keyboard support on the whole widget (trigger keeps focus).
        box.addEventListener('keydown', (e) => {
            const inSearch = e.target === search;
            const isOpen = !panel.classList.contains('hidden');

            if (!isOpen) {
                if (['Enter', ' ', 'ArrowDown'].includes(e.key)) {
                    e.preventDefault();
                    open();
                }
                return;
            }

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    setActive(activeIndex + 1);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    setActive(activeIndex - 1);
                    break;
                case 'Enter':
                    if (!inSearch) {
                        e.preventDefault();
                        select(visibleOptions()[activeIndex] ?? visibleOptions()[0]);
                    }
                    break;
                case 'Escape':
                    e.preventDefault();
                    close();
                    break;
                default:
                    if (!inSearch && e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
                        typeahead(e.key);
                    }
            }
        });

        if (box.hasAttribute('data-ms-autosubmit')) {
            box.addEventListener('change', () => box.closest('form')?.requestSubmit());
        }
    });
}

document.addEventListener('DOMContentLoaded', () => initMonoSelects());
window.initMonoSelects = initMonoSelects;

export { initMonoSelects };
