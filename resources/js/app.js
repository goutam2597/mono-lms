import './mono-select';

/**
 * Count-up animation for [data-countup] elements.
 * <span data-countup="1250" data-suffix="+">0+</span>
 * Starts when the element enters the viewport; respects reduced motion.
 */
const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function countUp(el) {
    const target = parseInt(el.dataset.countup, 10) || 0;
    const suffix = el.dataset.suffix ?? '';
    if (reduced || target === 0) {
        el.textContent = target.toLocaleString() + suffix;
        return;
    }

    const duration = 1200;
    const start = performance.now();

    const tick = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
        el.textContent = Math.round(target * eased).toLocaleString() + suffix;
        if (progress < 1) requestAnimationFrame(tick);
    };

    requestAnimationFrame(tick);
}

document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('[data-countup]');
    if (!counters.length) return;

    if (!('IntersectionObserver' in window)) {
        counters.forEach(countUp);
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                countUp(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    counters.forEach((el) => observer.observe(el));
});
