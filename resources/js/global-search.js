/**
 * Mono LMS - Global Search Bar
 * Handles opening and closing of the full-width search drawer below the navbar.
 */

export function initGlobalSearch() {
    const toggleBtn = document.getElementById('global-search-toggle');
    const searchBar = document.getElementById('global-search-bar');
    const searchInput = document.getElementById('global-search-input');
    const closeBtn = document.getElementById('global-search-cancel');
    const backdrop = document.getElementById('global-search-backdrop');
    const iconOpen = document.getElementById('global-search-icon-open');
    const iconClose = document.getElementById('global-search-icon-close');

    if (!toggleBtn || !searchBar) return;

    function openSearch() {
        searchBar.classList.remove('hidden');
        if (backdrop) backdrop.classList.remove('hidden');
        if (iconOpen) iconOpen.classList.add('hidden');
        if (iconClose) iconClose.classList.remove('hidden');
        toggleBtn.setAttribute('aria-expanded', 'true');
        toggleBtn.classList.add('bg-slate-100');

        setTimeout(() => {
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }, 50);
    }

    function closeSearch() {
        searchBar.classList.add('hidden');
        if (backdrop) backdrop.classList.add('hidden');
        if (iconOpen) iconOpen.classList.remove('hidden');
        if (iconClose) iconClose.classList.add('hidden');
        toggleBtn.setAttribute('aria-expanded', 'false');
        toggleBtn.classList.remove('bg-slate-100');
    }

    function toggleSearch() {
        const isOpen = !searchBar.classList.contains('hidden');
        if (isOpen) {
            closeSearch();
        } else {
            openSearch();
        }
    }

    toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        toggleSearch();
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            closeSearch();
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', () => {
            closeSearch();
        });
    }

    // Close on Escape, Open on '/' (when not in an input/textarea)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !searchBar.classList.contains('hidden')) {
            closeSearch();
            toggleBtn.focus();
            return;
        }

        if (e.key === '/' && searchBar.classList.contains('hidden')) {
            const activeTag = document.activeElement ? document.activeElement.tagName.toLowerCase() : '';
            if (activeTag !== 'input' && activeTag !== 'textarea' && activeTag !== 'select' && !document.activeElement?.isContentEditable) {
                e.preventDefault();
                openSearch();
            }
        }
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!searchBar.classList.contains('hidden')) {
            if (!searchBar.contains(e.target) && !toggleBtn.contains(e.target)) {
                closeSearch();
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', initGlobalSearch);
