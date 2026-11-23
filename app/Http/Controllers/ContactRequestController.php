<?php

namespace App\Http\Controllers;

use App\Mail\ContactRequestReceived;
use App\Models\ContactRequest;
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
        $validated = $request->validate([
            'service' => ['required', Rule::in(array_keys(ContactRequest::SERVICES))],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

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
