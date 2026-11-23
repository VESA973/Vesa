@php
    use App\Models\RepairDevice;
@endphp

<x-layouts.site title="Réparation électronique" description="Réparation de pianos numériques et synthétiseurs, consoles de jeux, ordinateurs et cartes électroniques.">
    <section class="mx-auto grid max-w-6xl items-center gap-12 px-6 pt-16 pb-24 md:min-h-[calc(100svh-5rem)] md:grid-cols-2 md:pt-0">
        <div>
            <x-boot-line>Diagnostic en cours</x-boot-line>
            <x-statement as="h1" accent="reparation" lead="Réparation électronique" word="Durable" />
            <p data-type="4" data-type-speed="18" class="mt-10 max-w-xl text-2xl leading-relaxed text-neutral-600">Ne remplacez pas, réparez. Diagnostic précis pour les particuliers comme pour les professionnels.</p>
        </div>
        <x-repair-visual data-reveal="0.3" />
    </section>

    {{-- One entry per section, each with its own page --}}
    <section class="mx-auto grid max-w-6xl gap-px border-y border-neutral-900/10 bg-neutral-900/10 md:grid-cols-3">
        @foreach (RepairDevice::FAMILIES as $family => $label)
            <a data-reveal="{{ $loop->index * 0.1 }}" href="{{ route('reparation.family', $family) }}" class="group flex flex-col bg-white p-8 transition hover:bg-neutral-50">
                <p class="font-mono text-xs tracking-[0.16em] text-neutral-400 uppercase">0{{ $loop->iteration }} / <span data-scramble class="text-ink">{{ $label }}</span></p>
                <h2 class="mt-4 text-2xl font-bold">{{ $label }}</h2>
                <p class="mt-3 text-neutral-600">{{ RepairDevice::FAMILY_INTROS[$family] }}</p>
                @if ($devicesByFamily->has($family))
                    <p class="mt-6 font-mono text-xs text-neutral-400">{{ $devicesByFamily[$family]->pluck('name')->join(' · ') }}</p>
                @endif
                <span class="accent-reparation accent-text mt-auto inline-block pt-8 font-medium transition group-hover:translate-x-1">Voir les réparations &rarr;</span>
            </a>
        @endforeach
    </section>

    <section data-steps class="mx-auto max-w-6xl border-t border-neutral-900/10 px-6 py-32">
        <h2 data-type="1" class="text-3xl font-bold md:text-4xl">Comment ça marche</h2>
        <ol class="mt-16 grid gap-12 md:grid-cols-4">
            @foreach (['Vous décrivez la panne', 'Nous faisons le diagnostic', 'Vous validez le devis', 'Nous réparons'] as $step)
                <li>
                    <span class="font-mono text-sm tracking-[0.16em] text-neutral-400">0{{ $loop->iteration }}</span>
                    <p class="mt-3 text-lg font-semibold">{{ $step }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-32 text-center">
        <x-statement accent="reparation" lead="Un appareil en panne ?" word="On s’en occupe." class="[&>span]:mx-auto" />
        <a data-reveal href="{{ route('contact', ['service' => 'reparation']) }}" class="mt-12 inline-block rounded-full bg-ink px-8 py-4 font-medium text-white transition hover:bg-ink-soft">Demander une réparation</a>
    </section>
</x-layouts.site>
