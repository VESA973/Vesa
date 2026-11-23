@php
    $activities = [
        ['solutions', 'solutions', 'Solutions', 'Sites, applications et outils sur mesure pour votre entreprise.'],
        ['reparation', 'reparation', 'Réparation', 'Diagnostic clair et réparation de vos appareils électroniques.'],
        ['projects.index', 'conseil', 'Réalisations', 'Les sites que nous avons conçus pour nos clients.'],
    ];
@endphp

<x-layouts.site>
    {{-- Hero: text on the left, visual on the right --}}
    <section class="mx-auto grid max-w-6xl items-center gap-12 px-6 pt-16 pb-20 md:min-h-[calc(100svh-5rem)] md:grid-cols-2 md:pt-0">
        <div>
        <x-boot-line>V-ESA OS — Solutions numériques &amp; réparation électronique</x-boot-line>
        <h1 class="text-4xl font-bold sm:text-6xl md:text-7xl">
            <span data-type="2" data-type-speed="70" class="block">Moderne.</span>
            <span data-type="3" data-type-speed="70" class="block">Simple.</span>
            <span data-type="4" data-type-speed="90" class="accent-solutions accent-text block pb-2 font-script font-bold">Pour vous.</span>
        </h1>
        <a data-reveal="1.2" href="{{ route('contact') }}" class="mt-10 inline-block rounded-full bg-ink px-7 py-3.5 font-medium text-white transition hover:bg-ink-soft">Parlez-nous de votre projet</a>
        </div>

        <x-hero-visual data-reveal="0.3" />
    </section>

    {{-- The three ways in --}}
    <section class="mx-auto grid max-w-6xl gap-px border-y border-neutral-900/10 bg-neutral-900/10 md:grid-cols-3">
        @foreach ($activities as [$route, $accent, $label, $text])
            <a data-reveal="{{ $loop->index * 0.1 }}" href="{{ route($route) }}" class="group bg-white p-8 transition hover:bg-neutral-50">
                <p class="font-mono text-xs tracking-[0.16em] text-neutral-400 uppercase">0{{ $loop->iteration }} / <span data-scramble class="text-ink">{{ $label }}</span></p>
                <p class="mt-4 text-lg text-neutral-600">{{ $text }}</p>
                <span class="accent-{{ $accent }} accent-text mt-6 inline-block font-medium transition group-hover:translate-x-1">Découvrir &rarr;</span>
            </a>
        @endforeach
    </section>
</x-layouts.site>
