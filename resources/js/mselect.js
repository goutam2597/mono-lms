/**
 * Custom select (mselect) — mono_market pattern for Mono LMS
 * Converts native <select> elements into accessible, beautiful, fixed-floating dropdown lists.
 */
(function () {
    var openList = null;

    // Intercept native HTMLSelectElement.prototype.value setter so programmatic updates
    // (e.g. modal editAdmin `roleIn.value = '2'`) instantly update the custom dropdown trigger label!
    try {
        var originalDescriptor = Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype, 'value');
        if (originalDescriptor && !HTMLSelectElement.prototype._mselectWrapped) {
            HTMLSelectElement.prototype._mselectWrapped = true;
            var origSet = originalDescriptor.set;
            Object.defineProperty(HTMLSelectElement.prototype, 'value', {
                set: function (val) {
                    origSet.call(this, val);
                    if (typeof this._mselectSync === 'function') {
                        this._mselectSync();
                    }
                },
                get: originalDescriptor.get,
                configurable: true
            });
        }
    } catch (e) {
        // Fallback silently if environment restricts prototype modification
    }

    function closeOpen() {
        if (!openList) return;
        var cur = openList;
        openList = null;
        cur.wrap.classList.remove('open');
        cur.btn.setAttribute('aria-expanded', 'false');
        cur.list.classList.remove('show');
        setTimeout(function () {
            if (cur.list && cur.list.parentNode) {
                cur.list.remove();
            }
        }, 180);
        document.removeEventListener('click', onDocClick, true);
        window.removeEventListener('scroll', onWinScroll, true);
        window.removeEventListener('resize', closeOpen);
    }

    function positionList() {
        if (!openList) return;
        var r = openList.btn.getBoundingClientRect();
        var list = openList.list;
        var cap = parseInt(list.style.maxHeight, 10) || 260;
        var listH = Math.min(list.offsetHeight || 0, cap);
        var minW = parseInt(openList.sel.getAttribute('data-mselect-min-width'), 10) || 0;
        var width = Math.max(r.width, minW);
        var below = window.innerHeight - r.bottom;

        if (below < listH + 8 && r.top > listH + 8) {
            list.style.top = (r.top - listH - 4) + 'px';
        } else {
            list.style.top = (r.bottom + 4) + 'px';
        }

        var left = Math.min(r.left, Math.max(8, window.innerWidth - width - 8));
        list.style.left = left + 'px';
        list.style.width = width + 'px';

        if (document.documentElement.dir === 'rtl') {
            var overflow = (r.left + r.width) - window.innerWidth;
            if (overflow > 0) {
                list.style.left = Math.max(8, r.left - overflow - 8) + 'px';
            }
        }
    }

    function onWinScroll(e) {
        if (!openList) return;
        if (openList.list.contains(e.target)) return;
        positionList();
    }

    function onDocClick(e) {
        if (openList && !openList.list.contains(e.target) && !openList.btn.contains(e.target)) {
            closeOpen();
        }
    }

    function buildList(sel, syncLabel) {
        var list = document.createElement('div');
        list.className = 'mselect-list' + (sel.getAttribute('data-mselect-list-class') ? ' ' + sel.getAttribute('data-mselect-list-class') : '');
        if (sel.getAttribute('data-mselect-max-height')) {
            list.style.maxHeight = sel.getAttribute('data-mselect-max-height') + 'px';
        }

        var items = [];
        var lastGroup = null;
        var emptyEl = null;

        function addOption(opt) {
            if (opt.disabled && !opt.value) return;
            var o = document.createElement('div');
            o.className = 'mselect-opt' + (opt.selected ? ' is-selected' : '');
            o.textContent = opt.textContent;

            o.addEventListener('click', function () {
                sel.value = opt.value;
                syncLabel();
                sel.dispatchEvent(new Event('change', { bubbles: true }));
                sel.dispatchEvent(new Event('input', { bubbles: true }));
                closeOpen();
            });

            list.insertBefore(o, emptyEl);
            items.push({ el: o, text: opt.textContent.toLowerCase(), group: lastGroup });
        }

        function renderOptions() {
            Array.prototype.forEach.call(list.querySelectorAll('.mselect-opt,.mselect-group'), function (n) { n.remove(); });
            items.length = 0;
            lastGroup = null;
            Array.prototype.forEach.call(sel.children, function (child) {
                if (child.tagName === 'OPTGROUP') {
                    var g = document.createElement('div');
                    g.className = 'mselect-group';
                    g.textContent = child.label;
                    list.insertBefore(g, emptyEl);
                    lastGroup = g;
                    Array.prototype.forEach.call(child.children, addOption);
                } else if (child.tagName === 'OPTION') {
                    addOption(child);
                }
            });
        }

        renderOptions();

        // Search filter for lists with 6+ options unless disabled
        var shouldSearch = items.length >= 6 && !sel.hasAttribute('data-mselect-no-search');
        if (shouldSearch) {
            var searchWrap = document.createElement('div');
            searchWrap.className = 'mselect-search';
            var input = document.createElement('input');
            input.type = 'text';
            input.placeholder = sel.getAttribute('data-mselect-search') || 'Search…';
            searchWrap.appendChild(input);
            list.insertBefore(searchWrap, list.firstChild);

            var empty = document.createElement('div');
            empty.className = 'mselect-empty';
            empty.textContent = 'No results found';
            list.appendChild(empty);
            emptyEl = empty;

            function applyLocalFilter(q) {
                var visible = 0;
                items.forEach(function (it) {
                    var show = !q || it.text.indexOf(q) !== -1;
                    it.el.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                Array.prototype.forEach.call(list.querySelectorAll('.mselect-group'), function (g) {
                    var hasVisible = items.some(function (it) { return it.group === g && it.el.style.display !== 'none'; });
                    g.style.display = hasVisible ? '' : 'none';
                });
                empty.style.display = visible ? 'none' : 'block';
            }

            input.addEventListener('input', function () {
                applyLocalFilter(input.value.trim().toLowerCase());
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeOpen();
                if (e.key === 'Enter') {
                    e.preventDefault();
                    var first = items.filter(function (it) { return it.el.style.display !== 'none'; })[0];
                    if (first) first.el.click();
                }
            });

            setTimeout(function () { input.focus(); }, 10);
        }

        return list;
    }

    function enhanceSelect(sel) {
        if (!sel || sel.dataset.mselectDone) return;
        if (sel.hasAttribute('data-no-mselect')) return;
        // Skip hidden template selects or zero-sized helper controls
        if (sel.closest('.mono-select')) return; // Allow older mono-select to coexist if any

        sel.dataset.mselectDone = '1';

        var wrap = document.createElement('div');
        wrap.className = 'mselect';
        sel.parentNode.insertBefore(wrap, sel);
        wrap.appendChild(sel);

        try {
            var hostStyle = window.getComputedStyle(wrap.parentNode);
            if ((hostStyle.display === 'flex' || hostStyle.display === 'inline-flex') && hostStyle.flexDirection === 'row') {
                wrap.style.minWidth = 'fit-content';
                wrap.style.maxWidth = '100%';
            }
        } catch (fitErr) {}

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'mselect-btn' + (sel.className ? ' ' + sel.className : '');
        btn.setAttribute('aria-haspopup', 'listbox');
        btn.setAttribute('aria-expanded', 'false');

        if (sel.id) {
            var associatedLabel = document.querySelector('label[for="' + sel.id.replace(/(["\\])/g, '\\$1') + '"]');
            if (associatedLabel) {
                if (!associatedLabel.id) associatedLabel.id = sel.id + '-label';
                btn.setAttribute('aria-labelledby', associatedLabel.id);
                associatedLabel.addEventListener('click', function (event) {
                    event.preventDefault();
                    btn.focus();
                });
            }
        }
        if (sel.hasAttribute('aria-label')) btn.setAttribute('aria-label', sel.getAttribute('aria-label'));
        if (sel.disabled) btn.disabled = true;

        var label = document.createElement('span');
        label.className = 'mselect-label';

        var chev = document.createElement('span');
        chev.className = 'mselect-chev';
        chev.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>';

        btn.appendChild(label);
        btn.appendChild(chev);
        wrap.appendChild(btn);

        function syncLabel() {
            var opt = sel.options[sel.selectedIndex];
            var text = opt ? (opt.getAttribute('data-trigger') || opt.textContent) : (sel.getAttribute('placeholder') || 'Select…');
            label.textContent = text;
            btn.title = opt ? opt.textContent : '';
        }

        sel._mselectSync = syncLabel;
        syncLabel();
        sel.addEventListener('change', syncLabel);

        // Watch for dynamically added or modified options
        if (window.MutationObserver) {
            var mutObserver = new MutationObserver(function () {
                syncLabel();
            });
            mutObserver.observe(sel, { childList: true, subtree: true, characterData: true });
        }

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (openList && openList.btn === btn) { closeOpen(); return; }
            closeOpen();

            var list = buildList(sel, syncLabel);
            document.body.appendChild(list);
            btn.setAttribute('aria-expanded', 'true');
            openList = { list: list, wrap: wrap, btn: btn, sel: sel };
            positionList();
            requestAnimationFrame(function () { list.classList.add('show'); });
            wrap.classList.add('open');

            document.addEventListener('click', onDocClick, true);
            window.addEventListener('scroll', onWinScroll, true);
            window.addEventListener('resize', closeOpen);
        });

        btn.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeOpen();
            if (e.key === 'ArrowDown' || e.key === 'Enter') {
                e.preventDefault();
                btn.click();
            }
        });
    }

    window.mselectEnhance = function (root) {
        var base = root || document;
        base.querySelectorAll('select:not([data-no-mselect])').forEach(enhanceSelect);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            window.mselectEnhance(document);
        });
    } else {
        window.mselectEnhance(document);
    }

    // Auto-enhance dynamically inserted selects (e.g. modals, AJAX responses)
    if (window.MutationObserver) {
        var bodyObserver = new MutationObserver(function (mutations) {
            for (var i = 0; i < mutations.length; i++) {
                if (mutations[i].addedNodes.length) {
                    window.mselectEnhance(document);
                    break;
                }
            }
        });
        if (document.body) {
            bodyObserver.observe(document.body, { childList: true, subtree: true });
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                bodyObserver.observe(document.body, { childList: true, subtree: true });
            });
        }
    }
})();
