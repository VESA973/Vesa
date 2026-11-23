@props(['image' => null, 'alt' => '', 'address' => null])

{{-- A site shown inside a minimal browser window (traffic lights + address bar). --}}
<div {{ $attributes->class('overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-neutral-900/10') }}>
    <div class="flex items-center gap-3 border-b border-neutral-900/10 bg-neutral-50/90 px-4 py-2.5">
        <span class="flex shrink-0 gap-1.5" aria-hidden="true">
            <span class="size-2.5 rounded-full bg-[#ff5f57]"></span>
            <span class="size-2.5 rounded-full bg-[#febc2e]"></span>
            <span class="size-2.5 rounded-full bg-[#28c840]"></span>
        </span>
        @if ($address)
            <span class="mx-auto flex min-w-0 max-w-[70%] items-center gap-1.5 truncate rounded-full bg-white px-3 py-1 font-mono text-[0.65rem] text-neutral-500 ring-1 ring-neutral-900/5">
                <svg aria-hidden="true" viewBox="0 0 16 16" class="size-3 shrink-0 fill-current"><path d="M4 7V5a4 4 0 1 1 8 0v2h1v8H3V7h1Zm2 0h4V5a2 2 0 1 0-4 0v2Z"/></svg>
                {{ $address }}
            </span>
        @endif
    </div>
    <div data-pixelate class="relative aspect-[16/10] overflow-hidden bg-neutral-100">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $alt }}" loading="lazy" decoding="async" class="photo-graded size-full object-cover transition duration-700 ease-out group-hover/frame:scale-[1.04]">
            <div class="photo-tint"></div>
        @else
            <div class="accent-solutions accent-bg size-full opacity-20"></div>
        @endif
    </div>
</div>
