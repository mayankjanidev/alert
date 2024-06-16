<?php

namespace Mayank\Alert\Tests;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

use Mayank\Alert\Alert;
use Mayank\Alert\AlertConfig;
use Mayank\Alert\Enums\AlertTheme;

class AlertConfigTest extends \Orchestra\Testbench\TestCase
{
    public function test_config_session_key()
    {
        $this->assertNotEmpty(AlertConfig::getSessionKey());

        Config::set('alert.session_key', 'custom_alert_key');
        $this->assertSame(AlertConfig::getSessionKey(), 'custom_alert_key');

        Alert::info()->create();
        $this->assertTrue(Session::has(AlertConfig::getSessionKey()));
        $this->assertNotNull(Alert::current());
        $this->assertTrue(Alert::exists());
    }

    public function test_config_theme()
    {
        $this->assertSame(AlertConfig::getTheme(), AlertTheme::default);

        foreach (AlertTheme::cases() as $alertTheme) {
            Config::set('alert.theme', $alertTheme->value);
            $this->assertSame(AlertConfig::getTheme(), $alertTheme);
        }

        Config::set('alert.theme', 'random_invalid');
        $this->assertSame(AlertConfig::getTheme(), AlertTheme::default);

        Config::set('alert.theme', '');
        $this->assertSame(AlertConfig::getTheme(), AlertTheme::default);

        Config::set('alert.theme', null);
        $this->assertSame(AlertConfig::getTheme(), AlertTheme::default);
    }
}
