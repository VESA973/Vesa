<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

/**
 * Site settings edited in the admin, stored as key/value pairs. Secrets are stored encrypted.
 */
#[Fillable(['key', 'value'])]
class Setting extends Model
{
    public const MAIL_CACHE_KEY = 'settings.mail';

    /**
     * Mail settings editable in the admin (the password is handled separately).
     *
     * @var list<string>
     */
    public const MAIL_KEYS = ['host', 'scheme', 'port', 'username', 'from_address', 'from_name', 'contact_address'];

    /**
     * Raw stored mail settings, password still encrypted. Cached; cleared on save.
     *
     * @return array<string, string|null>
     */
    public static function storedMail(): array
    {
        return Cache::rememberForever(self::MAIL_CACHE_KEY, fn (): array => self::query()
            ->where('key', 'like', 'mail.%')
            ->pluck('value', 'key')
            ->mapWithKeys(fn (?string $value, string $key): array => [substr($key, 5) => $value])
            ->all());
    }

    /**
     * Save mail settings; an empty password keeps the one already stored.
     *
     * @param  array<string, mixed>  $data
     */
    public static function saveMail(array $data): void
    {
        foreach (self::MAIL_KEYS as $key) {
            self::query()->updateOrCreate(['key' => "mail.{$key}"], ['value' => isset($data[$key]) ? (string) $data[$key] : null]);
        }

        if (filled($data['password'] ?? null)) {
            self::query()->updateOrCreate(['key' => 'mail.password'], ['value' => Crypt::encryptString($data['password'])]);
        }

        Cache::forget(self::MAIL_CACHE_KEY);
    }

    public static function hasMailPassword(): bool
    {
        return filled(self::storedMail()['password'] ?? null);
    }

    /**
     * Push the stored mail settings into Laravel's mail config (called on every request).
     */
    public static function applyMailConfig(): void
    {
        $stored = self::storedMail();
        if (blank($stored['host'] ?? null)) {
            return;
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $stored['host'],
            'mail.mailers.smtp.scheme' => $stored['scheme'] ?? 'smtps',
            'mail.mailers.smtp.port' => (int) ($stored['port'] ?? 465),
            'mail.mailers.smtp.username' => $stored['username'] ?? null,
            'mail.mailers.smtp.password' => filled($stored['password'] ?? null) ? Crypt::decryptString($stored['password']) : null,
            'mail.from.address' => $stored['from_address'] ?? config('mail.from.address'),
            'mail.from.name' => $stored['from_name'] ?? config('mail.from.name'),
            'mail.contact_address' => $stored['contact_address'] ?? config('mail.contact_address'),
        ]);
    }
}
