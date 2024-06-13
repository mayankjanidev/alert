<?php

namespace Mayank\Alert\Tests;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

use Mayank\Alert\Alert;
use Mayank\Alert\AlertConfig;

class AlertConfigTest extends \Orchestra\Testbench\TestCase
{
    public function test_config_session_key()
    {
        $this->assertNotEmpty(AlertConfig::getSessionKey());

        Config::set('alert.session_key', 'custom_alert_key');
        $this->assertSame(AlertConfig::getSessionKey(), 'custom_alert_key');

        Alert::info()->flash();
        $this->assertTrue(Session::has(AlertConfig::getSessionKey()));
        $this->assertNotNull(Alert::current());
        $this->assertTrue(Alert::exists());
    }
}
