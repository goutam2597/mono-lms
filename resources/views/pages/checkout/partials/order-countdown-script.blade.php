{{-- 60-minute reservation countdown (mono_market pattern): runs every
     [data-countdown] element on the page, 1s tick, red under 5 minutes. --}}
<script>
    (function () {
        var els = document.querySelectorAll('[data-countdown]');
        if (! els.length) return;

        els.forEach(function (el) {
            var deadline = el.dataset.deadline ? new Date(el.dataset.deadline).getTime() : 0;
            if (! deadline) return;

            function tick() {
                if (! document.body.contains(el)) return;
                var left = Math.max(0, Math.floor((deadline - Date.now()) / 1000));
                if (left <= 0) {
                    el.textContent = 'Expired';
                    el.classList.add('text-red-600');
                    return;
                }
                var m = Math.floor(left / 60), s = left % 60;
                el.textContent = m + ':' + (s < 10 ? '0' : '') + s;
                if (m < 5) el.classList.add('text-red-600');
                setTimeout(tick, 1000);
            }

            tick();
        });
    })();
</script>
