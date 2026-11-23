@php
    use App\Models\RepairDevice;

    $label = RepairDevice::FAMILIES[$family];
@endphp

<x-layouts.site :title="'Réparation — '.$label" :description="RepairDevice::FAMILY_INTROS[$family]">
    <section class="mx-auto max-w-6xl px-6 pt-20 pb-12 md:pt-28">
        <a data-reveal href="{{ route('reparation') }}" class="text-sm text-neutral-400 transition hover:text-neutral-900">&larr; Toutes les réparations</a>
        <x-boot-line class="mt-10">Diagnostic // {{ $family }}</x-boot-line>
        <x-statement as="h1" accent="reparation" lead="Réparation" :word="$label" />
        <p data-type="4" data-type-speed="18" class="mt-10 max-w-2xl text-2xl leading-relaxed text-neutral-600">{{ RepairDevice::FAMILY_INTROS[$family] }}</p>

        {{-- Switch between sections --}}
        <nav data-reveal="0.3" class="mt-12 flex flex-wrap gap-2" aria-label="Rubriques de réparation">
            @foreach (RepairDevice::FAMILIES as $otherFamily => $otherLabel)
                <a href="{{ route('reparation.family', $otherFamily) }}" @class([
                    'border px-3.5 py-1.5 text-sm font-semibold transition',
                    'border-ink bg-ink text-white' => $otherFamily === $family,
                    'border-neutral-300 hover:border-ink' => $otherFamily !== $family,
                ]) @if ($otherFamily === $family) aria-current="page" @endif>{{ $otherLabel }}</a>
            @endforeach
        </nav>
    </section>

    <section class="mx-auto max-w-6xl border-t border-neutral-900/10 px-6 py-16">
        @if ($devices->isEmpty())
            <p class="py-16 text-center text-xl text-neutral-400">Cette rubrique sera bientôt complétée. Contactez-nous pour toute demande.</p>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($devices as $device)
                    <x-repair-device-card data-reveal="{{ ($loop->index % 2) * 0.1 }}" :device="$device" />
                @endforeach
            </div>
        @endif
    </section>

    <section class="mx-auto max-w-6xl px-6 py-32 text-center">
        <x-statement accent="reparation" lead="Votre appareil n’est pas dans la liste ?" word="Demandez-nous." class="[&>span]:mx-auto" />
        <a data-reveal href="{{ route('contact', ['service' => 'reparation']) }}" class="mt-12 inline-block rounded-full bg-ink px-8 py-4 font-medium text-white transition hover:bg-ink-soft">Demander une réparation</a>
    </section>
</x-layouts.site>
