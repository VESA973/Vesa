<?php

namespace Tests\Feature;

use App\Filament\Pages\MailSettings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class MailSettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function settings(array $overrides = []): array
    {
        return [
            'host' => 'ssl0.ovh.net',
            'scheme' => 'smtps',
            'port' => 465,
            'username' => 'contact@v-esa.fr',
            'password' => 'secret-mailbox-pass',
            'from_address' => 'contact@v-esa.fr',
            'from_name' => 'V-ESA',
            'contact_address' => 'contact@v-esa.fr',
            ...$overrides,
        ];
    }

    public function test_page_requires_login(): void
    {
        $this->get('/admin/parametres-email')->assertRedirect('/admin/login');
    }

    public function test_admin_saves_settings_with_an_encrypted_password(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(MailSettings::class)
            ->fillForm($this->settings())
            ->call('save')
            ->assertHasNoFormErrors();

        $stored = Setting::query()->where('key', 'mail.password')->value('value');
        $this->assertNotSame('secret-mailbox-pass', $stored);
        $this->assertSame('secret-mailbox-pass', Crypt::decryptString($stored));
        $this->assertSame('ssl0.ovh.net', config('mail.mailers.smtp.host'));
        $this->assertSame('secret-mailbox-pass', config('mail.mailers.smtp.password'));
    }

    public function test_leaving_the_password_empty_keeps_the_stored_one(): void
    {
        $this->actingAs(User::factory()->create());
        Livewire::test(MailSettings::class)->fillForm($this->settings())->call('save');

        Livewire::test(MailSettings::class)
            ->fillForm($this->settings(['password' => null, 'from_name' => 'V-ESA Cayenne']))
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('secret-mailbox-pass', Crypt::decryptString(Setting::query()->where('key', 'mail.password')->value('value')));
        $this->assertSame('V-ESA Cayenne', config('mail.from.name'));
    }

    public function test_test_email_button_reports_success(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create());

        Livewire::test(MailSettings::class)
            ->assertSee('Envoyer un e-mail de test')
            ->call('sendTestEmail')
            ->assertNotified('E-mail de test envoyé à '.config('mail.contact_address'));
    }
}
