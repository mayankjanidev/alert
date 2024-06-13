<?php

namespace Mayank\Alert\Tests;

use Mayank\Alert\ServiceProvider;
use Mayank\Alert\Tests\Models\SampleModelInstances;

use Mayank\Alert\Alert;
use Mayank\Alert\AlertType;

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
        $this->assertSame($alert->getMeta(), []);
        $this->assertSame($alert->getType(), AlertType::info->value);

        Alert::success()->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getMeta(), []);
        $this->assertSame($alert->getType(), AlertType::success->value);

        Alert::warning()->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getMeta(), []);
        $this->assertSame($alert->getType(), AlertType::warning->value);

        Alert::failure()->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getMeta(), []);
        $this->assertSame($alert->getType(), AlertType::failure->value);
    }

    public function test_alert_works_without_description()
    {
        Alert::info()->title('Title')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), null);
        $this->assertTrue(Alert::exists());
    }

    public function test_alert_works_without_title_and_description()
    {
        Alert::info()->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), Alert::DEFAULT_TITLE);
        $this->assertTrue(Alert::exists());
    }

    public function test_alert_works_with_metadata()
    {
        $meta = [
            'is_active' => true,
            'link' => 'https://example.com'
        ];

        Alert::info()->meta($meta)->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getMeta(), $meta);
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
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'created');

        Alert::model($this->getUpdatedModel())->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'updated');

        Alert::model($this->getDeletedModel())->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'deleted');
    }

    public function test_model_alert_works_for_custom_action()
    {
        Alert::model($this->getCreatedModel())->action('custom_action')->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'custom_action');
    }

    public function test_model_alert_works_for_custom_type()
    {
        Alert::model($this->getCreatedModel())->type('custom_type')->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'custom_type');
        $this->assertSame($alert->getAction(), 'created');
    }

    public function test_model_alert_uses_correct_lang_values()
    {
        Alert::model($this->getCreatedModel())->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Post was created.');
        $this->assertSame($alert->getDescription(), null);

        Alert::model($this->getUpdatedModel())->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Post was updated.');
        $this->assertSame($alert->getDescription(), null);

        Alert::model($this->getDeletedModel())->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Post was deleted.');
        $this->assertSame($alert->getDescription(), null);

        Alert::model($this->getCreatedModel())->action('custom_action')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'alert::messages.model.custom_action.title');
        $this->assertSame($alert->getDescription(), null);
    }

    public function test_entity_alert_works_for_default_action()
    {
        Alert::for('settings')->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'updated');
    }

    public function test_entity_alert_works_for_custom_action()
    {
        Alert::for('settings')->action('custom_action')->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'custom_action');
    }

    public function test_entity_alert_works_for_custom_type()
    {
        Alert::for('settings')->type('custom_type')->title('Title')->description('Description')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'custom_type');
        $this->assertSame($alert->getAction(), 'updated');
    }

    public function test_entity_alert_uses_correct_lang_values()
    {
        Alert::for('settings')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'alert::messages.settings.updated.title');
        $this->assertSame($alert->getDescription(), null);

        Alert::for('settings')->action('custom_action')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'alert::messages.settings.custom_action.title');
        $this->assertSame($alert->getDescription(), null);
    }

    public function test_method_to_array_works()
    {
        Alert::info()->flash();
        $alert = Alert::current();

        $this->assertSame($alert->toArray(), [
            'title' => Alert::DEFAULT_TITLE,
            'description' => null,
            'type' => 'info',
            'action' => null,
            'meta' => [],
        ]);

        Alert::success()->flash();
        $alert = Alert::current();

        $this->assertSame($alert->toArray(), [
            'title' => Alert::DEFAULT_TITLE,
            'description' => null,
            'type' => 'success',
            'action' => null,
            'meta' => [],
        ]);

        $meta = [
            'is_active' => true,
            'link' => 'https://example.com'
        ];

        Alert::info()->title('Title')->description('Description')->action('custom_action')->meta($meta)->flash();
        $alert = Alert::current();

        // title, description and meta
        $this->assertSame($alert->toArray(), [
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'info',
            'action' => 'custom_action',
            'meta' => $meta,
        ]);

        Alert::model($this->getCreatedModel())->flash();
        $alert = Alert::current();

        // model alert
        $this->assertSame($alert->toArray(), [
            'title' => 'Post was created.',
            'description' => null,
            'type' => 'success',
            'action' => 'created',
            'meta' => [],
        ]);

        // entity alert
        Alert::for('settings')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->toArray(), [
            'title' => 'alert::messages.settings.updated.title',
            'description' => null,
            'type' => 'success',
            'action' => 'updated',
            'meta' => [],
        ]);
    }

    public function test_method_array_works()
    {
        $this->assertSame(Alert::array(), []);

        Alert::info()->flash();

        $this->assertSame(Alert::array(), [
            'title' => Alert::DEFAULT_TITLE,
            'description' => null,
            'type' => 'info',
            'action' => null,
            'meta' => [],
        ]);
    }

    public function test_method_to_json_works()
    {
        Alert::info()->flash();
        $alert = Alert::current();

        $this->assertSame($alert->toJson(), json_encode([
            'title' => Alert::DEFAULT_TITLE,
            'description' => null,
            'type' => 'info',
            'action' => null,
            'meta' => [],
        ], JSON_FORCE_OBJECT));

        Alert::success()->flash();
        $alert = Alert::current();

        $this->assertSame($alert->toJson(), json_encode([
            'title' => Alert::DEFAULT_TITLE,
            'description' => null,
            'type' => 'success',
            'action' => null,
            'meta' => [],
        ], JSON_FORCE_OBJECT));

        $meta = [
            'is_active' => true,
            'link' => 'https://example.com'
        ];

        Alert::info()->title('Title')->description('Description')->action('custom_action')->meta($meta)->flash();
        $alert = Alert::current();

        // title, description and meta
        $this->assertSame($alert->toJson(), json_encode([
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'info',
            'action' => 'custom_action',
            'meta' => $meta,
        ], JSON_FORCE_OBJECT));

        Alert::model($this->getCreatedModel())->flash();
        $alert = Alert::current();

        // model alert
        $this->assertSame($alert->toJson(), json_encode([
            'title' => 'Post was created.',
            'description' => null,
            'type' => 'success',
            'action' => 'created',
            'meta' => [],
        ], JSON_FORCE_OBJECT));

        // entity alert
        Alert::for('settings')->flash();
        $alert = Alert::current();

        $this->assertSame($alert->toJson(), json_encode([
            'title' => 'alert::messages.settings.updated.title',
            'description' => null,
            'type' => 'success',
            'action' => 'updated',
            'meta' => [],
        ], JSON_FORCE_OBJECT));
    }

    public function test_method_json_works()
    {
        $this->assertSame(Alert::json(), '{}');

        Alert::info()->flash();

        $this->assertSame(Alert::json(), json_encode([
            'title' => Alert::DEFAULT_TITLE,
            'description' => null,
            'type' => 'info',
            'action' => null,
            'meta' => [],
        ], JSON_FORCE_OBJECT));
    }
}
