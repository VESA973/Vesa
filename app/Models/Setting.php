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

    public const CAPTCHA_CACHE_KEY = 'settings.captcha';

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

    /**
     * reCAPTCHA keys: admin values first, then .env. Both are needed for the captcha to be active.
     *
     * @return array{site_key: ?string, secret_key: ?string}
     */
    public static function recaptcha(): array
    {
        $stored = Cache::rememberForever(self::CAPTCHA_CACHE_KEY, fn (): array => self::query()
            ->whereIn('key', ['captcha.site_key', 'captcha.secret_key'])
            ->pluck('value', 'key')
            ->all());

        $siteKey = $stored['captcha.site_key'] ?? null;
        $secretKey = filled($stored['captcha.secret_key'] ?? null) ? Crypt::decryptString($stored['captcha.secret_key']) : null;

        return [
            'site_key' => $siteKey ?: config('services.recaptcha.site_key'),
            'secret_key' => $secretKey ?: config('services.recaptcha.secret_key'),
        ];
    }

    public static function recaptchaEnabled(): bool
    {
        $keys = self::recaptcha();

        return filled($keys['site_key']) && filled($keys['secret_key']);
    }

    /**
     * Save reCAPTCHA keys; an empty secret keeps the stored one, an empty site key turns the captcha off.
     *
     * @param  array<string, mixed>  $data
     */
    public static function saveRecaptcha(array $data): void
    {
        if (blank($data['site_key'] ?? null)) {
            self::query()->whereIn('key', ['captcha.site_key', 'captcha.secret_key'])->delete();
        } else {
            self::query()->updateOrCreate(['key' => 'captcha.site_key'], ['value' => trim($data['site_key'])]);
            if (filled($data['secret_key'] ?? null)) {
                self::query()->updateOrCreate(['key' => 'captcha.secret_key'], ['value' => Crypt::encryptString(trim($data['secret_key']))]);
            }
        }

        Cache::forget(self::CAPTCHA_CACHE_KEY);
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
