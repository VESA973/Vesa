@props(['device'])

{{-- One repaired device (brand or console model) with the list of repairs performed. --}}
<article {{ $attributes->class('notch-flip flex flex-col bg-white/80 p-8 ring-1 ring-neutral-900/10 backdrop-blur-xl') }}>
    @if ($device->image)
        <img src="{{ asset('storage/'.$device->image) }}" alt="{{ $device->name }}" loading="lazy" class="mb-6 aspect-[16/9] w-full object-cover">
    @endif
    <div class="flex items-baseline justify-between gap-4">
        <h2 class="text-xl font-bold">{{ $device->name }}</h2>
        <span class="font-mono text-xs text-neutral-400">{{ str_pad($device->services->count(), 2, '0', STR_PAD_LEFT) }} rép.</span>
    </div>
    @if ($device->description)
        <p class="mt-3 text-neutral-500">{{ $device->description }}</p>
    @endif
    @if ($device->services->isNotEmpty())
        <ul class="mt-6 divide-y divide-neutral-900/10 border-t border-neutral-900/10">
            @foreach ($device->services as $service)
                <li class="flex items-baseline justify-between gap-4 py-3">
                    <span>
                        <span class="accent-reparation accent-text mr-2 font-mono">›</span>{{ $service->name }}
                        @if ($service->description)
                            <span class="block pl-5 text-sm text-neutral-400">{{ $service->description }}</span>
                        @endif
                    </span>
                    @if ($service->price_from !== null)
                        <span class="shrink-0 font-mono text-sm text-ink">dès {{ rtrim(rtrim(number_format((float) $service->price_from, 2, ',', ' '), '0'), ',') }} €</span>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('contact', ['service' => 'reparation']) }}" class="mt-auto pt-8 text-sm font-semibold text-ink underline-offset-4 hover:underline">Demander une réparation &rarr;</a>
</article>
