<?php

namespace App\Http\Controllers;

use App\Mail\ContactRequestReceived;
use App\Models\ContactRequest;
use App\Models\Setting;
use App\Rules\Recaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Throwable;

class ContactRequestController extends Controller
{
    /**
     * Store a contact request sent from the public site.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'service' => ['required', Rule::in(array_keys(ContactRequest::SERVICES))],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            // Required so V-ESA can call back; digits, spaces and + ( ) . - only.
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9 ().-]{6,29}$/'],
            'company' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:5000'],
        ];

        // "Je ne suis pas un robot" is only checked once both reCAPTCHA keys are set in the admin.
        if (Setting::recaptchaEnabled()) {
            $rules['g-recaptcha-response'] = ['required', 'string', new Recaptcha(Setting::recaptcha()['secret_key'], $request->ip())];
        }

        $validated = collect($request->validate($rules))->except('g-recaptcha-response')->all();

        $contactRequest = ContactRequest::create($validated);

        // The request is already saved in the admin: a mail server problem must not lose it or show an error.
        try {
            Mail::to(config('mail.contact_address'))->send(new ContactRequestReceived($contactRequest));
        } catch (Throwable $exception) {
            report($exception);
        }

        return to_route('contact')->with('status', 'Merci, votre demande a bien été envoyée. Nous vous répondons rapidement.');
    }
}
