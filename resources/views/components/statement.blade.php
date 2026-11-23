@props(['accent', 'word', 'lead' => 'V-ESA c’est', 'as' => 'h2', 'typed' => true])

{{-- "V-ESA c'est" + one keyword in the activity's gradient. Both lines are typed out like a terminal unless :typed="false". --}}
<{{ $as }} {{ $attributes->class('leading-none') }}>
    <span @if ($typed) data-type="2" data-type-speed="40" @else data-reveal @endif class="block text-lg font-medium text-neutral-400">{{ $lead }}</span>
    <span @if ($typed) data-type="3" data-type-speed="85" @else data-reveal="0.1" @endif class="accent-{{ $accent }} accent-text mt-2 block pb-2 font-script text-4xl font-bold sm:text-5xl md:text-6xl">{{ $word }}</span>
</{{ $as }}>
