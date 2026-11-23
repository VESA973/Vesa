<x-mail::message>
# Nouvelle demande de contact

**{{ App\Models\ContactRequest::SERVICES[$contactRequest->service] ?? $contactRequest->service }}**

<x-mail::table>
| | |
|:--|:--|
| Nom | {{ $contactRequest->name }} |
| E-mail | {{ $contactRequest->email }} |
| Téléphone | {{ $contactRequest->phone ?: '—' }} |
| Entreprise | {{ $contactRequest->company ?: '—' }} |
| Reçue le | {{ $contactRequest->created_at->format('d/m/Y à H:i') }} |
</x-mail::table>

**Message**

{{ $contactRequest->message }}

<x-mail::button :url="url('/admin/contact-requests/'.$contactRequest->id)">
Voir dans l’administration
</x-mail::button>

Répondez directement à cet e-mail pour écrire à {{ $contactRequest->name }}.
</x-mail::message>
