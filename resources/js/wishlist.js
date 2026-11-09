/**
 * Mono LMS - Wishlist / Saved Courses Engine
 * Handles async toggling of saved courses across cards, detail pages, and directories.
 */

function notifyToast(message, type = 'success', options = {}) {
    if (window.monoToast) {
        return window.monoToast.show({ message, type, ...options });
    }
    if (window.showToast) {
        return window.showToast(message, type, options);
    }
}

function updateWishlistButtonState(btn, saved) {
    btn.setAttribute('data-saved', saved ? '1' : '0');
    btn.setAttribute('title', saved ? 'Remove from Saved Courses' : 'Save Course');
    btn.setAttribute('aria-label', saved ? 'Remove from Saved Courses' : 'Save Course');

    const svg = btn.querySelector('svg');
    if (svg) {
        if (saved) {
            svg.setAttribute('fill', 'currentColor');
            svg.classList.remove('fill-none', 'text-slate-600', 'text-slate-500', 'text-gray-500');
            svg.classList.add('fill-rose-500', 'text-rose-500', 'stroke-rose-500');
            btn.classList.add('text-rose-500');
            btn.classList.remove('text-slate-600', 'text-gray-500', 'text-slate-700');
        } else {
            svg.setAttribute('fill', 'none');
            svg.classList.remove('fill-rose-500', 'stroke-rose-500', 'text-rose-500');
            svg.classList.add('fill-none', 'text-slate-600');
            btn.classList.remove('text-rose-500');
            btn.classList.add('text-slate-600');
        }
    }

    const label = btn.querySelector('[data-wishlist-label]');
    if (label) {
        label.textContent = saved ? 'Saved in Wishlist' : 'Save to Wishlist';
    }
}

async function handleWishlistToggle(btn, e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    if (!btn || btn.hasAttribute('data-loading')) {
        return;
    }

    const url = btn.getAttribute('data-wishlist-toggle');
    const isGuest = btn.getAttribute('data-guest') === '1' || url === '/login' || url?.endsWith('/login');

    if (isGuest || !url) {
        notifyToast('Please sign in to save courses.', 'warning', {
            action: { label: 'Sign In', href: '/login' },
        });
        setTimeout(() => {
            window.location.href = '/login';
        }, 700);
        return;
    }

    btn.setAttribute('data-loading', 'true');

    const currentlySaved = btn.getAttribute('data-saved') === '1';
    const nextSaved = !currentlySaved;

    // Optimistic UI update across all buttons referencing this same toggle URL
    const matchingButtons = document.querySelectorAll(`[data-wishlist-toggle="${CSS.escape(url)}"]`);
    matchingButtons.forEach((b) => updateWishlistButtonState(b, nextSaved));

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({}),
        });

        if (response.status === 401) {
            const data = await response.json().catch(() => ({}));
            notifyToast('Please sign in to save courses.', 'warning', {
                action: { label: 'Sign In', href: data.redirect || '/login' },
            });
            setTimeout(() => {
                window.location.href = data.redirect || '/login';
            }, 700);
            return;
        }

        if (!response.ok) {
            throw new Error('Failed to update wishlist');
        }

        const data = await response.json();
        matchingButtons.forEach((b) => updateWishlistButtonState(b, Boolean(data.saved)));

        // Update any badge counters on page
        if (typeof data.count !== 'undefined') {
            document.querySelectorAll('[data-wishlist-count]').forEach((el) => {
                el.textContent = data.count > 0 ? data.count : '';
                el.classList.toggle('hidden', data.count === 0);
            });
        }

        if (data.saved) {
            notifyToast(data.message || 'Course saved to your list.', 'success', {
                action: { label: 'View', href: '/student/saved-courses' },
            });
        } else {
            notifyToast(data.message || 'Course removed from saved courses.', 'info');
        }
    } catch (err) {
        // Rollback on error
        matchingButtons.forEach((b) => updateWishlistButtonState(b, currentlySaved));
        notifyToast('Could not update saved status. Please try again.', 'error');
    } finally {
        btn.removeAttribute('data-loading');
    }
}

// Global click & tap listener
document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-wishlist-toggle]');
    if (btn) {
        handleWishlistToggle(btn, e);
    }
});
