@php
    $story = array_filter([
        'Le besoin' => $project->challenge,
        'La solution' => $project->solution,
        'Le résultat' => $project->result,
    ]);
@endphp

<x-layouts.site :title="$project->title" :description="$project->excerpt">
    <section class="mx-auto max-w-6xl px-6 pt-24 pb-16 md:pt-36">
        <a data-reveal href="{{ route('projects.index') }}" class="text-sm text-neutral-400 transition hover:text-neutral-900">&larr; Toutes les réalisations</a>
        <x-boot-line class="mt-10">Projet // {{ $project->slug }}</x-boot-line>
        <x-statement as="h1" accent="solutions" :lead="$project->categoryLabel()" :word="$project->title" />
        <p data-type="4" data-type-speed="18" class="mt-10 max-w-3xl text-2xl leading-relaxed text-neutral-600">{{ $project->excerpt }}</p>

        <dl data-reveal="0.3" class="mt-12 flex flex-wrap gap-x-16 gap-y-6 text-sm">
            @if ($project->client)
                <div><dt class="text-neutral-400">Client</dt><dd class="mt-1 font-medium">{{ $project->client }}</dd></div>
            @endif
            @if ($project->year)
                <div><dt class="text-neutral-400">Année</dt><dd class="mt-1 font-medium">{{ $project->year }}</dd></div>
            @endif
            @if ($project->technologies)
                <div><dt class="text-neutral-400">Technologies</dt><dd class="mt-1 font-medium">{{ implode(' · ', $project->technologies) }}</dd></div>
            @endif
            @if ($project->url)
                <div><dt class="text-neutral-400">En ligne</dt><dd class="mt-1"><a href="{{ $project->url }}" class="font-medium underline-offset-4 hover:underline" target="_blank" rel="noopener">Voir le site &nearr;</a></dd></div>
            @endif
        </dl>
    </section>

    @if ($project->cover_image)
        <div class="relative mx-auto max-w-5xl px-6 pb-32">
            <div aria-hidden="true" class="absolute inset-x-0 -inset-y-8 rounded-full bg-[radial-gradient(circle,rgb(45_111_210/0.22),rgb(63_191_106/0.12)_45%,transparent_70%)] blur-2xl"></div>
            <x-browser-frame data-zoom class="relative" :image="asset('storage/'.$project->cover_image)" :alt="$project->title" :address="$project->url ? parse_url($project->url, PHP_URL_HOST) : $project->title" />
        </div>
    @endif

    @foreach ($story as $heading => $text)
        <section class="mx-auto grid max-w-6xl gap-8 border-t border-neutral-900/10 px-6 py-20 md:grid-cols-12">
            <h2 data-type="1" data-type-speed="60" class="text-3xl font-semibold text-neutral-400 md:col-span-4">{{ $heading }}</h2>
            <p data-reveal class="text-xl leading-relaxed whitespace-pre-line text-neutral-700 md:col-span-8">{{ $text }}</p>
        </section>
    @endforeach

    @if ($project->gallery)
        <section class="mx-auto grid max-w-6xl gap-6 px-6 py-20 md:grid-cols-2">
            @foreach ($project->gallery as $image)
                <figure data-reveal data-pixelate class="relative overflow-hidden bg-white/40 ring-1 ring-neutral-900/10">
                    <img src="{{ asset('storage/'.$image) }}" alt="{{ $project->title }} — image {{ $loop->iteration }}" loading="lazy" decoding="async" class="size-full object-cover">
                </figure>
            @endforeach
        </section>
    @endif

    <section class="mx-auto max-w-6xl px-6 py-32 text-center">
        <x-statement accent="solutions" lead="Un projet similaire ?" word="Parlons-en." class="[&>span]:mx-auto" />
        <a data-reveal href="{{ route('contact', ['service' => 'solutions']) }}" class="mt-12 inline-block rounded-full bg-ink px-8 py-4 font-medium text-white transition hover:bg-ink-soft">Nous contacter</a>
        @if ($nextProject)
            <p class="mt-16"><a href="{{ route('projects.show', $nextProject) }}" class="text-neutral-400 transition hover:text-neutral-900">Projet suivant : {{ $nextProject->title }} &rarr;</a></p>
        @endif
    </section>
</x-layouts.site>
