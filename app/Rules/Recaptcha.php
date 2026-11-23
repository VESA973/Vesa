<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * Checks the "Je ne suis pas un robot" answer with Google. If Google cannot be reached the
 * form is refused (better than letting spam through); the visitor can simply retry.
 */
class Recaptcha implements ValidationRule
{
    public function __construct(private string $secretKey, private ?string $visitorIp = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
                'response' => (string) $value,
                'remoteip' => $this->visitorIp,
            ]);
        } catch (ConnectionException) {
            $fail('La vérification anti-robot est momentanément indisponible. Réessayez dans un instant.');

            return;
        }

        if ($response->json('success') !== true) {
            $fail('La vérification « Je ne suis pas un robot » a échoué. Cochez à nouveau la case.');
        }
    }
}
