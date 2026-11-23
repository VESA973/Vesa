<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Mail;
use Throwable;
use UnitEnum;

/**
 * Admin page to set up outgoing email (OVH SMTP by default) and send a test message.
 */
class MailSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|UnitEnum|null $navigationGroup = 'Paramètres';

    protected static ?string $navigationLabel = 'E-mail (SMTP)';

    protected static ?string $title = 'Paramètres e-mail';

    protected static ?string $slug = 'parametres-email';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $stored = Setting::storedMail();

        $this->form->fill([
            'host' => $stored['host'] ?? 'ssl0.ovh.net',
            'scheme' => $stored['scheme'] ?? 'smtps',
            'port' => $stored['port'] ?? 465,
            'username' => $stored['username'] ?? config('mail.mailers.smtp.username'),
            'from_address' => $stored['from_address'] ?? config('mail.from.address'),
            'from_name' => $stored['from_name'] ?? config('mail.from.name'),
            'contact_address' => $stored['contact_address'] ?? config('mail.contact_address'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Serveur d’envoi (SMTP)')
                    ->description('Réglages OVH : serveur ssl0.ovh.net, sécurité SSL, port 465, identifiant = adresse e-mail complète.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('host')
                            ->label('Serveur')
                            ->required(),
                        Select::make('scheme')
                            ->label('Sécurité')
                            ->options(['smtps' => 'SSL (port 465)', 'smtp' => 'STARTTLS (port 587)'])
                            ->required(),
                        TextInput::make('port')
                            ->label('Port')
                            ->numeric()
                            ->required(),
                        TextInput::make('username')
                            ->label('Identifiant (adresse e-mail)')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->label('Mot de passe de la boîte mail')
                            ->password()
                            ->revealable()
                            ->autocomplete('new-password')
                            ->required(fn (): bool => ! Setting::hasMailPassword())
                            ->helperText(fn (): string => Setting::hasMailPassword()
                                ? 'Un mot de passe est enregistré (chiffré). Laissez vide pour le conserver.'
                                : 'Il sera enregistré chiffré dans la base de données.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Adresses')
                    ->columns(2)
                    ->schema([
                        TextInput::make('from_address')
                            ->label('Adresse d’expédition')
                            ->email()
                            ->required(),
                        TextInput::make('from_name')
                            ->label('Nom d’expéditeur')
                            ->required(),
                        TextInput::make('contact_address')
                            ->label('Les demandes du formulaire de contact arrivent sur')
                            ->email()
                            ->required()
                            ->columnSpanFull(),
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
                        Action::make('save')
                            ->label('Enregistrer')
                            ->submit('save'),
                        Action::make('sendTest')
                            ->label('Envoyer un e-mail de test')
                            ->color('gray')
                            ->action(fn () => $this->sendTestEmail()),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        Setting::saveMail($this->form->getState());
        Setting::applyMailConfig();

        $this->form->fill([...$this->data, 'password' => null]);

        Notification::make()->success()->title('Paramètres e-mail enregistrés')->send();
    }

    public function sendTestEmail(): void
    {
        $recipient = config('mail.contact_address');

        try {
            Mail::raw('Ceci est un e-mail de test envoyé depuis l’administration du site V-ESA. La configuration SMTP fonctionne.', function ($message) use ($recipient): void {
                $message->to($recipient)->subject('Test d’envoi — site V-ESA');
            });
        } catch (Throwable $exception) {
            Notification::make()->danger()->title('L’envoi a échoué')->body($exception->getMessage())->persistent()->send();

            return;
        }

        Notification::make()->success()->title("E-mail de test envoyé à {$recipient}")->body('Vérifiez la boîte de réception (et les indésirables).')->send();
    }
}
