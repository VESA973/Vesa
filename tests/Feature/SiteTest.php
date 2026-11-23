<?php

namespace Tests\Feature;

use App\Mail\ContactRequestReceived;
use App\Models\ContactRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string}>
     */
    public static function publicPages(): array
    {
        return [
            'home' => ['/'],
            'solutions' => ['/solutions'],
            'repair' => ['/reparation'],
            'contact' => ['/contact'],
            'legal notice' => ['/mentions-legales'],
            'cookie policy' => ['/cookies'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_public_page_loads(string $path): void
    {
        $this->get($path)->assertOk()->assertSee('V-ESA');
    }

    public function test_legal_pages_show_company_details_and_cookies(): void
    {
        $this->get('/mentions-legales')->assertOk()->assertSee('992 120 170 00012')->assertSee('contact@v-esa.fr');
        $this->get('/cookies')->assertOk()->assertSee(config('session.cookie'))->assertSee('XSRF-TOKEN');
    }

    public function test_every_page_has_the_cookie_banner_with_equal_choices(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('data-cookie-banner', false)
            ->assertSee('data-consent="refused"', false)
            ->assertSee('data-consent="accepted"', false)
            ->assertSee(route('legal'), false);
    }

    public function test_pages_have_link_preview_tags(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<meta property="og:title" content="V-ESA · Solutions numériques &amp; réparation électronique">', false)
            ->assertSee('<meta property="og:image" content="'.asset('images/og-share.jpg'), false)
            ->assertSee('property="og:image:alt"', false)
            ->assertSee('<meta property="og:description" content="V-ESA à Cayenne', false);

        $this->get('/solutions')
            ->assertSee('<meta property="og:title" content="Solutions entreprises · V-ESA">', false)
            ->assertSee('<meta property="og:url" content="'.url('/solutions').'">', false);
    }

    public function test_old_site_addresses_redirect_permanently(): void
    {
        $this->get('/reparation-consoles')->assertStatus(301)->assertRedirect('/reparation/consoles');
        $this->get('/reparation-instruments')->assertStatus(301)->assertRedirect('/reparation/instruments');
        $this->get('/rendez-vous')->assertStatus(301)->assertRedirect('/contact');
        $this->get('/boutique')->assertStatus(301)->assertRedirect('/');
    }

    public function test_contact_page_preselects_service_from_query(): void
    {
        $this->get('/contact?service=reparation')
            ->assertOk()
            ->assertSee('value="reparation" class="sr-only" checked', false);
    }

    public function test_valid_contact_request_is_stored(): void
    {
        $this->post('/contact', [
            'service' => 'solutions',
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'phone' => '0694 12 34 56',
            'message' => 'Je voudrais un site.',
        ])->assertRedirect('/contact')->assertSessionHas('status');

        $this->assertDatabaseHas('contact_requests', [
            'service' => 'solutions',
            'email' => 'jean@example.com',
            'is_handled' => false,
        ]);
    }

    public function test_contact_request_is_emailed_to_the_contact_inbox(): void
    {
        Mail::fake();

        $this->post('/contact', [
            'service' => 'reparation',
            'name' => 'Marie Martin',
            'email' => 'marie@example.com',
            'phone' => '+594 694 12 34 56',
            'message' => 'Mon piano Yamaha ne s’allume plus.',
        ])->assertRedirect('/contact');

        Mail::assertSent(ContactRequestReceived::class, function (ContactRequestReceived $mail): bool {
            return $mail->hasTo(config('mail.contact_address'))
                && $mail->hasReplyTo('marie@example.com')
                && str_contains($mail->envelope()->subject, 'Marie Martin');
        });
    }

    public function test_contact_email_shows_the_request(): void
    {
        $contactRequest = ContactRequest::factory()->create(['message' => 'Écran cassé sur ma Switch']);

        (new ContactRequestReceived($contactRequest))
            ->assertSeeInHtml('Écran cassé sur ma Switch')
            ->assertSeeInHtml($contactRequest->email);
    }

    public function test_contact_request_is_kept_when_the_mail_server_fails(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->post('/contact', [
            'service' => 'solutions',
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'phone' => '0694 12 34 56',
            'message' => 'Je voudrais un site.',
        ])->assertRedirect('/contact')->assertSessionHas('status');

        $this->assertDatabaseHas('contact_requests', ['email' => 'jean@example.com']);
    }

    public function test_phone_number_is_required_and_checked(): void
    {
        $form = ['service' => 'solutions', 'name' => 'Jean Dupont', 'email' => 'jean@example.com', 'message' => 'Bonjour'];

        $this->post('/contact', $form)->assertSessionHasErrors(['phone' => 'Le champ téléphone est obligatoire.']);
        $this->post('/contact', [...$form, 'phone' => 'appelez-moi'])->assertSessionHasErrors(['phone' => 'Le format du champ téléphone n’est pas valide.']);
        $this->post('/contact', [...$form, 'phone' => '06 94 12 34 56'])->assertSessionHasNoErrors();
    }

    public function test_invalid_contact_request_is_rejected(): void
    {
        $this->post('/contact', [
            'service' => 'piratage',
            'name' => '',
            'email' => 'pas-un-email',
            'message' => '',
            'is_handled' => true,
        ])->assertSessionHasErrors(['service', 'name', 'email', 'message']);

        $this->assertDatabaseCount('contact_requests', 0);
    }

    public function test_admin_requires_login(): void
    {
        $this->get('/admin/contact-requests')->assertRedirect('/admin/login');
    }

    public function test_admin_lists_contact_requests(): void
    {
        $contactRequest = ContactRequest::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get('/admin/contact-requests')
            ->assertOk()
            ->assertSee($contactRequest->email);
    }
}
