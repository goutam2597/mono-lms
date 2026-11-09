/**
 * Mono LMS - Clean, Professional Toast Notification System
 * Minimalist, understated notification bar (Linear / GitHub style).
 */

class MonoToastManager {
    constructor() {
        this.container = null;
        this.toasts = new Map();
        this.maxToasts = 3;
    }

    getOrCreateContainer() {
        if (!this.container || !document.body.contains(this.container)) {
            this.container = document.createElement('div');
            this.container.id = 'mono-toast-container';
            this.container.className =
                'fixed bottom-5 right-4 sm:bottom-6 sm:right-6 z-[99999] flex flex-col gap-2 pointer-events-none max-w-[calc(100vw-2rem)] sm:max-w-sm w-auto';
            this.container.setAttribute('aria-live', 'polite');
            document.body.appendChild(this.container);
        }
        return this.container;
    }

    show(options = {}) {
        if (typeof options === 'string') {
            options = { message: options };
        }

        const {
            type = 'success',
            message = '',
            action = null, // { label: 'View', href: '/url', onClick: fn }
            duration = 3200,
        } = options;

        if (!message) return;

        const container = this.getOrCreateContainer();

        // Limit concurrent toasts
        const activeToasts = container.querySelectorAll('.mono-toast-item');
        if (activeToasts.length >= this.maxToasts) {
            const oldestId = activeToasts[0].dataset.toastId;
            if (oldestId) this.dismiss(oldestId);
        }

        const id = 'toast-' + Math.random().toString(36).substring(2, 9);
        const toastEl = document.createElement('div');
        toastEl.className =
            'mono-toast-item pointer-events-auto bg-slate-900 text-white border border-slate-800 rounded-lg px-3.5 py-2.5 shadow-xl flex items-center gap-2.5 text-xs transition-all duration-200 transform translate-y-2 opacity-0';
        toastEl.dataset.toastId = id;
        toastEl.setAttribute('role', 'status');

        // Simple, clean status icons
        let iconHtml = '';
        if (type === 'success') {
            iconHtml = `
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            `;
        } else if (type === 'error') {
            iconHtml = `
                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `;
        } else if (type === 'warning') {
            iconHtml = `
                <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            `;
        } else {
            iconHtml = `
                <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
            `;
        }

        // Action text link if present
        let actionHtml = '';
        if (action && action.label) {
            const href = action.href ? `href="${action.href}"` : '#';
            actionHtml = `
                <a ${href} class="mono-toast-action font-semibold text-emerald-400 hover:text-emerald-300 underline underline-offset-2 ml-auto shrink-0 transition-colors">
                    ${action.label}
                </a>
            `;
        }

        toastEl.innerHTML = `
            ${iconHtml}
            <span class="text-slate-100 font-medium leading-snug break-words">${message}</span>
            ${actionHtml}
            <button type="button" class="mono-toast-close text-slate-400 hover:text-white p-0.5 -mr-1 transition-colors shrink-0 cursor-pointer ml-1 text-base leading-none" aria-label="Dismiss">&times;</button>
        `;

        container.appendChild(toastEl);

        // Bind custom action click if specified
        if (action && typeof action.onClick === 'function') {
            const actionBtn = toastEl.querySelector('.mono-toast-action');
            if (actionBtn) {
                actionBtn.addEventListener('click', (e) => {
                    action.onClick(e);
                    this.dismiss(id);
                });
            }
        }

        // Close button
        toastEl.querySelector('.mono-toast-close')?.addEventListener('click', () => {
            this.dismiss(id);
        });

        // Entrance
        requestAnimationFrame(() => {
            toastEl.classList.remove('translate-y-2', 'opacity-0');
            toastEl.classList.add('translate-y-0', 'opacity-100');
        });

        // Auto dismiss timer
        const timer = setTimeout(() => {
            this.dismiss(id);
        }, duration);

        this.toasts.set(id, {
            el: toastEl,
            timer,
        });

        return id;
    }

    dismiss(id) {
        const item = this.toasts.get(id);
        if (!item) return;

        const { el, timer } = item;
        clearTimeout(timer);

        el.classList.add('opacity-0', 'translate-y-1');
        el.style.pointerEvents = 'none';

        setTimeout(() => {
            if (el && el.parentNode) {
                el.parentNode.removeChild(el);
            }
            this.toasts.delete(id);
        }, 180);
    }

    success(message, options = {}) {
        return this.show({ ...options, type: 'success', message });
    }

    error(message, options = {}) {
        return this.show({ ...options, type: 'error', message });
    }

    info(message, options = {}) {
        return this.show({ ...options, type: 'info', message });
    }

    warning(message, options = {}) {
        return this.show({ ...options, type: 'warning', message });
    }
}

// Global singleton instance
window.monoToast = new MonoToastManager();

// Backwards-compatible shorthand
window.showToast = function (message, type = 'success', options = {}) {
    return window.monoToast.show({ message, type, ...options });
};

// Auto-show flash messages if present
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-flash-toast]').forEach((el) => {
        const type = el.getAttribute('data-type') || 'success';
        const message = el.getAttribute('data-message') || el.textContent.trim();
        if (message) {
            window.monoToast.show({ type, message });
            el.remove();
        }
    });
});

export default window.monoToast;
