(function () {
    'use strict';

    var config = window.adminTranslatorConfig || {};
    if (!config.url || !config.csrf) return;

    // The host terminates a web request at ~30s and slow (reasoning)
    // models burn ~5-10s before the first token, so every request carries
    // a single locale and one ~600-char value (~2-3 lines): fields are
    // split client-side and stitched after all segments have translated.
    // Measured: ~1400-char segments stall past the 20s client cap on the
    // xhigh reasoning gateway; ~600-char ones finish in ~8-15s.
    var SEGMENT_MAX_CHARS = 600;

    function query(scope, selector) {
        return Array.prototype.slice.call(scope.querySelectorAll(selector));
    }

    function targetRoot(toolbar) {
        var selector = toolbar.dataset.container;
        if (!selector) return document;
        try { return document.querySelector(selector) || document; } catch (e) { return document; }
    }

    function fieldLocale(field) {
        return field.dataset.translateLocale || config.defaultLocale || '';
    }

    function readValue(field) {
        if (field.dataset.translateAdapter === 'quill') {
            var editor = document.querySelector('[data-quill="' + CSS.escape(field.id) + '"], [data-bundle-quill="' + CSS.escape(field.id) + '"]');
            if (editor && editor.__quill) {
                var html = editor.__quill.getSemanticHTML
                    ? editor.__quill.getSemanticHTML()
                    : editor.__quill.root.innerHTML;
                // Quill represents an empty editor as <p></p> or <p><br></p>.
                // Treat that as empty, otherwise the translator creates jobs
                // for fields with no source text and pointlessly retries 422s.
                var probe = document.createElement('div');
                probe.innerHTML = html;
                if (!String(probe.textContent || '').trim() && !probe.querySelector('img,video,audio,iframe')) {
                    return '';
                }
                return html;
            }
        }
        return field.value == null ? '' : String(field.value);
    }

    function writeValue(field, value) {
        if (value === undefined || value === null) return;
        if (field.dataset.translateAdapter === 'quill') {
            var editor = document.querySelector('[data-quill="' + CSS.escape(field.id) + '"], [data-bundle-quill="' + CSS.escape(field.id) + '"]');
            if (editor && editor.__quill) {
                editor.__quill.setContents(editor.__quill.clipboard.convert({ html: value }));
                field.value = value;
                field.dispatchEvent(new Event('input', { bubbles: true }));
                return;
            }
        }
        field.value = value;
        field.dispatchEvent(new Event('input', { bubbles: true }));
        field.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function collect(toolbar) {
        var fields = {};
        query(targetRoot(toolbar), '[data-translate-key][data-translate-locale]').forEach(function (field) {
            var locale = fieldLocale(field);
            var key = field.dataset.translateKey;
            if (!locale || !key || field.disabled) return;
            if (!fields[locale]) fields[locale] = {};
            fields[locale][key] = readValue(field);
        });
        return fields;
    }

    function findTarget(toolbar, locale, key) {
        var root = targetRoot(toolbar);
        return query(root, '[data-translate-key][data-translate-locale]').find(function (field) {
            return fieldLocale(field) === locale && field.dataset.translateKey === key;
        }) || null;
    }

    function targets(toolbar) {
        if (!toolbar.dataset.targets) return null;
        try {
            var parsed = JSON.parse(toolbar.dataset.targets);
            return Array.isArray(parsed) ? parsed : null;
        } catch (e) {
            return null;
        }
    }

    // Split an oversized value into translatable segments after block tags
    // (preferred) or sentence spaces, never mid-word, so stitching restores
    // the original formatting exactly.
    function splitValue(value) {
        if (value.length <= SEGMENT_MAX_CHARS) return [value];
        var pieces = value.split(/(?=<\/(?:p|h1|h2|h3|h4|h5|h6|li|div|blockquote)>)/i);
        var segments = [];
        var current = '';
        pieces.forEach(function (piece) {
            while ((current + piece).length > SEGMENT_MAX_CHARS) {
                if (current) { segments.push(current); current = ''; }
                var window = piece.slice(0, SEGMENT_MAX_CHARS);
                var cut = Math.max(
                    window.lastIndexOf('. '),
                    window.lastIndexOf('! '),
                    window.lastIndexOf('? '),
                    window.lastIndexOf('\n')
                );
                if (cut < SEGMENT_MAX_CHARS / 4) cut = SEGMENT_MAX_CHARS - 1;
                segments.push(piece.slice(0, cut + 1));
                piece = piece.slice(cut + 1);
            }
            if (current && (current + piece).length > SEGMENT_MAX_CHARS) {
                segments.push(current);
                current = '';
            }
            current += piece;
        });
        if (current) segments.push(current);
        return segments;
    }

    function postTranslate(payload) {
        return fetch(config.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        }).then(function (response) {
            return response.json().catch(function () { return {}; }).then(function (body) {
                if (!response.ok) throw body;
                return body;
            });
        });
    }

    function errorMessage(body, fallback) {
        if (body && typeof body === 'object') {
            if (typeof body.message === 'string') return body.message;
            if (body.errors) {
                var first = Object.keys(body.errors)[0];
                if (first && body.errors[first] && body.errors[first][0]) return body.errors[first][0];
            }
        }
        return fallback;
    }

    function failedLabel() {
        return config.labels && config.labels.failed ? config.labels.failed : 'Translation failed.';
    }

    query(document, '[data-ai-translator]').forEach(function (toolbar) {
        var buttons = query(toolbar, '[data-translate-run]');
        var status = toolbar.querySelector('[data-translate-status]');
        var overwrite = toolbar.querySelector('[data-translate-overwrite]');
        if (!buttons.length) return;

        function localeFromSelector(selector) {
            if (selector.dataset.translateLocaleTab) return selector.dataset.translateLocaleTab;
            if (selector.dataset.menuLocale) return selector.dataset.menuLocale;
            var panel = selector.dataset.langPanel || selector.dataset.bundleLang || '';
            if (!panel || /(?:^|-)default$/.test(panel)) return toolbar.dataset.source || config.defaultLocale || '';
            return panel.replace(/^.*?-lang-panel-/, '').replace(/^.*?-lang-/, '').replace(/^lang-panel-/, '');
        }

        function languageSelectors() {
            var root = targetRoot(toolbar);
            return query(root, '[data-translate-locale-tab], .lang-tab[data-lang-panel], .bundle-lang-tab[data-bundle-lang], .mp-lang-tab[data-menu-locale]');
        }

        function activeLocale() {
            var active = languageSelectors().find(function (selector) {
                return selector.classList.contains('is-active') || selector.getAttribute('aria-selected') === 'true';
            });
            if (active) return localeFromSelector(active);
            var configured = targets(toolbar) || [];
            return configured.length === 1 ? configured[0] : toolbar.dataset.source || config.defaultLocale || '';
        }

        function syncLanguageButton(locale) {
            var source = toolbar.dataset.source || config.defaultLocale || '';
            var isSource = !locale || locale === source;
            query(toolbar, '[data-translate-target]').forEach(function (candidate) {
                // On the default/source tab, only Translate All remains; on a
                // target tab, exactly that language's button — never both.
                candidate.hidden = isSource || candidate.dataset.translateTarget !== locale;
            });
            var all = toolbar.querySelector('[data-translate-all]');
            if (all) all.hidden = !isSource;
        }

        languageSelectors().forEach(function (selector) {
            selector.addEventListener('click', function () {
                // Existing page handlers update is-active in the same click;
                // defer one tick so this reads their final selected state.
                setTimeout(function () { syncLanguageButton(localeFromSelector(selector)); }, 0);
            });
        });
        syncLanguageButton(activeLocale());

        buttons.forEach(function (button) {
            var idleLabel = button.textContent;

            button.addEventListener('click', function () {
                if (buttons.some(function (candidate) { return candidate.disabled; })) return;
                buttons.forEach(function (candidate) { candidate.disabled = true; });

                var workingLabel = config.labels && config.labels.working ? config.labels.working : 'Translating…';
                var fx = window.aiFx && window.aiFx.button
                    ? window.aiFx.button.start(button, { label: workingLabel, simulate: false })
                    : null;
                if (!fx) button.textContent = workingLabel;
                var con = null;
                if (status) status.textContent = '';

                var allFields = collect(toolbar);
                var preferredSource = toolbar.dataset.source || config.defaultLocale || '';
                var selectedTarget = button.dataset.translateTarget || '';
                var locales = selectedTarget ? [selectedTarget] : targets(toolbar);
                if (!locales) {
                    locales = Object.keys(allFields).filter(function (locale) {
                        return locale && locale !== preferredSource;
                    });
                }

                // One language-specific click builds source data per field:
                // prefer the primary locale, then any other non-target locale
                // that has content. A blank target is immediately prefilled
                // with this source, so the editor is never left empty while
                // the AI retries or if every provider attempt fails.
                var sourceOrder = [preferredSource].concat(Object.keys(allFields).filter(function (locale) {
                    return locale && locale !== preferredSource && locales.indexOf(locale) < 0;
                }));
                var sourceByKey = {};
                sourceOrder.forEach(function (locale) {
                    Object.keys(allFields[locale] || {}).forEach(function (key) {
                        var value = String(allFields[locale][key] == null ? '' : allFields[locale][key]);
                        if (!sourceByKey[key] && value.trim()) {
                            sourceByKey[key] = { locale: locale, value: value };
                        }
                    });
                });

                var sourceSegments = {};
                Object.keys(sourceByKey).forEach(function (key) {
                    sourceSegments[key] = splitValue(sourceByKey[key].value);
                });

                var canWrite = {};
                locales.forEach(function (locale) {
                    Object.keys(sourceSegments).forEach(function (key) {
                        var field = findTarget(toolbar, locale, key);
                        if (!field) return;
                        var value = readValue(field);
                        (canWrite[locale] = canWrite[locale] || {})[key] =
                            value.trim() === '' || (overwrite && overwrite.checked) ||
                            plainText(value) === plainText(sourceByKey[key].value);
                        if (value.trim() === '') writeValue(field, sourceByKey[key].value);
                    });
                });

                function plainText(html) {
                    var div = document.createElement('div');
                    div.innerHTML = html;
                    return String(div.textContent || '').replace(/\s+/g, ' ').trim();
                }

                // A job is one logical field segment. It may perform several
                // bounded attempts, but only advances progress after it either
                // translates or exhausts its fallback ladder.
                var jobs = [];
                locales.forEach(function (locale) {
                    Object.keys(sourceSegments).forEach(function (key) {
                        if (!canWrite[locale] || !canWrite[locale][key]) return;
                        sourceSegments[key].forEach(function (segment, index) {
                            jobs.push({
                                locale: locale,
                                source: sourceByKey[key].locale,
                                key: key,
                                index: index,
                                count: sourceSegments[key].length,
                                sendKey: key + '__seg' + index,
                                value: segment,
                                parts: null,
                                partIndex: 0,
                                attempt: 0
                            });
                        });
                    });
                });

                var results = {};
                var fallbackCount = 0;
                var lastError = '';
                var jobsDone = 0;
                var runStart = Date.now();

                function syncProgress() {
                    if (fx) fx.update(jobs.length ? jobsDone / jobs.length * 100 : 100);
                }

                function writeProgressive(locale, key) {
                    if (!canWrite[locale] || !canWrite[locale][key]) return;
                    var field = findTarget(toolbar, locale, key);
                    if (!field) return;
                    var bucket = (results[locale] || {})[key] || [];
                    var segments = sourceSegments[key] || [];
                    var joined = '';
                    for (var i = 0; i < segments.length; i++) {
                        joined += (typeof bucket[i] === 'string' && bucket[i].trim()) ? bucket[i] : segments[i];
                    }
                    writeValue(field, joined);
                }

                function finish() {
                    var appliedLocales = {};
                    var changed = false;
                    locales.forEach(function (locale) {
                        Object.keys(sourceSegments).forEach(function (key) {
                            if (!canWrite[locale] || !canWrite[locale][key]) return;
                            var field = findTarget(toolbar, locale, key);
                            if (!field) return;
                            var bucket = (results[locale] || {})[key] || [];
                            var joined = sourceSegments[key].map(function (segment, index) {
                                return (typeof bucket[index] === 'string' && bucket[index].trim()) ? bucket[index] : segment;
                            }).join('');
                            writeValue(field, joined);
                            appliedLocales[locale] = true;
                            changed = true;
                        });
                    });
                    if (changed && typeof window.dispatchEvent === 'function') {
                        window.dispatchEvent(new CustomEvent('admin-translator:applied', {
                            detail: { locales: Object.keys(appliedLocales), toolbar: toolbar }
                        }));
                    }
                    if (status) {
                        status.textContent = config.labels && config.labels.done
                            ? config.labels.done
                            : 'Translations filled in — review and save.';
                        if (fallbackCount > 0) {
                            status.textContent += ' (' + fallbackCount + ' piece(s) kept in the source language after retries.)';
                        }
                        if (lastError) status.textContent += ' Last error: ' + lastError;
                    }
                    if (fx) fx.done(fallbackCount === 0);
                    else button.textContent = idleLabel;
                    buttons.forEach(function (candidate) { candidate.disabled = false; });
                    if (con) {
                        var secs = ((Date.now() - runStart) / 1000).toFixed(1);
                        if (fallbackCount > 0) {
                            con.fail('⚠ finished in ' + secs + 's — ' + fallbackCount + ' source-language fallback(s)');
                        } else {
                            con.done('✓ done in ' + secs + 's — all ' + jobs.length + ' piece(s) translated');
                            con.closeAfter(2600);
                        }
                    }
                }

                function labelFor(job) {
                    return job.locale + ' (' + job.key + ' ' + (job.index + 1) + '/' + job.count + ')';
                }

                function requestPart(job, text) {
                    var fields = {};
                    fields[job.source] = {};
                    // Every retry is its own request, so it does not need a
                    // synthetic __seg/__retry key. Keep the canonical field
                    // key: strict contexts (product/blog/bundle/etc.) reject
                    // renamed keys before the provider is ever called.
                    fields[job.source][job.key] = text;
                    return postTranslate({
                        context: toolbar.dataset.context,
                        fields: fields,
                        source: job.source,
                        targets: [job.locale]
                    }).then(function (body) {
                        var translated = (body.translations || {})[job.locale] || {};
                        var value = translated[job.key];
                        if (typeof value !== 'string' || !value.trim()) {
                            throw body;
                        }
                        return value;
                    });
                }

                function attemptJob(job, onSuccess, onFailure) {
                    job.attempt++;
                    // Attempts 1-2 retry the original request. Then halve the
                    // segment and translate each smaller part separately. This
                    // defeats transient timeouts and HTML-tag mistakes without
                    // making the common successful path needlessly expensive.
                    if (job.attempt <= 2) {
                        requestPart(job, job.value).then(onSuccess).catch(function (body) {
                            lastError = errorMessage(body, failedLabel());
                            if (con) con.log('↻ retry ' + job.attempt + '/4 — ' + labelFor(job) + ' — ' + lastError, 'warn');
                            setTimeout(function () { attemptJob(job, onSuccess, onFailure); }, 750 * job.attempt);
                        });
                        return;
                    }

                    if (!job.parts) {
                        job.parts = splitForRetry(job.value);
                        job.partIndex = 0;
                    }
                    var translatedParts = [];
                    (function nextPart() {
                        if (job.partIndex >= job.parts.length) {
                            onSuccess(translatedParts.join(''));
                            return;
                        }
                        var partIndex = job.partIndex;
                        requestPart(job, job.parts[partIndex])
                            .then(function (value) {
                                translatedParts.push(value);
                                job.partIndex++;
                                nextPart();
                            })
                            .catch(function (body) {
                                lastError = errorMessage(body, failedLabel());
                                if (job.attempt < 4) {
                                    job.attempt++;
                                    if (con) con.log('↻ final retry — ' + labelFor(job) + ' part ' + (partIndex + 1) + '/' + job.parts.length, 'warn');
                                    setTimeout(nextPart, 1500);
                                } else {
                                    onFailure();
                                }
                            });
                    })();
                }

                function splitForRetry(value) {
                    if (value.length <= 240) return [value];
                    var midpoint = Math.floor(value.length / 2);
                    var before = Math.max(value.lastIndexOf('</p>', midpoint), value.lastIndexOf('. ', midpoint), value.lastIndexOf('\n', midpoint));
                    var afterCandidates = [value.indexOf('</p>', midpoint), value.indexOf('. ', midpoint), value.indexOf('\n', midpoint)].filter(function (position) { return position >= 0; });
                    var after = afterCandidates.length ? Math.min.apply(Math, afterCandidates) : -1;
                    var cut = before > value.length / 4 ? before + (value.substr(before, 4).toLowerCase() === '</p>' ? 4 : 2) : after;
                    if (cut < 1 || cut >= value.length) cut = midpoint;
                    return [value.slice(0, cut), value.slice(cut)].filter(function (part) { return part !== ''; });
                }

                function runNext(index) {
                    if (index >= jobs.length) { finish(); return; }
                    var job = jobs[index];
                    var label = labelFor(job);
                    if (status) status.textContent = label + '…';
                    var jobStart = Date.now();
                    if (con) con.thinking(label);
                    attemptJob(job, function (value) {
                        var bucket = (results[job.locale] = results[job.locale] || {})[job.key];
                        if (!bucket) bucket = results[job.locale][job.key] = [];
                        bucket[job.index] = value;
                        writeProgressive(job.locale, job.key);
                        jobsDone++;
                        syncProgress();
                        if (con) con.log('✓ ' + label + ' — ' + ((Date.now() - jobStart) / 1000).toFixed(1) + 's' + (job.attempt > 1 ? ' after retries' : ''), 'ok');
                        runNext(index + 1);
                    }, function () {
                        fallbackCount++;
                        var bucket = (results[job.locale] = results[job.locale] || {})[job.key];
                        if (!bucket) bucket = results[job.locale][job.key] = [];
                        bucket[job.index] = job.value;
                        writeProgressive(job.locale, job.key);
                        jobsDone++;
                        syncProgress();
                        if (con) con.log('⚠ ' + label + ' — kept source text after 4 attempts', 'warn');
                        runNext(index + 1);
                    });
                }

                if (!jobs.length) {
                    if (fx) fx.restore();
                    else button.textContent = idleLabel;
                    buttons.forEach(function (candidate) { candidate.disabled = false; });
                    if (status) status.textContent = 'Nothing empty or overwriteable for this language.';
                    return;
                }

                if (window.aiFx && window.aiFx.console) {
                    con = window.aiFx.console;
                    con.open(idleLabel);
                    con.log(locales.join(', ') + ' · ' + jobs.length + ' piece(s) · retry up to 4×', 'dim');
                }
                syncProgress();
                runNext(0);
            });
        });
    });
})();
