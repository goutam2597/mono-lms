/**
 * Hero orbital showcase: crossfades the central image slides every 8 seconds.
 * The 5 orbital stat pods remain fixed in their pentagon alignment around the orbit.
 */
const INTERVAL = 8000;

function initHeroShowcase() {
    const root = document.querySelector('[data-hero-showcase]');
    if (!root || root.dataset.heroReady) return;
    root.dataset.heroReady = '1';

    const slides = [...root.querySelectorAll('[data-hero-slide]')];
    if (!slides.length) return;

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let tick = 0;

    // Initial state: first slide visible.
    slides.forEach((slide, i) => slide.classList.toggle('is-on', i === 0));

    if (reduced || slides.length <= 1) return;

    setInterval(() => {
        tick++;
        slides.forEach((slide, i) => slide.classList.toggle('is-on', i === tick % slides.length));
    }, INTERVAL);
}

document.addEventListener('DOMContentLoaded', initHeroShowcase);
window.initHeroShowcase = initHeroShowcase;

export { initHeroShowcase };
