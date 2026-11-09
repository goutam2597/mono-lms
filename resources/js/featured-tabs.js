/**
 * Featured Courses Category Filtering
 * Enables instant interactive filtering of the Featured Courses grid by category / popularity.
 */
export function initFeaturedTabs() {
    const tabsContainer = document.querySelector('[data-featured-tabs]');
    const grid = document.querySelector('[data-featured-grid]');
    const emptyState = document.querySelector('[data-featured-empty]');
    const resetBtn = document.querySelector('[data-featured-reset]');

    if (!tabsContainer || !grid) return;

    const tabs = Array.from(tabsContainer.querySelectorAll('[data-filter]'));
    const cards = Array.from(grid.querySelectorAll('[data-tags]'));

    const activeClasses = ['border-[#344c3d]', 'text-[#344c3d]', 'bg-[#dce7da]/40', 'font-semibold', 'shadow-xs'];
    const inactiveClasses = ['border-transparent', 'text-[#191919]', 'hover:bg-white', 'hover:shadow-xs', 'font-medium'];

    function applyFilter(filter) {
        let visibleCount = 0;

        tabs.forEach((tab) => {
            const isActive = tab.dataset.filter === filter;
            if (isActive) {
                tab.classList.remove(...inactiveClasses);
                tab.classList.add(...activeClasses);
                tab.setAttribute('aria-pressed', 'true');
            } else {
                tab.classList.remove(...activeClasses);
                tab.classList.add(...inactiveClasses);
                tab.setAttribute('aria-pressed', 'false');
            }
        });

        cards.forEach((card) => {
            const tags = (card.dataset.tags || '').split(/\s+/).filter(Boolean);
            const matches = filter === 'all' || tags.includes(filter);

            if (matches) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (emptyState) {
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }
    }

    tabs.forEach((tab) => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            applyFilter(tab.dataset.filter);
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', (e) => {
            e.preventDefault();
            applyFilter('all');
        });
    }
}

document.addEventListener('DOMContentLoaded', initFeaturedTabs);
