/**
 * Carousel Slider:
 * - Single-line horizontal presentation
 * - Dual navigation arrows on both sides
 * - Seamless infinite looping with buffer clones
 * - Dynamic pagination dots with animated active pill
 * - Touch swipe & mouse drag support
 * - Responsive layout adaptation (supports categories: 5-desktop & trending courses: 4-desktop)
 */

export function createSlider(root) {
    if (!root || root.dataset.sliderReady === '1') return;
    root.dataset.sliderReady = '1';

    const container = root.querySelector('[data-slider-container]');
    const track = root.querySelector('[data-slider-track]');
    const prevBtn = root.querySelector('[data-slider-prev]');
    const nextBtn = root.querySelector('[data-slider-next]');
    const dotsContainer = root.parentElement ? root.parentElement.querySelector('[data-slider-dots]') : root.querySelector('[data-slider-dots]');

    if (!container || !track) return;

    const originalCards = Array.from(track.querySelectorAll('[data-slider-card]'));
    const totalOriginals = originalCards.length;
    if (totalOriginals === 0) return;

    const isTrending = root.hasAttribute('data-trending-slider');
    const maxVisibleDesktop = isTrending ? 4 : 5;

    let currentIndex = 0;
    let isTransitioning = false;
    let cardWidth = 0;
    let gap = 24;
    let visibleCount = getVisibleCount();
    let numPages = Math.max(1, Math.ceil(totalOriginals / visibleCount));

    function getVisibleCount() {
        const w = window.innerWidth;
        if (w >= 1024) return maxVisibleDesktop;
        if (w >= 768) return Math.min(3, maxVisibleDesktop);
        if (w >= 640) return Math.min(2, maxVisibleDesktop);
        return 1;
    }

    function getGap() {
        return window.innerWidth >= 640 ? 24 : 20;
    }

    // Clone items for infinite looping buffer
    const cloneCount = Math.min(totalOriginals, Math.max(visibleCount, maxVisibleDesktop));

    const prefixClones = originalCards.slice(-cloneCount).map((card) => {
        const clone = card.cloneNode(true);
        clone.setAttribute('data-slider-clone', 'prefix');
        clone.setAttribute('aria-hidden', 'true');
        return clone;
    });

    const suffixClones = originalCards.slice(0, cloneCount).map((card) => {
        const clone = card.cloneNode(true);
        clone.setAttribute('data-slider-clone', 'suffix');
        clone.setAttribute('aria-hidden', 'true');
        return clone;
    });

    prefixClones.forEach((clone) => track.insertBefore(clone, track.firstChild));
    suffixClones.forEach((clone) => track.appendChild(clone));

    currentIndex = cloneCount;

    function measure() {
        visibleCount = getVisibleCount();
        gap = getGap();
        const containerWidth = container.clientWidth;

        if (window.innerWidth < 640) {
            cardWidth = Math.min(isTrending ? 300 : 270, Math.floor(containerWidth * 0.85));
        } else {
            cardWidth = (containerWidth - (visibleCount - 1) * gap) / visibleCount;
        }

        const allCards = track.querySelectorAll('[data-slider-card]');
        allCards.forEach((c) => {
            c.style.width = `${cardWidth}px`;
            c.style.flexShrink = '0';
        });

        numPages = Math.max(1, Math.ceil(totalOriginals / visibleCount));
        renderDots();
        moveToIndex(currentIndex, false);
    }

    function moveToIndex(index, animate = true) {
        currentIndex = index;
        const step = cardWidth + gap;
        const targetX = -(currentIndex * step);

        if (animate) {
            isTransitioning = true;
            track.style.transition = 'transform 450ms cubic-bezier(0.22, 1, 0.36, 1)';
        } else {
            track.style.transition = 'none';
        }

        track.style.transform = `translateX(${targetX}px)`;
        updateDots();
    }

    track.addEventListener('transitionend', () => {
        isTransitioning = false;
        const step = cardWidth + gap;

        if (currentIndex >= totalOriginals + cloneCount) {
            // Jump back to start of originals seamlessly
            currentIndex = currentIndex - totalOriginals;
            track.style.transition = 'none';
            track.style.transform = `translateX(${-(currentIndex * step)}px)`;
        } else if (currentIndex < cloneCount) {
            // Jump forward to end of originals seamlessly
            currentIndex = currentIndex + totalOriginals;
            track.style.transition = 'none';
            track.style.transform = `translateX(${-(currentIndex * step)}px)`;
        }

        updateDots();
    });

    function slideNext() {
        if (isTransitioning) return;
        const stepCount = visibleCount > 1 ? visibleCount : 1;
        moveToIndex(currentIndex + stepCount, true);
    }

    function slidePrev() {
        if (isTransitioning) return;
        const stepCount = visibleCount > 1 ? visibleCount : 1;
        moveToIndex(currentIndex - stepCount, true);
    }

    if (nextBtn) nextBtn.addEventListener('click', slideNext);
    if (prevBtn) prevBtn.addEventListener('click', slidePrev);

    // Dynamic Pagination Dots
    function renderDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';

        for (let p = 0; p < numPages; p++) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'slider-dot transition-all duration-300 rounded-full cursor-pointer focus:outline-none';
            dot.setAttribute('aria-label', `Go to slide page ${p + 1}`);
            dot.dataset.page = String(p);

            dot.addEventListener('click', () => {
                if (isTransitioning) return;
                const targetIndex = cloneCount + p * visibleCount;
                moveToIndex(targetIndex, true);
            });

            dotsContainer.appendChild(dot);
        }
        updateDots();
    }

    function updateDots() {
        if (!dotsContainer) return;
        const dots = dotsContainer.querySelectorAll('.slider-dot');
        if (!dots.length) return;

        const normalizedIndex = (currentIndex - cloneCount + totalOriginals * 100) % totalOriginals;
        const activePage = Math.min(numPages - 1, Math.floor(normalizedIndex / visibleCount));

        dots.forEach((dot, idx) => {
            if (idx === activePage) {
                dot.className = 'slider-dot transition-all duration-300 w-8 h-2.5 rounded-full bg-[#344c3d] shadow-sm cursor-pointer';
                dot.setAttribute('aria-current', 'true');
            } else {
                dot.className = 'slider-dot transition-all duration-300 w-2.5 h-2.5 rounded-full bg-[#DCE7DA] hover:bg-[#8EA58C] cursor-pointer';
                dot.removeAttribute('aria-current');
            }
        });
    }

    // Touch & Mouse Drag / Swipe
    let isDragging = false;
    let dragMoved = false;
    let dragDistancePassed = false;
    let startX = 0;

    function onPointerDown(e) {
        if (e.button !== undefined && e.button !== 0) return;
        isDragging = true;
        dragMoved = false;
        dragDistancePassed = false;
        startX = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
        track.style.transition = 'none';
        track.style.cursor = 'grabbing';
    }

    function onPointerMove(e) {
        if (!isDragging) return;
        const currentX = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
        const deltaX = currentX - startX;

        if (Math.abs(deltaX) > 6) {
            dragMoved = true;
            dragDistancePassed = true;
        }

        if (dragMoved) {
            const step = cardWidth + gap;
            const currentTranslate = -(currentIndex * step);
            track.style.transform = `translateX(${currentTranslate + deltaX}px)`;
        }
    }

    function onPointerUp(e) {
        if (!isDragging) return;
        isDragging = false;
        track.style.cursor = '';

        if (!dragMoved) return;

        const endX = e.type.includes('mouse') ? e.pageX : (e.changedTouches ? e.changedTouches[0].clientX : startX);
        const deltaX = endX - startX;
        const threshold = cardWidth * 0.22;

        if (deltaX < -threshold) {
            slideNext();
        } else if (deltaX > threshold) {
            slidePrev();
        } else {
            moveToIndex(currentIndex, true);
        }

        setTimeout(() => {
            dragMoved = false;
            dragDistancePassed = false;
        }, 80);
    }

    // Prevent navigation click when user was dragging
    track.addEventListener('click', (e) => {
        if (dragDistancePassed) {
            e.preventDefault();
            e.stopPropagation();
        }
    }, true);

    container.addEventListener('mousedown', onPointerDown);
    window.addEventListener('mousemove', onPointerMove);
    window.addEventListener('mouseup', onPointerUp);

    container.addEventListener('touchstart', onPointerDown, { passive: true });
    container.addEventListener('touchmove', onPointerMove, { passive: true });
    container.addEventListener('touchend', onPointerUp);

    // Keyboard support
    root.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') slidePrev();
        if (e.key === 'ArrowRight') slideNext();
    });

    // Resize handling
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(measure, 100);
    });

    measure();
}

export function initSliders() {
    document.querySelectorAll('[data-category-slider], [data-trending-slider]').forEach(createSlider);
}

export function initCategorySlider() {
    initSliders();
}

document.addEventListener('DOMContentLoaded', initSliders);
