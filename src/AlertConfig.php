<?php

namespace Mayank\Alert;

class AlertConfig
{
    public static function getSessionKey(): string
    {
        return config('alert.session_key', 'alert');
    }
}
