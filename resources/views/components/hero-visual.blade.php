{{-- Home hero visual: the cut-out team member inside a soft glow and slowly turning HUD rings,
     her base fading into the page so there is no hard cut. --}}
<div {{ $attributes->class('relative mx-auto aspect-[1195/1312] w-full max-w-[32rem]') }}>
    {{-- Glow in the logo colours, strongest behind the holographic tablet --}}
    <div aria-hidden="true" class="absolute inset-[8%] rounded-full bg-[radial-gradient(circle_at_40%_55%,rgb(45_111_210/0.35),rgb(63_191_106/0.18)_45%,transparent_70%)] blur-2xl"></div>

    {{-- HUD rings centred on her --}}
    <svg aria-hidden="true" viewBox="0 0 400 400" class="absolute top-[6%] left-1/2 w-[105%] -translate-x-1/2">
        <defs>
            <linearGradient id="hud-gradient" x1="0" x2="1" y1="0" y2="1">
                <stop offset="0" stop-color="#3fbf6a" />
                <stop offset="1" stop-color="#2d6fd2" />
            </linearGradient>
        </defs>
        <g fill="none" stroke="url(#hud-gradient)">
            <circle cx="200" cy="200" r="190" stroke-width="1" stroke-opacity="0.35" />
            <g class="hud-spin">
                <circle cx="200" cy="200" r="172" stroke-width="2" stroke-dasharray="2 10" stroke-opacity="0.7" />
                <path d="M200 18 A182 182 0 0 1 382 200" stroke-width="3" stroke-linecap="round" />
            </g>
            <g class="hud-spin-reverse">
                <path d="M60 300 A150 150 0 0 1 120 70" stroke-width="1.5" stroke-opacity="0.6" />
                <circle cx="200" cy="200" r="150" stroke-width="1" stroke-dasharray="40 14" stroke-opacity="0.3" />
            </g>
        </g>
    </svg>

    {{-- Floor line she stands above --}}
    <div aria-hidden="true" class="absolute right-[5%] bottom-[6%] left-[5%] h-px bg-gradient-to-r from-transparent via-[#2d6fd2]/50 to-transparent"></div>

    <img src="{{ asset('images/hero-cutout.webp') }}?v={{ filemtime(public_path('images/hero-cutout.webp')) }}" alt="Une conseillère V-ESA consulte des données sur une tablette holographique" width="1195" height="1312" class="relative w-full [mask-image:linear-gradient(to_bottom,black_72%,transparent_96%)]">

    {{-- Two small status chips --}}
    <p data-reveal="0.8" class="absolute top-[14%] right-0 md:-right-6">
        <span class="float-slow flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 font-mono text-[0.7rem] tracking-[0.14em] text-ink uppercase shadow-lg ring-1 ring-neutral-900/5 backdrop-blur-xl">
            <span class="size-2 animate-pulse rounded-full bg-[#3fbf6a]"></span> Système en ligne
        </span>
    </p>
    <p data-reveal="1" class="absolute bottom-[16%] left-0 md:-left-8">
        <span class="float-slow block rounded-full bg-ink px-4 py-2 font-mono text-xs text-white shadow-lg [animation-delay:-3s]">&lt;solution <span class="text-[#8fd94e]">sur-mesure</span> /&gt;</span>
    </p>
</div>
