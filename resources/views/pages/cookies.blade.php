@php
    $cookies = [
        [config('session.cookie'), 'Garde votre session pendant la visite (par exemple pour le formulaire de contact).', 'Nécessaire', config('session.lifetime').' minutes'],
        ['XSRF-TOKEN', 'Protège les formulaires contre les envois frauduleux.', 'Nécessaire', config('session.lifetime').' minutes'],
        ['vesa-cookie-consent', 'Mémorise votre choix sur le bandeau cookies (stockage local du navigateur).', 'Nécessaire', '13 mois'],
    ];
@endphp

<x-layouts.site title="Politique cookies" description="Les cookies utilisés sur le site V-ESA et comment gérer vos choix.">
    <div class="mx-auto max-w-4xl px-6 pt-20 pb-32 md:pt-28">
        <x-boot-line>Document // cookies</x-boot-line>
        <h1 data-type="2" data-type-speed="45" class="text-3xl font-bold md:text-5xl">Politique cookies</h1>
        <p data-reveal class="mt-6 text-lg text-neutral-500">Ce site n’utilise ni publicité ni pistage. Voici exactement ce qu’il dépose sur votre appareil.</p>

        <div class="mt-16">
            <x-legal-section title="Qu’est-ce qu’un cookie ?">
                <p>Un petit fichier enregistré par votre navigateur quand vous visitez un site. Il permet par exemple de garder une session ouverte ou de retenir une préférence.</p>
            </x-legal-section>

            <x-legal-section title="Cookies utilisés">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="font-mono text-xs tracking-[0.12em] text-neutral-400 uppercase">
                            <tr><th class="py-2 pr-4">Nom</th><th class="py-2 pr-4">Rôle</th><th class="py-2 pr-4">Type</th><th class="py-2">Durée</th></tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-900/10">
                            @foreach ($cookies as [$name, $role, $type, $duration])
                                <tr>
                                    <td class="py-3 pr-4 font-mono text-xs text-neutral-900">{{ $name }}</td>
                                    <td class="py-3 pr-4">{{ $role }}</td>
                                    <td class="py-3 pr-4">{{ $type }}</td>
                                    <td class="py-3 whitespace-nowrap">{{ $duration }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p>Ces éléments sont indispensables au fonctionnement du site : ils ne demandent pas votre consentement (article 82 de la loi Informatique et Libertés).</p>
            </x-legal-section>

            <x-legal-section title="Mesure d’audience">
                <p>Aucune mesure d’audience n’est active aujourd’hui. Si nous en ajoutons une, elle ne sera activée qu’après votre accord via le bandeau, et votre refus sera respecté.</p>
            </x-legal-section>

            <x-legal-section title="Gérer vos choix">
                <p>Vous pouvez revenir sur votre choix à tout moment. Il est conservé 13 mois, puis le bandeau vous est de nouveau proposé.</p>
                <p><button type="button" data-cookie-settings class="rounded-full border border-ink px-5 py-2 text-sm font-medium text-ink transition hover:bg-ink hover:text-white">Modifier mes choix</button></p>
            </x-legal-section>

            <x-legal-section title="Contact">
                <p>Pour toute question : <a href="mailto:contact@v-esa.fr">contact@v-esa.fr</a>. Voir aussi les <a href="{{ route('legal') }}">mentions légales</a>.</p>
            </x-legal-section>
        </div>
    </div>
</x-layouts.site>
