<?php

namespace App\Services;

use App\Models\LaraTranslation;

class TranslationService
{
    public function getTranslation($key, $locale)
    {
        return LaraTranslation::where('key', $key)
            ->where('locale', $locale)
            ->value('value');
    }

    public function updateTranslation($key, $locale, $value)
    {
        LaraTranslation::updateOrCreate(
            ['key' => $key, 'locale' => $locale],
            ['value' => $value]
        );
    }
}