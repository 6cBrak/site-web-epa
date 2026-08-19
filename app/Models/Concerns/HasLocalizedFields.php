<?php

namespace App\Models\Concerns;

trait HasLocalizedFields
{
    protected function localized(string $field): ?string
    {
        $locale = app()->getLocale();
        $value = $this->attributes[$field.'_'.$locale] ?? null;

        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->attributes[$field.'_fr'] ?? null;
    }
}
