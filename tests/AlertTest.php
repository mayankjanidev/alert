<?php

namespace Mayank\Alert\Tests;

use Mayank\Alert\Alert;

class AlertTest extends \Orchestra\Testbench\TestCase
{
    public function test_alert_message_is_correctly_set()
    {
        Alert::info()->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'info');

        Alert::success()->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'success');

        Alert::warning()->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'warning');

        Alert::failure()->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'failure');
    }

    public function test_alert_works_without_description()
    {
        Alert::info()->title('Title')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getDescription(), null);
        $this->assertTrue(Alert::exists());
    }

    public function test_alert_works_without_title_and_description()
    {
        Alert::info()->flash();
        $alert = Alert::current();

        $this->assertNotEmpty($alert->getTitle());
        $this->assertTrue(Alert::exists());
    }

    public function test_custom_alert_type_works()
    {
        Alert::type('custom')->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'custom');
    }

    public function test_alert_exists_method_works()
    {
        $this->assertFalse(Alert::exists());

        Alert::info()->title('Title')->description('Description')->flash();

        $this->assertTrue(Alert::exists());
    }
}
