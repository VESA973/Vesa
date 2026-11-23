<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Admin page for the Google reCAPTCHA v2 keys protecting the contact form.
 */
class RecaptchaSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Paramètres';

    protected static ?string $navigationLabel = 'Anti-robot (reCAPTCHA)';

    protected static ?string $title = 'Protection anti-robot';

    protected static ?string $slug = 'parametres-recaptcha';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(['site_key' => Setting::recaptcha()['site_key']]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Google reCAPTCHA v2 — case « Je ne suis pas un robot »')
                    ->description(fn (): string => (Setting::recaptchaEnabled() ? 'Actif : la case s’affiche sur le formulaire de contact. ' : 'Inactif : renseignez les deux clés pour l’activer. ')
                        .'Clés à créer sur google.com/recaptcha/admin (type « Case à cocher “Je ne suis pas un robot” », domaine v-esa.fr).')
                    ->schema([
                        TextInput::make('site_key')
                            ->label('Clé du site')
                            ->helperText('Laissez vide et enregistrez pour désactiver le captcha.'),
                        TextInput::make('secret_key')
                            ->label('Clé secrète')
                            ->password()
                            ->revealable()
                            ->autocomplete('new-password')
                            ->helperText(fn (): string => filled(Setting::recaptcha()['secret_key'])
                                ? 'Une clé secrète est enregistrée (chiffrée). Laissez vide pour la conserver.'
                                : 'Elle sera enregistrée chiffrée.'),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label('Enregistrer')->submit('save'),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        Setting::saveRecaptcha($this->form->getState());

        $this->form->fill(['site_key' => Setting::recaptcha()['site_key'], 'secret_key' => null]);

        Notification::make()
            ->success()
            ->title(Setting::recaptchaEnabled() ? 'Captcha activé sur le formulaire de contact' : 'Captcha désactivé')
            ->send();
    }
}
