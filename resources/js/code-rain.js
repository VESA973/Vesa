/**
 * Sober "digital rain" background in V-ESA's colours: sparse columns of code characters
 * falling slowly on the light background, logo green on the left fading to blue on the right.
 * Columns behind the reading area are dimmer so text stays easy to read.
 * Scrolling briefly speeds the rain up. Static frame when motion is reduced.
 */

const CHARACTERS = '01<>/{}[]=;:+*ABCDEF0123456789';
const FONT_SIZE = 14;
const COLUMN_GAP = 26;
const ACTIVE_SHARE = 0.4;
const MAX_ALPHA = 0.32;

const GREEN = [63, 191, 106];
const BLUE = [45, 111, 210];

const randomCharacter = () => CHARACTERS[Math.floor(Math.random() * CHARACTERS.length)];

export function startCodeRain(canvas, { reducedMotion = false } = {}) {
    const context = canvas.getContext('2d');
    let width = 0;
    let height = 0;
    let columns = [];
    let lastTime = 0;
    let lastScroll = window.scrollY;
    let boost = 0;

    const createDrop = (x, anywhere) => {
        const length = 6 + Math.floor(Math.random() * 16);
        return {
            x,
            y: anywhere ? Math.random() * height : -Math.random() * height * 0.6,
            speed: 28 + Math.random() * 46,
            length,
            characters: Array.from({ length }, randomCharacter),
        };
    };

    const resize = () => {
        const ratio = Math.min(window.devicePixelRatio || 1, 2);
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width * ratio;
        canvas.height = height * ratio;
        context.setTransform(ratio, 0, 0, ratio, 0, 0);
        context.font = `${FONT_SIZE}px 'Geist Mono', ui-monospace, SFMono-Regular, Menlo, monospace`;
        context.textAlign = 'center';

        columns = [];
        for (let x = COLUMN_GAP / 2; x < width; x += COLUMN_GAP) {
            if (Math.random() < ACTIVE_SHARE) {
                columns.push(createDrop(x, true));
            }
        }
    };

    const colourAt = (x) => {
        const mix = x / width;
        return GREEN.map((channel, index) => Math.round(channel + (BLUE[index] - channel) * mix)).join(', ');
    };

    // Dimmer in the middle of the screen, where the content sits.
    const readabilityAt = (x) => {
        const distanceFromCentre = Math.abs(x / width - 0.5) * 2;
        return 0.35 + 0.65 * Math.min(1, distanceFromCentre * 1.4);
    };

    const draw = (now) => {
        const delta = lastTime ? Math.min((now - lastTime) / 1000, 0.1) : 0;
        lastTime = now;

        const scrolled = Math.abs(window.scrollY - lastScroll);
        lastScroll = window.scrollY;
        boost = Math.min(4, boost * 0.92 + scrolled * 0.02);

        context.clearRect(0, 0, width, height);

        columns.forEach((drop, index) => {
            drop.y += drop.speed * (1 + boost) * delta;

            if (Math.random() < 0.03) {
                drop.characters[Math.floor(Math.random() * drop.length)] = randomCharacter();
            }

            const colour = colourAt(drop.x);
            const readability = readabilityAt(drop.x);

            for (let position = 0; position < drop.length; position += 1) {
                const y = drop.y - position * FONT_SIZE * 1.2;
                if (y < -FONT_SIZE || y > height + FONT_SIZE) {
                    continue;
                }
                const isHead = position === 0;
                const fade = 1 - position / drop.length;
                context.fillStyle = `rgba(${colour}, ${(isHead ? MAX_ALPHA * 1.6 : MAX_ALPHA * fade) * readability})`;
                context.fillText(drop.characters[position], drop.x, y);
            }

            if (drop.y - drop.length * FONT_SIZE * 1.2 > height) {
                columns[index] = createDrop(drop.x, false);
            }
        });

        if (!reducedMotion) {
            requestAnimationFrame(draw);
        }
    };

    resize();
    window.addEventListener('resize', () => {
        resize();
        if (reducedMotion) {
            requestAnimationFrame(draw);
        }
    });
    requestAnimationFrame(draw);
}
