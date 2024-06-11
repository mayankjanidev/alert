<?php

namespace Mayank\Alert\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

use Mayank\Alert\ServiceProvider;
use Mayank\Alert\Tests\Models\SampleModelInstances;
use Mayank\Alert\Alert;
use Mayank\Alert\View\Components\AlertComponent;
use Mayank\Alert\View\Components\AlertLayoutComponent;

class AlertComponentTest extends \Orchestra\Testbench\TestCase
{
    use InteractsWithViews, SampleModelInstances;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function test_alert_component_renders()
    {
        Alert::info()->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');

        Alert::success()->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');

        Alert::warning()->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');

        Alert::failure()->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');
    }

    public function test_alert_component_renders_without_description()
    {
        Alert::info()->title('Title')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
    }

    public function test_alert_component_does_not_render_without_alert_message()
    {
        $component = $this
            ->component(
                AlertComponent::class,
            );

        $this->assertFalse($component->shouldRender());

        $layoutComponent = $this
            ->component(
                AlertLayoutComponent::class,
            );

        $this->assertFalse($layoutComponent->shouldRender());
    }

    public function test_model_alert_component_renders()
    {
        Alert::model($this->getCreatedModel())->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');
    }

    public function test_model_alert_uses_correct_lang_values()
    {
        Alert::model($this->getCreatedModel())->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Post Created');
        $component->assertSee('Post was successfully created.');

        Alert::model($this->getUpdatedModel())->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Post Updated');
        $component->assertSee('Post was successfully updated.');

        Alert::model($this->getDeletedModel())->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Post Deleted');
        $component->assertSee('Post was successfully deleted.');
    }

    public function test_model_alert_uses_correct_lang_values_for_custom_action()
    {
        Alert::model($this->getCreatedModel())->action('custom_action')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('alert::messages.model.custom_action.title');
        $component->assertSee('alert::messages.model.custom_action.description');
    }

    public function test_entity_alert_component_renders()
    {
        Alert::for('settings')->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');
    }

    public function test_entity_alert_uses_correct_lang_values()
    {
        Alert::for('settings')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('alert::messages.settings.updated.title');
        $component->assertSee('alert::messages.settings.updated.description');
    }

    public function test_entity_alert_uses_correct_lang_values_for_custom_action()
    {
        Alert::for('settings')->action('custom_action')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('alert::messages.settings.custom_action.title');
        $component->assertSee('alert::messages.settings.custom_action.description');
    }
}
