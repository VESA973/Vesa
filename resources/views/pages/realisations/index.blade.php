@php
    use App\Models\Project;

    $clientLogos = $allProjects->whereNotNull('client_logo')->unique('client');
    $stats = array_filter([
        'projets réalisés' => $allProjects->count(),
        'clients accompagnés' => $allProjects->pluck('client')->filter()->unique()->count(),
        'technologies maîtrisées' => $allProjects->pluck('technologies')->flatten()->filter()->unique()->count(),
    ]);
@endphp

<x-layouts.site title="Réalisations" description="Les sites et applications web réalisés par V-ESA pour ses clients.">
    {{-- Header --}}
    <section class="mx-auto max-w-6xl px-6 pt-24 md:pt-36">
        <x-eyebrow data-reveal>Réalisations</x-eyebrow>
        <div class="mt-6 grid gap-8 md:grid-cols-12">
            <h1 class="text-3xl font-bold md:col-span-6 md:text-5xl"><span data-type="2" data-type-speed="60">Découvrez nos</span> <span data-type="3" data-type-speed="80" class="accent-solutions accent-text font-script">réussites.</span></h1>
            <p data-reveal="0.1" class="text-lg leading-relaxed text-neutral-600 md:col-span-6 md:pt-3">Des <strong class="font-semibold text-neutral-900">sites et applications web</strong> conçus pour <strong class="font-semibold text-neutral-900">de vrais clients</strong> : sites vitrines, boutiques en ligne, outils sur mesure.</p>
        </div>

        @if ($stats)
            <dl class="mt-16 grid grid-cols-2 border-t border-neutral-200 md:grid-cols-3">
                @foreach ($stats as $label => $value)
                    <div data-reveal="{{ $loop->index * 0.1 }}" class="border-b border-neutral-200 py-8 md:border-b-0">
                        <dd data-count="{{ $value }}" data-pad="3" class="font-mono text-5xl font-medium tracking-tight">{{ str_pad($value, 3, '0', STR_PAD_LEFT) }}</dd>
                        <dt class="mt-2 font-mono text-xs tracking-[0.16em] text-neutral-500 uppercase">{{ $label }}</dt>
                    </div>
                @endforeach
            </dl>
        @endif
    </section>

    {{-- Client logos: centred strip in the page column, edges fading out, seamless loop --}}
    @if ($clientLogos->count() >= 3)
        <section class="mx-auto mt-16 max-w-6xl px-6" aria-label="Ils nous font confiance">
            <x-eyebrow class="text-center">Ils nous font confiance</x-eyebrow>
            <div class="mt-8 overflow-hidden border-y border-neutral-900/10 py-10 [mask-image:linear-gradient(to_right,transparent,black_15%,black_85%,transparent)]">
                <div class="flex w-max animate-[marquee_40s_linear_infinite] items-center motion-reduce:mx-auto motion-reduce:animate-none">
                    @foreach ([1, 2, 3, 4] as $copy)
                        @foreach ($clientLogos as $project)
                            {{-- Spacing as padding (not gap) so both halves are identical and the loop has no jump --}}
                            <span class="shrink-0 px-10">
                                <img src="{{ asset('storage/'.$project->client_logo) }}" alt="{{ $copy === 1 ? $project->client : '' }}" @if ($copy > 1) aria-hidden="true" @endif class="h-10 w-auto object-contain opacity-60 grayscale transition hover:opacity-100 hover:grayscale-0">
                            </span>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Filters + projects --}}
    <section class="mx-auto max-w-6xl px-6 py-24">
        <div class="border-t border-neutral-200 pt-8">
            <x-eyebrow class="mb-4">Filtrer par type de site</x-eyebrow>
            <nav id="projets" class="flex scroll-mt-28 flex-wrap gap-2" aria-label="Filtrer par type de site">
                @foreach (['' => 'Tout'] + Project::CATEGORIES as $value => $label)
                    <a href="{{ route('projects.index', array_filter(['categorie' => $value])) }}#projets" @class([
                        'border px-3.5 py-1.5 text-sm font-semibold transition',
                        'border-ink bg-ink text-white' => ($category ?? '') === $value,
                        'border-neutral-300 hover:border-ink' => ($category ?? '') !== $value,
                    ]) @if (($category ?? '') === $value) aria-current="true" @endif>{{ $label }}</a>
                @endforeach
            </nav>
        </div>

        @if ($allProjects->isEmpty())
            <p class="py-24 text-center text-xl text-neutral-400">Nos réalisations arrivent très bientôt.</p>
        @elseif ($projects->isEmpty())
            <p class="py-24 text-center text-xl text-neutral-400">Aucun projet de ce type pour le moment.</p>
        @else
            <div class="mt-20 space-y-28 md:space-y-36">
                @foreach ($projects as $project)
                    <x-project-frame data-reveal :project="$project" :number="$projects->firstItem() + $loop->index" :reverse="$loop->even" />
                @endforeach
            </div>

            @if ($projects->hasPages())
                {{-- Page navigation, 4 projects per page --}}
                <nav class="mt-28 flex items-center justify-between gap-4 border-t border-neutral-900/10 pt-8 font-mono text-sm" aria-label="Pages des réalisations">
                    @if ($projects->onFirstPage())
                        <span class="text-neutral-300">&larr; Précédent</span>
                    @else
                        <a href="{{ $projects->previousPageUrl() }}#projets" rel="prev" class="text-ink transition hover:-translate-x-1">&larr; Précédent</a>
                    @endif

                    <ul class="flex gap-2">
                        @foreach ($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
                            <li>
                                <a href="{{ $url }}#projets" @class([
                                    'grid size-10 place-items-center rounded-full transition',
                                    'bg-ink text-white' => $page === $projects->currentPage(),
                                    'text-neutral-500 ring-1 ring-neutral-900/15 hover:ring-ink' => $page !== $projects->currentPage(),
                                ]) @if ($page === $projects->currentPage()) aria-current="page" @endif>{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                            </li>
                        @endforeach
                    </ul>

                    @if ($projects->hasMorePages())
                        <a href="{{ $projects->nextPageUrl() }}#projets" rel="next" class="text-ink transition hover:translate-x-1">Suivant &rarr;</a>
                    @else
                        <span class="text-neutral-300">Suivant &rarr;</span>
                    @endif
                </nav>
            @endif
        @endif
    </section>
</x-layouts.site>
