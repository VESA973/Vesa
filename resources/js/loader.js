/**
 * Arrival loader: counts up to 100 % while the page loads (at least ~1.4 s so the animation reads,
 * at most 4 s so a slow image never holds visitors back), then wipes upwards.
 * Plays once per visit (sessionStorage). Resolves when the wipe starts so page animations can begin.
 */
const MIN_DURATION = 1400;
const MAX_DURATION = 4000;

export function startLoader({ reducedMotion = false } = {}) {
    const loader = document.querySelector('[data-site-loader]');
    if (!loader || document.documentElement.classList.contains('no-loader')) {
        loader?.remove();
        return Promise.resolve();
    }

    try {
        sessionStorage.setItem('vesa-loaded', '1');
    } catch {
        // Private mode: the loader will simply play again on the next page.
    }

    const counter = loader.querySelector('[data-loader-count]');
    const bar = loader.querySelector('[data-loader-bar]');
    const minDuration = reducedMotion ? 300 : MIN_DURATION;
    let pageLoaded = document.readyState === 'complete';
    window.addEventListener('load', () => (pageLoaded = true), { once: true });

    return new Promise((resolve) => {
        const start = performance.now();
        let progress = 0;

        const finish = () => {
            counter.textContent = '100 %';
            bar.style.width = '100%';
            setTimeout(() => {
                loader.classList.add('is-done');
                resolve();
                setTimeout(() => loader.remove(), 900);
            }, 200);
        };

        const tick = (now) => {
            const elapsed = now - start;
            // Creep towards 90 % while loading, then run to 100 % once the page is ready.
            const target = pageLoaded && elapsed >= minDuration ? 1 : Math.min(0.9, elapsed / (minDuration * 1.1));
            progress += (target - progress) * 0.12;

            const percent = Math.min(100, Math.round(progress * 100));
            counter.textContent = `${String(percent).padStart(3, '0')} %`;
            bar.style.width = `${percent}%`;

            if ((pageLoaded && elapsed >= minDuration && progress > 0.985) || elapsed > MAX_DURATION) {
                finish();
                return;
            }
            requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    });
}
