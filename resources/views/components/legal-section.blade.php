@props(['title'])

<section class="grid gap-6 border-t border-neutral-900/10 py-10 md:grid-cols-12">
    <h2 class="text-lg font-bold md:col-span-4">{{ $title }}</h2>
    <div class="space-y-3 leading-relaxed text-neutral-600 md:col-span-8 [&_a]:text-ink [&_a]:underline [&_a]:underline-offset-4 [&_strong]:text-neutral-900">
        {{ $slot }}
    </div>
</section>
