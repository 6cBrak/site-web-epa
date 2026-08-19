<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['key', 'value'];

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::query()->find($key)?->value ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Get a bilingual content setting (stored as "{$key}_fr" / "{$key}_en"),
     * falling back to French if the current locale's value is empty.
     */
    public static function text(string $key): string
    {
        $value = static::get($key.'_'.app()->getLocale());

        return $value !== null && $value !== '' ? $value : (static::get($key.'_fr') ?? '');
    }

    public static function logoUrl(): string
    {
        $path = static::get('site_logo');

        return $path ? asset('storage/'.$path) : asset('images/logo.jpeg');
    }
}
