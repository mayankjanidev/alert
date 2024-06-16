<?php

namespace Mayank\Alert\Tests;

use Mayank\Alert\ServiceProvider;
use Mayank\Alert\Tests\Models\SampleModelInstances;

use Mayank\Alert\Alert;
use Mayank\Alert\Enums\AlertType;

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
        Alert::info()->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getMeta(), []);
        $this->assertSame($alert->getType(), AlertType::info->value);

        Alert::success()->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getMeta(), []);
        $this->assertSame($alert->getType(), AlertType::success->value);

        Alert::warning()->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getMeta(), []);
        $this->assertSame($alert->getType(), AlertType::warning->value);

        Alert::failure()->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getMeta(), []);
        $this->assertSame($alert->getType(), AlertType::failure->value);
    }

    public function test_alert_works_without_title()
    {
        Alert::info()->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), null);
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertTrue(Alert::exists());
    }

    public function test_alert_works_without_title_and_description()
    {
        Alert::info()->create();
        $alert = Alert::current();

        $this->assertSame($alert->getDescription(), Alert::DEFAULT_DESCRIPTION);
        $this->assertTrue(Alert::exists());
    }

    public function test_alert_works_with_metadata()
    {
        $meta = [
            'is_active' => true,
            'link' => 'https://example.com'
        ];

        Alert::info()->meta($meta)->create();
        $alert = Alert::current();

        $this->assertSame($alert->getMeta(), $meta);
        $this->assertTrue(Alert::exists());
    }

    public function test_custom_alert_type_works()
    {
        Alert::custom('custom')->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'custom');
    }

    public function test_alert_exists_method_works()
    {
        $this->assertFalse(Alert::exists());

        Alert::info()->title('Title')->description('Description')->create();

        $this->assertTrue(Alert::exists());
    }

    public function test_model_alert_works_for_default_actions()
    {
        Alert::model($this->getCreatedModel())->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'created');

        Alert::model($this->getUpdatedModel())->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'updated');

        Alert::model($this->getDeletedModel())->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'deleted');
    }

    public function test_model_alert_works_for_custom_action()
    {
        Alert::model($this->getCreatedModel())->action('custom_action')->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'custom_action');
    }

    public function test_model_alert_works_for_custom_type()
    {
        Alert::model($this->getCreatedModel())->type('custom_type')->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'custom_type');
        $this->assertSame($alert->getAction(), 'created');
    }

    public function test_model_alert_uses_correct_lang_values()
    {
        Alert::model($this->getCreatedModel())->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), null);
        $this->assertSame($alert->getDescription(), 'Post was created.');

        Alert::model($this->getUpdatedModel())->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), null);
        $this->assertSame($alert->getDescription(), 'Post was updated.');

        Alert::model($this->getDeletedModel())->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), null);
        $this->assertSame($alert->getDescription(), 'Post was deleted.');

        Alert::model($this->getCreatedModel())->action('custom_action')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), null);
        $this->assertSame($alert->getDescription(), 'alert::messages.model.custom_action.description');
    }

    public function test_entity_alert_works_for_default_action()
    {
        Alert::for('settings')->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'updated');
    }

    public function test_entity_alert_works_for_custom_action()
    {
        Alert::for('settings')->action('custom_action')->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), AlertType::success->value);
        $this->assertSame($alert->getAction(), 'custom_action');
    }

    public function test_entity_alert_works_for_custom_type()
    {
        Alert::for('settings')->type('custom_type')->title('Title')->description('Description')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), 'Title');
        $this->assertSame($alert->getDescription(), 'Description');
        $this->assertSame($alert->getType(), 'custom_type');
        $this->assertSame($alert->getAction(), 'updated');
    }

    public function test_entity_alert_uses_correct_lang_values()
    {
        Alert::for('settings')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), null);
        $this->assertSame($alert->getDescription(), 'alert::messages.settings.updated.description');

        Alert::for('settings')->action('custom_action')->create();
        $alert = Alert::current();

        $this->assertSame($alert->getTitle(), null);
        $this->assertSame($alert->getDescription(), 'alert::messages.settings.custom_action.description');
    }

    public function test_method_to_array_works()
    {
        Alert::info()->create();
        $alert = Alert::current();

        $this->assertSame($alert->toArray(), [
            'title' => null,
            'description' => Alert::DEFAULT_DESCRIPTION,
            'type' => 'info',
            'action' => null,
            'meta' => [],
        ]);

        Alert::success()->create();
        $alert = Alert::current();

        $this->assertSame($alert->toArray(), [
            'title' => null,
            'description' => Alert::DEFAULT_DESCRIPTION,
            'type' => 'success',
            'action' => null,
            'meta' => [],
        ]);

        $meta = [
            'is_active' => true,
            'link' => 'https://example.com'
        ];

        Alert::info()->title('Title')->description('Description')->action('custom_action')->meta($meta)->create();
        $alert = Alert::current();

        // title, description and meta
        $this->assertSame($alert->toArray(), [
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'info',
            'action' => 'custom_action',
            'meta' => $meta,
        ]);

        Alert::model($this->getCreatedModel())->create();
        $alert = Alert::current();

        // model alert
        $this->assertSame($alert->toArray(), [
            'title' => null,
            'description' => 'Post was created.',
            'type' => 'success',
            'action' => 'created',
            'meta' => [],
        ]);

        // entity alert
        Alert::for('settings')->create();
        $alert = Alert::current();

        $this->assertSame($alert->toArray(), [
            'title' => null,
            'description' => 'alert::messages.settings.updated.description',
            'type' => 'success',
            'action' => 'updated',
            'meta' => [],
        ]);
    }

    public function test_method_array_works()
    {
        $this->assertSame(Alert::array(), []);

        Alert::info()->create();

        $this->assertSame(Alert::array(), [
            'title' => null,
            'description' => Alert::DEFAULT_DESCRIPTION,
            'type' => 'info',
            'action' => null,
            'meta' => [],
        ]);
    }

    public function test_method_to_json_works()
    {
        Alert::info()->create();
        $alert = Alert::current();

        $this->assertSame($alert->toJson(), json_encode([
            'title' => null,
            'description' => Alert::DEFAULT_DESCRIPTION,
            'type' => 'info',
            'action' => null,
            'meta' => [],
        ], JSON_FORCE_OBJECT));

        Alert::success()->create();
        $alert = Alert::current();

        $this->assertSame($alert->toJson(), json_encode([
            'title' => null,
            'description' => Alert::DEFAULT_DESCRIPTION,
            'type' => 'success',
            'action' => null,
            'meta' => [],
        ], JSON_FORCE_OBJECT));

        $meta = [
            'is_active' => true,
            'link' => 'https://example.com'
        ];

        Alert::info()->title('Title')->description('Description')->action('custom_action')->meta($meta)->create();
        $alert = Alert::current();

        // title, description and meta
        $this->assertSame($alert->toJson(), json_encode([
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'info',
            'action' => 'custom_action',
            'meta' => $meta,
        ], JSON_FORCE_OBJECT));

        Alert::model($this->getCreatedModel())->create();
        $alert = Alert::current();

        // model alert
        $this->assertSame($alert->toJson(), json_encode([
            'title' => null,
            'description' => 'Post was created.',
            'type' => 'success',
            'action' => 'created',
            'meta' => [],
        ], JSON_FORCE_OBJECT));

        // entity alert
        Alert::for('settings')->create();
        $alert = Alert::current();

        $this->assertSame($alert->toJson(), json_encode([
            'title' => null,
            'description' => 'alert::messages.settings.updated.description',
            'type' => 'success',
            'action' => 'updated',
            'meta' => [],
        ], JSON_FORCE_OBJECT));
    }

    public function test_method_json_works()
    {
        $this->assertSame(Alert::json(), '{}');

        Alert::info()->create();

        $this->assertSame(Alert::json(), json_encode([
            'title' => null,
            'description' => Alert::DEFAULT_DESCRIPTION,
            'type' => 'info',
            'action' => null,
            'meta' => [],
        ], JSON_FORCE_OBJECT));
    }
}
