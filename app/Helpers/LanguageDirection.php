<?php

namespace App\Helpers;

class LanguageDirection
{
    public static function getDirection($locale)
    {
        $rtlLanguages = ['ar']; // Add more RTL languages if needed

        return in_array($locale, $rtlLanguages) ? 'rtl' : 'ltr';
    }
}
