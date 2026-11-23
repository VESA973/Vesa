@props([
    'title' => null,
    'description' => 'V-ESA à Cayenne : création de sites et d’applications pour les entreprises, réparation de pianos numériques, synthétiseurs, consoles de jeux et ordinateurs.',
])

@php
    $links = [
        'home' => 'Accueil',
        'solutions' => 'Solutions',
        'reparation' => 'Réparation',
        'projects.index' => 'Réalisations',
        'contact' => 'Contact',
    ];

    // Sub-menu under "Réparation": one page per section.
    $repairFamilies = App\Models\RepairDevice::FAMILIES;

    // Link previews (WhatsApp, Facebook, LinkedIn…): one share image for the whole site.
    $pageTitle = $title ? $title.' · V-ESA' : 'V-ESA · Solutions numériques & réparation électronique';
    $shareImage = asset('images/og-share.jpg').'?v='.filemtime(public_path('images/og-share.jpg'));
    $shareImageAlt = 'Une conseillère V-ESA consulte des données sur une tablette holographique, à côté du titre « Solutions numériques & réparation électronique ».';
    $isActive = fn (string $route): bool => request()->routeIs(str_replace('.index', '.*', $route), $route.'.*');
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="V-ESA">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $shareImageAlt }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $shareImage }}">
    <meta name="twitter:image:alt" content="{{ $shareImageAlt }}">
    <meta name="theme-color" content="#173a34">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <script type="speculationrules">
        {"prefetch": [{"where": {"and": [{"href_matches": "/*"}, {"not": {"href_matches": "/admin*"}}]}, "eagerness": "moderate"}]}
    </script>
    @fonts
    <script>
        document.documentElement.classList.add('js');
        // The loader only plays on arrival, not on every page of the visit.
        try { if (sessionStorage.getItem('vesa-loaded')) document.documentElement.classList.add('no-loader'); } catch (error) {}
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-neutral-900 antialiased selection:bg-ink selection:text-white">
    {{-- Arrival loader (resources/js/loader.js): boot screen with the logo, a counter and a progress bar. --}}
    <div data-site-loader class="site-loader" aria-hidden="true">
        <div class="flex flex-col items-center">
            <div class="relative grid size-28 place-items-center">
                <span class="loader-ring absolute inset-0 rounded-full border-2 border-dashed border-[#3fbf6a]/60"></span>
                <img src="{{ asset('images/logo-mark.png') }}" alt="" width="273" height="256" class="loader-logo h-14 w-auto">
            </div>
            <p class="mt-6 font-display text-2xl font-bold tracking-[0.2em] text-ink">V-ESA</p>
            <p class="mt-4 font-mono text-[0.7rem] tracking-[0.18em] text-neutral-400 uppercase">&gt; Initialisation du système<span class="loader-caret">_</span></p>
            <div class="mt-6 h-0.5 w-56 overflow-hidden rounded-full bg-neutral-900/10">
                <div data-loader-bar class="accent-solutions accent-bg h-full w-0"></div>
            </div>
            <p data-loader-count class="mt-3 font-mono text-xs text-ink">000 %</p>
        </div>
    </div>

    {{-- Background: plain white with a sober digital rain of code (resources/js/code-rain.js). --}}
    <div class="site-backdrop" aria-hidden="true" style="view-transition-name: site-backdrop">
        <canvas data-code-rain class="absolute inset-0 size-full"></canvas>
    </div>

    <header class="site-header sticky top-0 z-50 bg-white/50 backdrop-blur-xl">
        <nav class="mx-auto flex h-20 max-w-6xl items-center justify-between px-6" aria-label="Navigation principale">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-xl font-bold tracking-tight text-ink">
                <img src="{{ asset('images/logo-mark.png') }}" alt="" width="273" height="256" class="h-10 w-auto">
                V-ESA
            </a>

            <ul class="flex items-center gap-6 text-sm md:gap-10">
                @foreach ($links as $route => $label)
                    <li @class(['hidden sm:block', 'group relative' => $route === 'reparation'])>
                        @if ($route === 'contact')
                            <a href="{{ route($route) }}" class="rounded-full bg-ink px-5 py-2.5 font-medium text-white transition hover:bg-ink-soft">Écrivez-nous</a>
                        @else
                            <a href="{{ route($route) }}" @class(['relative transition hover:text-neutral-900', 'text-neutral-900' => $isActive($route), 'text-neutral-400' => ! $isActive($route)]) @if ($isActive($route)) aria-current="page" @endif>{{ $label }}@if ($route === 'reparation') <span aria-hidden="true" class="inline-block text-xs transition-transform duration-300 group-hover:rotate-180 group-focus-within:rotate-180">▾</span>@endif
                                @if ($isActive($route))
                                    {{-- Underline of the current section; it glides to the new link when the page changes --}}
                                    <span aria-hidden="true" class="nav-indicator accent-solutions accent-bg absolute inset-x-0 -bottom-2 h-0.5 rounded-full"></span>
                                @endif
                            </a>
                            @if ($route === 'reparation')
                                {{-- Opens on hover and when a link inside gets keyboard focus --}}
                                <div class="invisible absolute top-full left-1/2 w-max min-w-60 -translate-x-1/2 pt-4 opacity-0 transition-[opacity,visibility] duration-200 group-focus-within:visible group-focus-within:opacity-100 group-hover:visible group-hover:opacity-100">
                                    {{-- Panel drops down and unfolds; items slide in one after another --}}
                                    <ul class="origin-top -translate-y-2 scale-95 rounded-xl bg-white/95 p-2 shadow-xl ring-1 ring-neutral-900/10 backdrop-blur-xl transition duration-300 ease-out group-focus-within:translate-y-0 group-focus-within:scale-100 group-hover:translate-y-0 group-hover:scale-100">
                                        @foreach ($repairFamilies as $family => $familyLabel)
                                            <li class="-translate-x-2 opacity-0 transition duration-300 ease-out group-focus-within:translate-x-0 group-focus-within:opacity-100 group-hover:translate-x-0 group-hover:opacity-100" style="transition-delay: {{ 60 + $loop->index * 50 }}ms">
                                                <a href="{{ route('reparation.family', $family) }}" @class(['group/item relative flex items-center gap-3 overflow-hidden rounded-lg px-4 py-3 whitespace-nowrap transition hover:bg-neutral-50 hover:text-ink', 'text-ink font-medium' => request()->route('family') === $family, 'text-neutral-600' => request()->route('family') !== $family])>
                                                    <span aria-hidden="true" class="accent-reparation accent-bg absolute inset-y-2 left-0 w-0.5 origin-center scale-y-0 rounded-full transition-transform duration-300 group-hover/item:scale-y-100"></span>
                                                    <span class="font-mono text-[0.65rem] text-neutral-400 transition-colors group-hover/item:text-ink">0{{ $loop->iteration }}</span>
                                                    <span class="transition-transform duration-300 group-hover/item:translate-x-1">{{ $familyLabel }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif
                    </li>
                @endforeach

                {{-- Phone menu: native <details>, so it works without JavaScript; the panel covers the screen under the header --}}
                <li class="sm:hidden">
                    <details data-mobile-menu class="group/menu">
                        <summary class="grid size-11 cursor-pointer list-none place-items-center rounded-full ring-1 ring-neutral-900/15 [&::-webkit-details-marker]:hidden" aria-label="Menu">
                            <span aria-hidden="true" class="relative block h-3 w-5">
                                <span class="absolute inset-x-0 top-0 h-0.5 rounded-full bg-ink transition duration-300 group-open/menu:top-1.5 group-open/menu:rotate-45"></span>
                                <span class="absolute inset-x-0 bottom-0 h-0.5 rounded-full bg-ink transition duration-300 group-open/menu:bottom-1 group-open/menu:-rotate-45"></span>
                            </span>
                        </summary>
                        <div class="mobile-menu absolute inset-x-0 top-full h-[calc(100svh-5rem)] overflow-y-auto bg-white/95 px-6 pt-6 pb-12 backdrop-blur-xl">
                            <p class="font-mono text-[0.65rem] tracking-[0.18em] text-neutral-400 uppercase">&gt; Navigation</p>
                            <ul class="mt-6 divide-y divide-neutral-900/10 border-y border-neutral-900/10">
                                @foreach ($links as $route => $label)
                                    <li>
                                        <a href="{{ route($route) }}" @class(['flex items-baseline gap-4 py-4 font-display text-2xl font-bold', 'text-ink' => $isActive($route), 'text-neutral-900' => ! $isActive($route)]) @if ($isActive($route)) aria-current="page" @endif>
                                            <span class="font-mono text-xs font-normal text-neutral-400">0{{ $loop->iteration }}</span>{{ $label }}
                                        </a>
                                        @if ($route === 'reparation')
                                            <ul class="-mt-1 mb-4 space-y-1 pl-9">
                                                @foreach ($repairFamilies as $family => $familyLabel)
                                                    <li><a href="{{ route('reparation.family', $family) }}" class="flex items-center gap-2 py-1.5 text-neutral-600"><span class="accent-reparation accent-text font-mono">›</span>{{ $familyLabel }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('contact') }}" class="mt-8 block rounded-full bg-ink px-6 py-4 text-center font-medium text-white">Parlez-nous de votre projet</a>
                            <p class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-xs text-neutral-400">
                                <a href="{{ route('legal') }}">Mentions légales</a>
                                <a href="{{ route('cookies') }}">Politique cookies</a>
                            </p>
                        </div>
                    </details>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="mx-auto max-w-6xl px-6 py-16">
        <div class="flex flex-col gap-6 border-t border-neutral-200 pt-10 text-sm text-neutral-400 md:flex-row md:items-center md:justify-between">
            <p class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="V-ESA" width="350" height="480" class="h-16 w-auto">
                <span>&copy; {{ date('Y') }}</span>
            </p>
            <ul class="flex flex-wrap gap-x-8 gap-y-2">
                @foreach ($links as $route => $label)
                    <li><a href="{{ route($route) }}" class="hover:text-neutral-900">{{ $label }}</a></li>
                    @if ($route === 'reparation')
                        @foreach ($repairFamilies as $family => $familyLabel)
                            <li><a href="{{ route('reparation.family', $family) }}" class="hover:text-neutral-900">{{ $familyLabel }}</a></li>
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </div>
        <p class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-xs text-neutral-400">
            <a href="{{ route('legal') }}" class="hover:text-neutral-900">Mentions légales</a>
            <a href="{{ route('cookies') }}" class="hover:text-neutral-900">Politique cookies</a>
            <button type="button" data-cookie-settings class="hover:text-neutral-900">Gérer les cookies</button>
        </p>
    </footer>

    {{-- Cookie banner, shown by resources/js/cookie-consent.js until the visitor chooses --}}
    <div data-cookie-banner hidden role="dialog" aria-live="polite" aria-labelledby="cookie-title" class="fixed inset-x-4 bottom-4 z-[60] md:right-auto md:left-6 md:max-w-md">
        <div class="rounded-2xl bg-white/90 p-6 shadow-2xl ring-1 ring-neutral-900/10 backdrop-blur-xl">
            <p id="cookie-title" class="font-mono text-xs tracking-[0.16em] text-ink uppercase">&gt; Cookies</p>
            <p class="mt-3 text-sm leading-relaxed text-neutral-600">Ce site n’utilise que des cookies nécessaires à son fonctionnement, sans publicité ni pistage. Si nous ajoutons un jour une mesure d’audience, elle ne sera activée qu’avec votre accord. <a href="{{ route('cookies') }}" class="text-ink underline underline-offset-4">En savoir plus</a></p>
            <div class="mt-5 grid grid-cols-2 gap-3">
                <button type="button" data-consent="refused" class="rounded-full border border-ink px-5 py-2.5 text-sm font-medium text-ink transition hover:bg-neutral-50">Refuser</button>
                <button type="button" data-consent="accepted" class="rounded-full border border-ink bg-ink px-5 py-2.5 text-sm font-medium text-white transition hover:bg-ink-soft">Accepter</button>
            </div>
        </div>
    </div>
</body>
</html>
