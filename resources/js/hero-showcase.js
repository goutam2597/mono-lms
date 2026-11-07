/**
 * Hero showcase: rotates the floating stat chips around the hero image and
 * crossfades the image slides every 10 seconds.
 *
 * Markup (home.blade.php):
 *   [data-hero-showcase]        root
 *   [data-hero-slot]            6 fixed chip slots, first 6 items pre-rendered
 *   template[data-hero-item]    12 chip contents (icon + label + value)
 *   [data-hero-slide]           stacked image slides
 */
const INTERVAL = 10000;

function initHeroShowcase() {
    const root = document.querySelector('[data-hero-showcase]');
    if (!root || root.dataset.heroReady) return;
    root.dataset.heroReady = '1';

    const slots = [...root.querySelectorAll('[data-hero-slot]')];
    const slides = [...root.querySelectorAll('[data-hero-slide]')];
    const items = [...root.querySelectorAll('template[data-hero-item]')].map((t) => t.content);
    if (!slots.length || !items.length) return;

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let tick = 0;

    const fill = (slot, itemIndex) => {
        slot.innerHTML = '';
        slot.appendChild(items[itemIndex % items.length].cloneNode(true));
    };

    const rotate = () => {
        tick++;

        // Crossfade to the next image slide.
        slides.forEach((slide, i) => slide.classList.toggle('is-on', i === tick % slides.length));

        // Stagger the chips out, swap content, stagger back in.
        slots.forEach((slot, i) => {
            setTimeout(() => {
                const chip = slot.firstElementChild;
                chip?.classList.add('hero-chip-out');
                setTimeout(() => {
                    fill(slot, i + tick * slots.length);
                    const next = slot.firstElementChild;
                    next?.classList.add('hero-chip-in');
                    setTimeout(() => next?.classList.remove('hero-chip-in'), 600);
                }, 320);
            }, i * 90);
        });
    };

    // Initial slide state.
    slides.forEach((slide, i) => slide.classList.toggle('is-on', i === 0));

    if (reduced) return; // static showcase for reduced motion
    setInterval(rotate, INTERVAL);
}

document.addEventListener('DOMContentLoaded', initHeroShowcase);
