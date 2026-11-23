/**
 * Cookie banner: shown until the visitor accepts or refuses; the choice is kept 13 months
 * (CNIL guidance) in localStorage. Any future audience tool must check `window.vesaConsent()`
 * or listen for the `vesa:consent` event, and stay off unless it returns 'accepted'.
 */
const STORAGE_KEY = 'vesa-cookie-consent';
const MAX_AGE = 13 * 30 * 24 * 60 * 60 * 1000;

const readChoice = () => {
    try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY));
        return saved && Date.now() - saved.date < MAX_AGE ? saved.choice : null;
    } catch {
        return null;
    }
};

export function startCookieConsent() {
    const banner = document.querySelector('[data-cookie-banner]');
    if (!banner) {
        return;
    }

    window.vesaConsent = readChoice;

    const show = () => {
        banner.hidden = false;
        banner.querySelector('[data-consent]')?.focus({ preventScroll: true });
    };

    banner.addEventListener('click', (event) => {
        const button = event.target.closest('[data-consent]');
        if (!button) {
            return;
        }
        const choice = button.dataset.consent;
        localStorage.setItem(STORAGE_KEY, JSON.stringify({ choice, date: Date.now() }));
        banner.hidden = true;
        window.dispatchEvent(new CustomEvent('vesa:consent', { detail: choice }));
    });

    document.querySelectorAll('[data-cookie-settings]').forEach((button) => button.addEventListener('click', show));

    if (!readChoice()) {
        show();
    }
}
