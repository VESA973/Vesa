import Lenis from 'lenis';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { startCodeRain } from './code-rain';
import { startCookieConsent } from './cookie-consent';
import { startLoader } from './loader';

gsap.registerPlugin(ScrollTrigger);

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Page animations wait for the arrival loader so they play in front of the visitor, not behind it.
const loaderDone = startLoader({ reducedMotion: prefersReducedMotion });
gsap.globalTimeline.pause();
loaderDone.then(() => gsap.globalTimeline.resume());

// Terminal typing: in each section, [data-type="n"] elements are typed in order when the section
// scrolls into view; each glyph flickers before it locks in. One blinking cursor follows the latest line.
const typedElements = [...document.querySelectorAll('[data-type]')];
if (typedElements.length) {
    const glyphs = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#%&@$<>/';
    const texts = new Map(typedElements.map((element) => [element, element.textContent.trim()]));
    const reveal = (element) => {
        element.style.animation = 'none';
        element.style.visibility = 'visible';
    };

    if (prefersReducedMotion) {
        typedElements.forEach(reveal);
    } else {
        let cursorHolder = null;

        typedElements.forEach((element) => {
            element.setAttribute('aria-label', texts.get(element));
            // Keep the final height so the page does not jump while the text is typed.
            element.style.minHeight = `${element.offsetHeight}px`;
            element.textContent = '';
        });

        const typeElement = (element) =>
            new Promise((done) => {
                const text = texts.get(element);
                const delay = Number(element.dataset.typeSpeed || 35);
                let position = 0;
                reveal(element);
                cursorHolder?.classList.remove('is-typing');
                cursorHolder = element;
                element.classList.add('is-typing');

                const step = () => {
                    position += 1;
                    const glitch = position <= text.length && text[position - 1] !== ' ' ? glyphs[Math.floor(Math.random() * glyphs.length)] : '';
                    element.textContent = text.slice(0, position - 1) + glitch;
                    if (position <= text.length) {
                        setTimeout(step, delay);
                        return;
                    }
                    element.textContent = text;
                    setTimeout(done, 250);
                };
                step();
            });

        const groups = new Map();
        typedElements.forEach((element) => {
            const group = element.closest('section') || document.body;
            groups.set(group, [...(groups.get(group) || []), element]);
        });

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(async (entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    observer.unobserve(entry.target);
                    const ordered = groups.get(entry.target).sort((first, second) => Number(first.dataset.type) - Number(second.dataset.type));
                    for (const element of ordered) {
                        await typeElement(element);
                    }
                });
            },
            { rootMargin: '0px 0px -20% 0px' },
        );
        loaderDone.then(() => groups.forEach((elements, group) => observer.observe(group)));
    }
}

startCookieConsent();

const codeRainCanvas = document.querySelector('[data-code-rain]');
if (codeRainCanvas) {
    startCodeRain(codeRainCanvas, { reducedMotion: prefersReducedMotion });
}

if (!prefersReducedMotion) {
    const lenis = new Lenis({ autoRaf: false });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);

    gsap.utils.toArray('[data-reveal]').forEach((element) => {
        gsap.from(element, {
            y: 32,
            opacity: 0,
            duration: 1,
            ease: 'power3.out',
            delay: Number(element.dataset.reveal || 0),
            scrollTrigger: { trigger: element, start: 'top 90%' },
        });
    });

    // Apple-style: image grows from a small card to full size while it scrolls in.
    gsap.utils.toArray('[data-zoom]').forEach((element) => {
        gsap.fromTo(
            element,
            { scale: 0.78 },
            { scale: 1, ease: 'none', scrollTrigger: { trigger: element, start: 'top bottom', end: 'center center', scrub: true } },
        );
    });

    // Apple-style: on desktop the section stays pinned while each step lights up in turn.
    gsap.matchMedia().add('(min-width: 768px)', () => {
        gsap.utils.toArray('[data-steps]').forEach((section) => {
            const steps = section.querySelectorAll('li');
            gsap.set(steps, { opacity: 0.15 });
            gsap.timeline({
                scrollTrigger: { trigger: section, start: 'center center', end: `+=${steps.length * 400}`, pin: true, scrub: true },
            }).to(steps, { opacity: 1, stagger: 1, duration: 1 });
        });
    });

    // Key figures count up from zero when they come into view.
    gsap.utils.toArray('[data-count]').forEach((element) => {
        const counter = { value: 0 };
        gsap.to(counter, {
            value: Number(element.dataset.count),
            duration: 1.6,
            ease: 'power2.out',
            scrollTrigger: { trigger: element, start: 'top 90%' },
            onUpdate: () => {
                element.textContent = String(Math.round(counter.value)).padStart(Number(element.dataset.pad || 0), '0');
            },
        });
    });

    // Terminal-style labels: text decodes from random glyphs when it scrolls into view.
    const glyphs = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789<>/[]{}#';
    document.querySelectorAll('[data-scramble]').forEach((element) => {
        const finalText = element.textContent;
        ScrollTrigger.create({
            trigger: element,
            start: 'top 92%',
            once: true,
            onEnter: () => {
                let frame = 0;
                const tick = () => {
                    frame += 1;
                    element.textContent = [...finalText]
                        .map((char, index) => (char === ' ' || frame > index * 2 + 8 ? char : glyphs[Math.floor(Math.random() * glyphs.length)]))
                        .join('');
                    if (frame < finalText.length * 2 + 8) {
                        requestAnimationFrame(tick);
                    } else {
                        element.textContent = finalText;
                    }
                };
                tick();
            },
        });
    });

    // Images appear through a grid of dark-green blocks that switch off in random order.
    document.querySelectorAll('[data-pixelate]').forEach((frame) => {
        const overlay = document.createElement('div');
        overlay.ariaHidden = 'true';
        overlay.style.cssText = 'position:absolute;inset:0;z-index:5;display:grid;grid-template-columns:repeat(12,1fr);grid-template-rows:repeat(8,1fr);pointer-events:none';
        const cells = Array.from({ length: 96 }, () => {
            const cell = document.createElement('span');
            cell.style.background = Math.random() < 0.18 ? '#3fbf6a' : '#173a34';
            return cell;
        });
        overlay.append(...cells);
        frame.append(overlay);
        ScrollTrigger.create({
            trigger: frame,
            start: 'top 85%',
            once: true,
            onEnter: () =>
                gsap.to(cells, {
                    opacity: 0,
                    duration: 0.12,
                    stagger: { each: 0.007, from: 'random' },
                    onComplete: () => overlay.remove(),
                }),
        });
    });

    // Project windows tilt slightly towards the mouse, like a screen you can almost touch.
    if (window.matchMedia('(pointer: fine)').matches) {
        document.querySelectorAll('[data-tilt]').forEach((area) => {
            const target = area.querySelector('[data-tilt-target]');
            area.addEventListener('pointermove', (event) => {
                const bounds = area.getBoundingClientRect();
                const x = (event.clientX - bounds.left) / bounds.width - 0.5;
                const y = (event.clientY - bounds.top) / bounds.height - 0.5;
                target.style.transform = `perspective(1200px) rotateX(${(-y * 6).toFixed(2)}deg) rotateY(${(x * 8).toFixed(2)}deg) translateY(-6px)`;
            });
            area.addEventListener('pointerleave', () => {
                target.style.transform = '';
            });
        });
    }
}
