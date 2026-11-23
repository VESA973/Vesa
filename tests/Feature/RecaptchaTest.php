<?php

namespace Tests\Feature;

use App\Filament\Pages\RecaptchaSettings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class RecaptchaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function contactForm(array $overrides = []): array
    {
        return [
            'service' => 'reparation',
            'name' => 'Marie Martin',
            'email' => 'marie@example.com',
            'message' => 'Mon piano ne s’allume plus.',
            ...$overrides,
        ];
    }

    private function enableRecaptcha(): void
    {
        Setting::saveRecaptcha(['site_key' => 'site-key-test', 'secret_key' => 'secret-key-test']);
    }

    public function test_form_works_without_captcha_when_keys_are_not_set(): void
    {
        $this->get('/contact')->assertOk()->assertDontSee('g-recaptcha', false);
        $this->post('/contact', $this->contactForm())->assertSessionHasNoErrors();
        $this->assertDatabaseCount('contact_requests', 1);
    }

    public function test_contact_page_shows_the_captcha_box_when_enabled(): void
    {
        $this->enableRecaptcha();

        $this->get('/contact')
            ->assertOk()
            ->assertSee('class="g-recaptcha" data-sitekey="site-key-test"', false)
            ->assertSee('https://www.google.com/recaptcha/api.js?hl=fr', false);
    }

    public function test_unchecked_box_is_refused_in_french(): void
    {
        $this->enableRecaptcha();

        $this->post('/contact', $this->contactForm())
            ->assertSessionHasErrors(['g-recaptcha-response' => 'Cochez la case « Je ne suis pas un robot ».']);
        $this->assertDatabaseCount('contact_requests', 0);
    }

    public function test_answer_confirmed_by_google_is_accepted(): void
    {
        $this->enableRecaptcha();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => true])]);

        $this->post('/contact', $this->contactForm(['g-recaptcha-response' => 'token']))->assertSessionHasNoErrors();

        $this->assertDatabaseCount('contact_requests', 1);
        Http::assertSent(fn ($request): bool => $request['secret'] === 'secret-key-test' && $request['response'] === 'token');
    }

    public function test_answer_rejected_by_google_is_refused(): void
    {
        $this->enableRecaptcha();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => false])]);

        $this->post('/contact', $this->contactForm(['g-recaptcha-response' => 'robot']))->assertSessionHasErrors('g-recaptcha-response');
        $this->assertDatabaseCount('contact_requests', 0);
    }

    public function test_google_unreachable_refuses_without_crashing(): void
    {
        $this->enableRecaptcha();
        Http::fake(fn () => throw new ConnectionException('timeout'));

        $this->post('/contact', $this->contactForm(['g-recaptcha-response' => 'token']))->assertSessionHasErrors('g-recaptcha-response');
    }

    public function test_validation_messages_are_in_french(): void
    {
        $this->post('/contact', $this->contactForm(['name' => '']))
            ->assertSessionHasErrors(['name' => 'Le champ nom est obligatoire.']);
    }

    public function test_admin_saves_keys_with_an_encrypted_secret(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(RecaptchaSettings::class)
            ->fillForm(['site_key' => 'site-key-test', 'secret_key' => 'secret-key-test'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(Setting::recaptchaEnabled());
        $this->assertNotSame('secret-key-test', Setting::query()->where('key', 'captcha.secret_key')->value('value'));

        Livewire::test(RecaptchaSettings::class)->fillForm(['site_key' => '', 'secret_key' => ''])->call('save');
        $this->assertFalse(Setting::recaptchaEnabled());
    }
}
