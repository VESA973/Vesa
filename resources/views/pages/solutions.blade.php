@php
    $offers = [
        ['Sites vitrines & e-commerce', 'Un site rapide, moderne et bien référencé, que vous modifiez vous-même.'],
        ['Applications web sur mesure', 'Gestion de clients, plannings, stocks, devis : un outil qui colle à votre façon de travailler.'],
        ['Automatisation des tâches', 'Connectez vos outils et supprimez les saisies répétitives.'],
        ['Conseil & audit', 'Un regard extérieur sur votre informatique et un plan d’action concret.'],
    ];
@endphp

<x-layouts.site title="Solutions entreprises" description="Sites, applications web, automatisation et conseil : des solutions numériques modernes pour les entreprises.">
    <section class="mx-auto grid max-w-6xl items-center gap-12 px-6 pt-16 pb-24 md:min-h-[calc(100svh-5rem)] md:grid-cols-2 md:pt-0">
        <div>
            <x-boot-line>Système V-ESA — analyse du besoin</x-boot-line>
            <x-statement as="h1" accent="solutions" lead="Solutions pour entreprises" word="Sur-mesure" />
            <p data-type="4" data-type-speed="18" class="mt-10 max-w-xl text-2xl leading-relaxed text-neutral-600">Nous créons des outils numériques modernes, adaptés à votre métier et à votre budget.</p>
        </div>
        <x-solutions-visual data-reveal="0.3" />
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-32">
        <ul>
            @foreach ($offers as [$offerTitle, $offerText])
                <li data-reveal class="grid gap-4 border-t border-neutral-900/10 py-10 md:grid-cols-12">
                    <span class="font-mono text-sm tracking-[0.16em] text-neutral-400 md:col-span-1 md:pt-2">0{{ $loop->iteration }}</span>
                    <h2 class="text-2xl font-semibold md:col-span-5">{{ $offerTitle }}</h2>
                    <p class="text-lg text-neutral-500 md:col-span-6">{{ $offerText }}</p>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-32 text-center">
        <x-statement accent="solutions" lead="Un projet en tête ?" word="Parlons-en." class="[&>span]:mx-auto" />
        <a data-reveal href="{{ route('contact', ['service' => 'solutions']) }}" class="mt-12 inline-block rounded-full bg-ink px-8 py-4 font-medium text-white transition hover:bg-ink-soft">Demander un devis</a>
    </section>
</x-layouts.site>
