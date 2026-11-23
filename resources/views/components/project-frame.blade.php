@props(['project', 'number' => 1, 'reverse' => false])

@php
    $address = $project->url ? parse_url($project->url, PHP_URL_HOST) : null;
@endphp

{{-- One project as an alternating row: the site in a browser window on one side, the story on the other. --}}
<article data-category="{{ $project->category }}" {{ $attributes }}>
    <div class="grid items-center gap-10 md:grid-cols-12 md:gap-14">
        <a href="{{ route('projects.show', $project) }}" data-tilt @class(['group/frame relative block md:col-span-7', 'md:order-2' => $reverse]) aria-label="Voir le projet {{ $project->title }}">
            <div aria-hidden="true" class="absolute -inset-8 rounded-full bg-[radial-gradient(circle,rgb(45_111_210/0.22),rgb(63_191_106/0.12)_45%,transparent_70%)] blur-2xl transition-transform duration-500 group-hover/frame:scale-110"></div>
            <x-browser-frame data-tilt-target class="relative transition-transform duration-300 ease-out" :image="$project->cover_image ? asset('storage/'.$project->cover_image) : null" :alt="$project->title" :address="$address ?? $project->title" />
        </a>

        <div class="md:col-span-5">
            <p class="font-mono text-xs tracking-[0.16em] text-neutral-400 uppercase">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }} / <span data-scramble class="text-ink">{{ $project->categoryLabel() }}</span></p>

            @if ($project->client_logo)
                <img src="{{ asset('storage/'.$project->client_logo) }}" alt="{{ $project->client }}" class="mt-8 h-10 w-auto object-contain">
            @elseif ($project->client)
                <p class="mt-8 text-sm font-semibold tracking-wide text-neutral-500 uppercase">{{ $project->client }}</p>
            @endif

            <h3 class="mt-4 text-3xl font-bold">{{ $project->title }}</h3>
            <p class="mt-4 text-lg leading-relaxed text-neutral-600">{{ $project->excerpt }}</p>

            @if ($project->technologies)
                <ul class="mt-6 flex flex-wrap gap-2" aria-label="Technologies">
                    @foreach ($project->technologies as $technology)
                        <li class="rounded-full border border-neutral-900/15 px-3 py-1 font-mono text-[0.7rem] text-neutral-600">{{ $technology }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">
                <a href="{{ route('projects.show', $project) }}" class="group inline-flex items-center gap-2 rounded-full bg-ink px-6 py-3 text-sm font-medium text-white transition hover:bg-ink-soft">Voir le projet <span aria-hidden="true" class="transition-transform group-hover:translate-x-1">&rarr;</span></a>
                @if ($project->url)
                    <a href="{{ $project->url }}" target="_blank" rel="noopener" class="text-sm font-medium text-ink underline-offset-4 hover:underline">Visiter le site &nearr;</a>
                @endif
            </div>
        </div>
    </div>
</article>
