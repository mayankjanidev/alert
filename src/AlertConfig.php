<?php

namespace Mayank\Alert;

use Mayank\Alert\Enums\AlertTheme;

class AlertConfig
{
    public static function getSessionKey(): string
    {
        return config('alert.session_key', 'alert');
    }

    public static function getTheme(): AlertTheme
    {
        return AlertTheme::tryFrom(config('alert.theme', 'default')) ?? AlertTheme::default;
    }
}
