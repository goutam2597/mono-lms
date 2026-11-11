/*
 * ai-progress.js — shared AI affordances for the admin and vendor panels.
 *
 * window.aiFx.button  — turns any AI action button into a progress button:
 *                       spinner, live percentage, bottom-edge bar. Real mode
 *                       (caller knows the total) or simulated mode (single
 *                       request, creeps toward 90% and snaps to 100%).
 * window.aiFx.console — a terminal-style "what's cooking inside" panel.
 *                       Every AI flow opens it (text, image, translations):
 *                       open on button tap, auto-close after the run
 *                       settles, stays open on failure so the cause stays
 *                       readable.
 *
 * button.start(btn, { console, note, waiting, … }) layers the console onto
 * the progress button: `console` is the panel title, `note` a dim context
 * line (prompt, language, size…), `waiting` the thinking-line text.
 */
(function () {
    'use strict';
    if (window.aiFx) return;

    var SPIN_FRAMES = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];
    var FLAVOR_WORDS = ['thinking', 'cooking', 'brewing', 'composing', 'reasoning', 'polishing', 'translating', 'warming up'];

    /* ================================ console ================================ */

    var root = null, bodyEl = null, titleEl = null, footEl = null;
    var spinTimer = null, flavorTimer = null, closeTimer = null;
    var spinIdx = 0, flavorIdx = 0, thinkLine = null;

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function clip(s, n) {
        s = String(s == null ? '' : s).replace(/\s+/g, ' ').trim();
        return s.length > n ? s.slice(0, n - 1) + '…' : s;
    }

    function ensureConsole() {
        if (root) return root;
        root = document.createElement('div');
        root.className = 'ai-console';
        root.innerHTML =
            '<div class="ai-console-head">' +
                '<span class="ai-console-dots"><i></i><i></i><i></i></span>' +
                '<span class="ai-console-title">AI CONSOLE</span>' +
                '<button type="button" class="ai-console-x" aria-label="Close">×</button>' +
            '</div>' +
            '<div class="ai-console-body" role="log" aria-live="polite"></div>' +
            '<div class="ai-console-foot"><span class="ai-console-caret">▍</span><span class="ai-console-foot-text"></span></div>';
        document.body.appendChild(root);
        bodyEl = root.querySelector('.ai-console-body');
        titleEl = root.querySelector('.ai-console-title');
        footEl = root.querySelector('.ai-console-foot-text');
        root.querySelector('.ai-console-x').addEventListener('click', close);
        return root;
    }

    function stamp() {
        var d = new Date();
        function p(n) { return (n < 10 ? '0' : '') + n; }
        return p(d.getHours()) + ':' + p(d.getMinutes()) + ':' + p(d.getSeconds());
    }

    function addLine(html, cls) {
        if (!bodyEl) return null;
        var line = document.createElement('div');
        line.className = 'ai-line' + (cls ? ' ' + cls : '');
        line.innerHTML = '<span class="ai-line-t">' + stamp() + '</span>' + html;
        bodyEl.appendChild(line);
        bodyEl.scrollTop = bodyEl.scrollHeight;
        return line;
    }

    function stopTimers() {
        clearInterval(spinTimer); spinTimer = null;
        clearInterval(flavorTimer); flavorTimer = null;
    }

    function dropThinkLine() {
        if (thinkLine && thinkLine.parentNode) thinkLine.parentNode.removeChild(thinkLine);
        thinkLine = null;
    }

    function startTimers() {
        if (spinTimer) return;
        spinTimer = setInterval(function () {
            spinIdx = (spinIdx + 1) % SPIN_FRAMES.length;
            if (thinkLine) thinkLine.querySelector('.ai-spin-ch').textContent = SPIN_FRAMES[spinIdx];
        }, 90);
        flavorTimer = setInterval(function () {
            flavorIdx = (flavorIdx + 1) % FLAVOR_WORDS.length;
            if (thinkLine) thinkLine.querySelector('.ai-flavor').textContent = FLAVOR_WORDS[flavorIdx];
        }, 2400);
    }

    function close() {
        clearTimeout(closeTimer); closeTimer = null;
        if (root) root.classList.remove('open');
    }

    var consoleApi = {
        open: function (title) {
            clearTimeout(closeTimer); closeTimer = null;
            stopTimers(); dropThinkLine();
            ensureConsole();
            bodyEl.innerHTML = '';
            titleEl.textContent = title || 'AI CONSOLE';
            root.classList.add('open');
            addLine('── session start ──', 'head');
            footEl.textContent = 'booting…';
        },
        log: function (msg, cls) {
            ensureConsole();
            addLine(esc(msg), cls || '');
        },
        thinking: function (msg) {
            ensureConsole();
            stopTimers(); dropThinkLine();
            thinkLine = document.createElement('div');
            thinkLine.className = 'ai-line ai-thinking';
            thinkLine.innerHTML =
                '<span class="ai-spin-ch">' + SPIN_FRAMES[spinIdx] + '</span>' +
                '<span class="ai-flavor">' + esc(FLAVOR_WORDS[flavorIdx]) + '</span>' +
                '<span>' + esc(msg) + '</span>';
            bodyEl.appendChild(thinkLine);
            bodyEl.scrollTop = bodyEl.scrollHeight;
            startTimers();
            footEl.textContent = msg;
        },
        done: function (msg) {
            ensureConsole();
            stopTimers(); dropThinkLine();
            addLine(esc(msg), 'ok');
            footEl.textContent = msg;
        },
        fail: function (msg) {
            ensureConsole();
            stopTimers(); dropThinkLine();
            addLine(esc(msg), 'err');
            footEl.textContent = msg;
        },
        closeAfter: function (ms) {
            clearTimeout(closeTimer);
            closeTimer = setTimeout(close, ms || 1800);
        },
        close: close
    };

    /* ================================ button ================================= */

    function isDarkButton(btn) {
        var bg = getComputedStyle(btn).backgroundColor;
        var m = bg.match(/(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/);
        if (!m) return false;
        var lum = 0.2126 * (+m[1]) + 0.7152 * (+m[2]) + 0.0722 * (+m[3]);
        return lum < 128;
    }

    function startSim(duration, onTick) {
        var t0 = performance.now();
        var raf = 0, stopped = false;
        // Ease toward 90% and linger: the request may answer early (snap to
        // 100 on done) or stall (never lie about being almost finished).
        function frame(now) {
            if (stopped) return;
            var t = (now - t0) / duration;
            onTick(90 * (1 - Math.exp(-3.2 * Math.min(t, 1))));
            raf = requestAnimationFrame(frame);
        }
        raf = requestAnimationFrame(frame);
        return { stop: function () { stopped = true; cancelAnimationFrame(raf); } };
    }

    function startButton(btn, opts) {
        opts = opts || {};
        // Re-entry guard: a click landing inside the brief done-flash must not
        // capture the busy markup as the "original" to restore later.
        if (!btn.classList.contains('ai-busy')) btn.__aiOriginal = btn.innerHTML;
        var original = btn.__aiOriginal;

        var label = opts.label || btn.textContent.trim() || '';
        btn.classList.add('ai-busy', isDarkButton(btn) ? 'ai-busy-dark' : 'ai-busy-light');
        btn.innerHTML =
            '<span class="ai-bt">' +
                '<span class="ai-bt-spin" aria-hidden="true"></span>' +
                '<span class="ai-bt-label"></span>' +
                '<span class="ai-bt-pct">0%</span>' +
            '</span>' +
            '<span class="ai-bt-bar" aria-hidden="true"></span>';
        btn.querySelector('.ai-bt-label').textContent = label;

        var pct = 0, sim = null, settled = false;

        function setPct(p, text) {
            if (p != null) pct = Math.max(pct, Math.min(100, Math.round(p)));
            var pctEl = btn.querySelector('.ai-bt-pct');
            if (pctEl) pctEl.textContent = pct + '%';
            var bar = btn.querySelector('.ai-bt-bar');
            if (bar) bar.style.width = pct + '%';
            if (text) {
                var labelEl = btn.querySelector('.ai-bt-label');
                if (labelEl) labelEl.textContent = text;
            }
        }

        if (opts.simulate !== false && opts.total == null) {
            sim = startSim(opts.duration || 22000, function (p) { setPct(p); });
        } else {
            setPct(0);
        }

        return {
            // Real progress (0..100). First call stops the simulator.
            update: function (p, text) {
                if (sim) { sim.stop(); sim = null; }
                setPct(p, text);
            },
            done: function (ok, text) {
                if (settled) return;
                settled = true;
                if (sim) { sim.stop(); sim = null; }
                btn.classList.add(ok ? 'ai-done' : 'ai-fail');
                if (ok) setPct(100, text);
                else if (text) setPct(null, text);
                setTimeout(function () { restore(); }, ok ? 750 : 1500);
            },
            restore: restore
        };

        function restore() {
            btn.innerHTML = original;
            btn.classList.remove('ai-busy', 'ai-busy-dark', 'ai-busy-light', 'ai-done', 'ai-fail');
            delete btn.__aiOriginal;
        }
    }

    /* start() wraps the bare progress button. When opts.console is set the
       terminal panel runs alongside: context note, thinking spinner while
       the request is in flight, then ✓/✗ with the elapsed time. Success
       auto-closes the panel; failure leaves it open so the error stays
       readable — the same contract the translation flows follow. */
    function startWithConsole(btn, opts) {
        var fx = startButton(btn, opts);
        if (!opts || !opts.console) return fx;
        var con = consoleApi, t0 = Date.now();
        con.open(opts.console === true ? (opts.label || 'AI CONSOLE') : String(opts.console));
        if (opts.note) con.log(clip(opts.note, 140), 'dim');
        con.thinking(opts.waiting || 'waiting for the model…');
        return {
            update: fx.update,
            done: function (ok, text) {
                var secs = ((Date.now() - t0) / 1000).toFixed(1) + 's';
                if (ok) {
                    con.done('✓ ' + (text || 'done') + ' — ' + secs);
                    con.closeAfter(2600);
                } else {
                    con.fail('✗ ' + (text || 'failed') + ' — ' + secs);
                }
                // Error text stays out of the button label: it can be a whole
                // server message, and the console already shows it in full.
                fx.done(ok, ok ? text : undefined);
            },
            restore: fx.restore
        };
    }

    window.aiFx = {
        console: consoleApi,
        button: { start: startWithConsole }
    };
})();
