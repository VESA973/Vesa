@php
    use App\Models\ContactRequest;
    use App\Models\Setting;

    $selectedService = old('service', request('service'));
    $recaptchaSiteKey = Setting::recaptchaEnabled() ? Setting::recaptcha()['site_key'] : null;
    $inputClasses = 'mt-2 w-full border-0 border-b border-neutral-200 bg-transparent px-0 py-3 text-lg focus:border-ink focus:ring-0 focus:outline-none';
@endphp

<x-layouts.site title="Contact" description="Contactez V-ESA pour un projet numérique ou une réparation électronique.">
    <section class="mx-auto max-w-2xl px-6 pt-24 pb-32 md:pt-36">
        <x-boot-line>Canal de communication ouvert</x-boot-line>
        <x-statement as="h1" accent="solutions" lead="Un projet, une panne ?" word="Écrivez-nous." />
        <p data-type="4" data-type-speed="25" class="mt-6 text-lg text-neutral-500">Nous répondons à chaque demande rapidement.</p>

        @if (session('status'))
            <p class="mt-10 rounded-full bg-white/70 px-6 py-4 backdrop-blur-xl text-center text-neutral-800" role="status">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="mt-16 space-y-10" data-reveal="0.3">
            @csrf

            <fieldset>
                <legend class="text-sm text-neutral-400">Votre demande concerne</legend>
                <div class="mt-2 grid gap-3 sm:grid-cols-3">
                    @foreach (ContactRequest::SERVICES as $value => $label)
                        <label class="flex cursor-pointer items-center justify-center rounded-full px-4 py-3 text-sm ring-1 ring-neutral-200 transition hover:ring-ink has-checked:bg-ink has-checked:text-white has-checked:ring-ink has-focus-visible:outline-2 has-focus-visible:outline-offset-2">
                            <input type="radio" name="service" value="{{ $value }}" class="sr-only" @checked($selectedService === $value) required>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
                @error('service') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </fieldset>

            <div class="grid gap-6 sm:grid-cols-2">
                @foreach ([
                    ['name', 'Nom', 'text', true, 'name'],
                    ['email', 'E-mail', 'email', true, 'email'],
                    ['phone', 'Téléphone', 'tel', false, 'tel'],
                    ['company', 'Entreprise', 'text', false, 'organization'],
                ] as [$field, $label, $type, $isRequired, $autocomplete])
                    <div>
                        <label for="{{ $field }}" class="text-sm text-neutral-400">{{ $label }} @unless ($isRequired)<span class="text-neutral-300">(facultatif)</span>@endunless</label>
                        <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}" value="{{ old($field) }}" autocomplete="{{ $autocomplete }}" class="{{ $inputClasses }}" @required($isRequired)>
                        @error($field) <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>

            <div>
                <label for="message" class="text-sm text-neutral-400">Message</label>
                <textarea id="message" name="message" rows="4" class="{{ $inputClasses }}" required>{{ old('message') }}</textarea>
                @error('message') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            @if ($recaptchaSiteKey)
                {{-- Google reCAPTCHA v2, loaded on this page only --}}
                <div>
                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                    @error('g-recaptcha-response') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-2 text-xs text-neutral-400">Protégé par reCAPTCHA : les <a href="https://policies.google.com/privacy" target="_blank" rel="noopener" class="underline">règles de confidentialité</a> et les <a href="https://policies.google.com/terms" target="_blank" rel="noopener" class="underline">conditions d’utilisation</a> de Google s’appliquent.</p>
                </div>
                <script src="https://www.google.com/recaptcha/api.js?hl=fr" async defer></script>
            @endif

            <button type="submit" class="rounded-full bg-ink px-8 py-4 font-medium text-white transition hover:bg-ink-soft">Envoyer la demande</button>
        </form>
    </section>
</x-layouts.site>
