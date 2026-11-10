/**
 * Search Suggestions — attaches to any input[data-suggest]
 * Mirrors mono_market pattern: uses data-suggest-container selector to anchor
 * the floating dropdown without altering flex/grid layouts.
 */

const I18N = (typeof window !== 'undefined' && window.searchSuggestI18n) || {};
function tr(key, fallback) {
    const live = (typeof window !== 'undefined' && window.searchSuggestI18n) || I18N;
    return (typeof live[key] === 'string' && live[key]) ? live[key] : fallback;
}

const CSS = `
.ss-drop {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 20px 40px -8px rgba(15, 23, 42, 0.16), 0 8px 16px -4px rgba(15, 23, 42, 0.08);
    display: none;
    z-index: 99999;
    max-height: 490px;
    overflow-y: auto;
    overscroll-behavior: contain;
    animation: ss-in .14s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes ss-in {
    from { opacity: 0; transform: translateY(-4px) scale(0.99); }
    to { opacity: 1; transform: none; }
}
.ss-drop.ss-open { display: block; }
.ss-section {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #94a3b8;
    padding: 10px 16px 4px;
}
.ss-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    transition: background-color .12s ease;
}
.ss-item:hover, .ss-item.ss-active {
    background: #f8fafc;
}
.ss-thumb {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
}
.ss-thumb-ph {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: #f1f5f9;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 16px;
}
.ss-avatar {
    width: 38px;
    height: 38px;
    border-radius: 9999px;
    object-fit: cover;
    flex-shrink: 0;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
}
.ss-avatar-ph {
    width: 38px;
    height: 38px;
    border-radius: 9999px;
    background: var(--theme-primary, #344c3d);
    color: #ffffff;
    font-weight: 700;
    font-size: 13px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ss-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #f1f5f9;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}
.ss-body {
    flex: 1;
    min-width: 0;
}
.ss-name {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.35;
}
.ss-meta {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ss-badge {
    display: inline-block;
    padding: 1px 5px;
    font-size: 9.5px;
    font-weight: 600;
    background: #f1f5f9;
    color: #475569;
    border-radius: 4px;
}
.ss-price {
    font-size: 13px;
    font-weight: 700;
    color: var(--theme-primary, #344c3d);
    white-space: nowrap;
    flex-shrink: 0;
    text-align: right;
}
.ss-old {
    font-size: 10.5px;
    color: #94a3b8;
    text-decoration: line-through;
    margin-right: 4px;
    font-weight: 500;
}
.ss-sep {
    border: 0;
    border-top: 1px solid #f1f5f9;
    margin: 4px 0;
}
.ss-all {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 11px 16px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--theme-primary, #344c3d);
    border-top: 1px solid #f1f5f9;
    text-decoration: none;
    transition: background-color .12s ease;
}
.ss-all:hover, .ss-all.ss-active {
    background: #f8fafc;
}
.ss-empty {
    text-align: center;
    padding: 24px 16px;
    color: #94a3b8;
    font-size: 13px;
}
mark.ss-hl {
    background: rgba(52, 76, 61, 0.16);
    color: inherit;
    border-radius: 2px;
    padding: 0 2px;
    font-style: normal;
}
`;

function injectCSS() {
    if (typeof document === 'undefined' || document.getElementById('ss-style')) return;
    const s = document.createElement('style');
    s.id = 'ss-style';
    s.textContent = CSS;
    document.head.appendChild(s);
}

function esc(str) {
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function hl(text, q) {
    if (!q) return esc(text);
    const rx = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
    return esc(text).replace(rx, '<mark class="ss-hl">$1</mark>');
}

export function attachSearchSuggest(input) {
    if (!input || input._ssAttached) return;
    input._ssAttached = true;

    const url = input.getAttribute('data-suggest');
    if (!url) return;

    // Find positioning container (never insert extra wrappers into page layout)
    const cSel = input.getAttribute('data-suggest-container');
    let container = cSel ? input.closest(cSel) : null;
    if (!container) container = input.closest('form') || input.parentElement;

    if (window.getComputedStyle(container).position === 'static') {
        container.style.position = 'relative';
    }

    const drop = document.createElement('div');
    drop.className = 'ss-drop';
    container.appendChild(drop);

    let timer = null;
    let lastQ = '';
    let allItems = [];
    let active = -1;

    function open() { drop.classList.add('ss-open'); }
    function close() { drop.classList.remove('ss-open'); active = -1; }

    function setActive(i) {
        allItems.forEach((el, idx) => el.classList.toggle('ss-active', idx === i));
        active = i;
        if (i >= 0 && allItems[i]) {
            allItems[i].scrollIntoView({ block: 'nearest' });
        }
    }

    function addEl(tag, cls, parent) {
        const el = document.createElement(tag);
        el.className = cls;
        parent.appendChild(el);
        return el;
    }

    function render(data, q) {
        drop.innerHTML = '';
        allItems = [];

        const hasCourses = data.courses && data.courses.length;
        const hasCategories = data.categories && data.categories.length;
        const hasInstructors = data.instructors && data.instructors.length;

        if (!hasCourses && !hasCategories && !hasInstructors) {
            drop.innerHTML = `<div class="ss-empty">${esc(tr('no_results_for', 'No results for'))} &ldquo;<b>${esc(q)}</b>&rdquo;</div>`;
            open();
            return;
        }

        function section(label) { addEl('div', 'ss-section', drop).textContent = label; }
        function sep() { addEl('hr', 'ss-sep', drop); }
        function item(html, href) {
            const a = addEl('a', 'ss-item', drop);
            a.href = href;
            a.innerHTML = html;
            allItems.push(a);
        }

        // 1. Courses
        if (hasCourses) {
            section(tr('courses', 'Courses'));
            data.courses.forEach((c) => {
                const img = c.image
                    ? `<img class="ss-thumb" src="${esc(c.image)}" alt="" loading="lazy">`
                    : `<div class="ss-thumb-ph"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>`;

                const priceHtml = (c.old_price ? `<span class="ss-old">${esc(c.old_price)}</span>` : '') + `<span class="${c.price === 'Free' ? 'text-emerald-600 font-bold' : ''}">${esc(c.price)}</span>`;

                const metaParts = [];
                if (c.category) metaParts.push(`<span class="ss-badge">${esc(c.category)}</span>`);
                if (c.instructor) metaParts.push(`<span>by ${esc(c.instructor)}</span>`);

                item(
                    img +
                    `<div class="ss-body">` +
                        `<div class="ss-name">${hl(c.name, q)}</div>` +
                        (metaParts.length ? `<div class="ss-meta">${metaParts.join('')}</div>` : '') +
                    `</div>` +
                    `<div class="ss-price">${priceHtml}</div>`,
                    c.url
                );
            });
        }

        // 2. Categories
        if (hasCategories) {
            if (hasCourses) sep();
            section(tr('categories', 'Categories'));
            data.categories.forEach((cat) => {
                item(
                    `<div class="ss-icon">` +
                        `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>` +
                    `</div>` +
                    `<div class="ss-body">` +
                        `<div class="ss-name">${hl(cat.name, q)}</div>` +
                        `<div class="ss-meta">${esc(cat.count)} ${esc(tr('courses_count', 'courses'))}</div>` +
                    `</div>`,
                    cat.url
                );
            });
        }

        // 3. Instructors
        if (hasInstructors) {
            if (hasCourses || hasCategories) sep();
            section(tr('instructors', 'Instructors'));
            data.instructors.forEach((inst) => {
                const avatar = inst.avatar
                    ? `<img class="ss-avatar" src="${esc(inst.avatar)}" alt="" loading="lazy">`
                    : `<div class="ss-avatar-ph">${esc(inst.name.charAt(0).toUpperCase())}</div>`;

                item(
                    avatar +
                    `<div class="ss-body">` +
                        `<div class="ss-name">${hl(inst.name, q)}</div>` +
                        `<div class="ss-meta"><span>${esc(inst.designation)}</span> &bull; <span>${esc(inst.count)} ${esc(tr('courses_count', 'courses'))}</span></div>` +
                    `</div>`,
                    inst.url
                );
            });
        }

        // Bottom "See all results"
        const seeAllUrl = data.see_all_url || ('courses?q=' + encodeURIComponent(q));
        const all = addEl('a', 'ss-all', drop);
        all.href = seeAllUrl;
        all.innerHTML = `<span>${esc(tr('see_all_results', 'See all results for'))} &ldquo;<b>${esc(q)}</b>&rdquo;</span> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>`;
        allItems.push(all);

        open();
    }

    function fetchSuggestions(q) {
        const fetchUrl = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'q=' + encodeURIComponent(q);
        fetch(fetchUrl, { headers: { 'Accept': 'application/json' } })
            .then((r) => r.ok ? r.json() : null)
            .then((data) => {
                if (data) {
                    lastQ = q;
                    render(data, q);
                }
            })
            .catch(() => {});
    }

    // Event listeners
    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) {
            close();
            return;
        }
        timer = setTimeout(() => fetchSuggestions(q), 250);
    });

    input.addEventListener('keydown', function (e) {
        if (!drop.classList.contains('ss-open')) return;
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            setActive(Math.min(active + 1, allItems.length - 1));
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            setActive(active <= 0 ? -1 : active - 1);
        } else if (e.key === 'Enter' && active >= 0 && allItems[active]) {
            e.preventDefault();
            allItems[active].click();
        } else if (e.key === 'Escape') {
            close();
        }
    });

    input.addEventListener('focus', function () {
        if (this.value.trim().length >= 2 && lastQ === this.value.trim()) {
            open();
        }
    });

    document.addEventListener('click', function (e) {
        if (!container.contains(e.target) && e.target !== input) {
            close();
        }
    });
}

export function initSearchSuggest() {
    injectCSS();
    if (typeof document !== 'undefined') {
        document.querySelectorAll('input[data-suggest]').forEach(attachSearchSuggest);
    }
}

if (typeof window !== 'undefined') {
    window.initSearchSuggest = initSearchSuggest;
    window.attachSearchSuggest = attachSearchSuggest;
}

if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearchSuggest);
    } else {
        initSearchSuggest();
    }
}
