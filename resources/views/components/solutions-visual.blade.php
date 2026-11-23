{{-- Solutions hero visual: the V-ESA laptop on a blueprint grid, with a wireframe window and deploy chips around it. --}}
<div {{ $attributes->class('relative mx-auto aspect-square w-full max-w-[34rem]') }}>
    {{-- Blueprint grid fading out at the edges --}}
    <div aria-hidden="true" class="absolute inset-0 [mask-image:radial-gradient(circle,black_35%,transparent_70%)] bg-[linear-gradient(rgb(45_111_210/0.14)_1px,transparent_1px),linear-gradient(90deg,rgb(45_111_210/0.14)_1px,transparent_1px)] bg-[size:32px_32px]"></div>
    <div aria-hidden="true" class="absolute inset-[12%] rounded-full bg-[radial-gradient(circle,rgb(45_111_210/0.3),rgb(63_191_106/0.15)_50%,transparent_72%)] blur-2xl"></div>

    <img src="{{ asset('images/solutions-cutout.webp') }}?v={{ filemtime(public_path('images/solutions-cutout.webp')) }}" alt="Un ordinateur portable affichant du code aux couleurs de V-ESA" width="742" height="824" class="absolute top-1/2 left-1/2 w-[62%] -translate-x-1/2 -translate-y-1/2 -rotate-6 drop-shadow-2xl">

    {{-- Wireframe browser window --}}
    <div data-reveal="0.6" class="absolute top-[6%] left-0 w-[42%] md:-left-6">
        <div class="float-slow notch-flip bg-white/85 p-3 shadow-xl ring-1 ring-neutral-900/5 backdrop-blur-xl">
            <div class="flex gap-1.5"><span class="size-2 rounded-full bg-neutral-300"></span><span class="size-2 rounded-full bg-neutral-300"></span><span class="size-2 rounded-full bg-neutral-300"></span></div>
            <div class="accent-solutions accent-bg mt-3 h-10 opacity-80"></div>
            <div class="mt-2 grid grid-cols-3 gap-1.5"><span class="h-6 bg-neutral-900/10"></span><span class="h-6 bg-neutral-900/10"></span><span class="h-6 bg-neutral-900/10"></span></div>
            <div class="mt-2 h-1.5 w-2/3 rounded-full bg-neutral-900/10"></div>
        </div>
    </div>

    <p data-reveal="0.8" class="absolute top-[12%] right-0 md:-right-4">
        <span class="float-slow flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 font-mono text-[0.7rem] tracking-[0.14em] text-ink uppercase shadow-lg ring-1 ring-neutral-900/5 backdrop-blur-xl [animation-delay:-2s]">
            <span class="size-2 animate-pulse rounded-full bg-[#3fbf6a]"></span> Site en ligne
        </span>
    </p>
    <p data-reveal="1" class="absolute right-[4%] bottom-[8%]">
        <span class="float-slow block rounded-full bg-ink px-4 py-2 font-mono text-xs text-white shadow-lg [animation-delay:-4s]">$ deploy --prod <span class="text-[#8fd94e]">✓</span></span>
    </p>
</div>
