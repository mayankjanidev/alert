<?php

namespace Mayank\Alert\Tests;

use Mayank\Alert\ServiceProvider;
use Mayank\Alert\Tests\Models\SampleModelInstances;

use Mayank\Alert\Alert;

class AlertTest extends \Orchestra\Testbench\TestCase
{
    use SampleModelInstances;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

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
        Alert::custom('custom')->title('Title')->description('Description')->flash();
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

    public function test_model_alert_works_for_default_actions()
    {
        Alert::model($this->getCreatedModel())->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'success');
        $this->assertSame($alert->getAction(), 'created');

        Alert::model($this->getUpdatedModel())->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'success');
        $this->assertSame($alert->getAction(), 'updated');

        Alert::model($this->getDeletedModel())->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'success');
        $this->assertSame($alert->getAction(), 'deleted');
    }

    public function test_model_alert_works_for_custom_action()
    {
        Alert::model($this->getCreatedModel())->action('custom_action')->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'success');
        $this->assertSame($alert->getAction(), 'custom_action');
    }

    public function test_model_alert_uses_correct_lang_values()
    {
        Alert::model($this->getCreatedModel())->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Post Created');
        $this->assertSame($alert->getDescription(), 'Post was successfully created.');

        Alert::model($this->getUpdatedModel())->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Post Updated');
        $this->assertSame($alert->getDescription(), 'Post was successfully updated.');

        Alert::model($this->getDeletedModel())->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Post Deleted');
        $this->assertSame($alert->getDescription(), 'Post was successfully deleted.');
    }

    public function test_model_alert_uses_correct_lang_values_for_custom_action()
    {
        Alert::model($this->getCreatedModel())->action('custom_action')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'alert::messages.model.custom_action.title');
        $this->assertSame($alert->getDescription(), 'alert::messages.model.custom_action.description');
    }

    public function test_entity_alert_works_for_default_action()
    {
        Alert::for('settings')->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'success');
        $this->assertSame($alert->getAction(), 'updated');
    }

    public function test_entity_alert_works_for_custom_action()
    {
        Alert::for('settings')->action('custom_action')->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'success');
        $this->assertSame($alert->getAction(), 'custom_action');
    }

    public function test_entity_alert_uses_correct_lang_values_for_default_action()
    {
        Alert::for('settings')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'alert::messages.settings.updated.title');
        $this->assertSame($alert->getDescription(), 'alert::messages.settings.updated.description');
    }

    public function test_entity_alert_uses_correct_lang_values_for_custom_action()
    {
        Alert::for('settings')->action('custom_action')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'alert::messages.settings.custom_action.title');
        $this->assertSame($alert->getDescription(), 'alert::messages.settings.custom_action.description');
    }
}