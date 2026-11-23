{{-- Repair hero visual: the controller on a diagnostic target, with a scan line sweeping over it. --}}
<div {{ $attributes->class('relative mx-auto aspect-square w-full max-w-[34rem]') }}>
    <div aria-hidden="true" class="absolute inset-[14%] rounded-full bg-[radial-gradient(circle,rgb(143_217_78/0.3),rgb(42_157_143/0.15)_50%,transparent_72%)] blur-2xl"></div>

    {{-- Diagnostic target: rings, crosshair and ticks --}}
    <svg aria-hidden="true" viewBox="0 0 400 400" class="absolute inset-0 size-full">
        <defs>
            <linearGradient id="repair-gradient" x1="0" x2="1" y1="0" y2="1">
                <stop offset="0" stop-color="#8fd94e" />
                <stop offset="1" stop-color="#2a9d8f" />
            </linearGradient>
        </defs>
        <g fill="none" stroke="url(#repair-gradient)">
            <circle cx="200" cy="200" r="185" stroke-opacity="0.3" />
            <g class="hud-spin">
                <circle cx="200" cy="200" r="165" stroke-width="2" stroke-dasharray="1 9" stroke-opacity="0.8" />
            </g>
            <circle cx="200" cy="200" r="120" stroke-opacity="0.25" stroke-dasharray="30 10" />
            <path d="M200 0 V60 M200 340 V400 M0 200 H60 M340 200 H400" stroke-width="1.5" stroke-opacity="0.6" />
        </g>
    </svg>

    {{-- Viewfinder brackets around the controller --}}
    <div aria-hidden="true" class="absolute inset-x-[8%] inset-y-[30%]">
        <span class="corner-mark top-0 left-0 border-t-2 border-l-2"></span>
        <span class="corner-mark top-0 right-0 border-t-2 border-r-2"></span>
        <span class="corner-mark bottom-0 left-0 border-b-2 border-l-2"></span>
        <span class="corner-mark right-0 bottom-0 border-r-2 border-b-2"></span>
    </div>

    <img src="{{ asset('images/reparation-cutout.webp') }}?v={{ filemtime(public_path('images/reparation-cutout.webp')) }}" alt="Une manette de console en cours de diagnostic" width="880" height="346" class="absolute top-1/2 left-1/2 w-[80%] -translate-x-1/2 -translate-y-1/2 drop-shadow-2xl">

    {{-- Scan line sweeping over the controller --}}
    <div aria-hidden="true" class="scan-line absolute inset-x-[6%] top-[32%] h-0.5 bg-gradient-to-r from-transparent via-[#8fd94e] to-transparent shadow-[0_0_18px_4px_rgb(143_217_78/0.5)]"></div>

    <div data-reveal="0.6" class="absolute top-[6%] left-0 md:-left-4">
        <div class="float-slow notch-flip w-48 bg-white/85 p-4 shadow-xl ring-1 ring-neutral-900/5 backdrop-blur-xl">
            <p class="font-mono text-[0.65rem] tracking-[0.16em] text-neutral-400 uppercase">Diagnostic</p>
            <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-neutral-900/10"><div class="accent-reparation accent-bg h-full w-full"></div></div>
            <p class="mt-2 text-right font-mono text-[0.65rem] text-neutral-500">100 %</p>
        </div>
    </div>
    <p data-reveal="0.9" class="absolute right-0 bottom-[10%] md:-right-4">
        <span class="float-slow flex items-center gap-2 rounded-full bg-ink px-4 py-2 font-mono text-xs text-white shadow-lg [animation-delay:-3s]">Pièce remplacée <span class="text-[#8fd94e]">✓</span></span>
    </p>
</div>
