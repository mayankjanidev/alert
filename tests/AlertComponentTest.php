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

    public function test_alert_component_renders_without_title_and_description()
    {
        Alert::info()->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee(Alert::DEFAULT_DESCRIPTION);
    }

    public function test_alert_component_does_not_render_without_alert_message()
    {
        $component = $this
            ->component(
                AlertComponent::class,
            );

        $this->assertFalse($component->shouldRender());
    }

    public function test_model_alert_component_renders_for_default_actions()
    {
        Alert::model($this->getCreatedModel())->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');

        Alert::model($this->getUpdatedModel())->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');

        Alert::model($this->getDeletedModel())->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');
    }

    public function test_model_alert_component_renders_for_custom_action()
    {
        Alert::model($this->getCreatedModel())->action('custom_action')->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');
    }

    public function test_model_alert_component_uses_correct_lang_values()
    {
        Alert::model($this->getCreatedModel())->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Post was created.');
        $component->assertDontSee('alert::messages.model.created.title');

        Alert::model($this->getUpdatedModel())->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Post was updated.');
        $component->assertDontSee('alert::messages.model.updated.title');

        Alert::model($this->getDeletedModel())->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Post was deleted.');
        $component->assertDontSee('alert::messages.model.deleted.title');

        Alert::model($this->getCreatedModel())->action('custom_action')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('alert::messages.model.custom_action.description');
        $component->assertDontSee('alert::messages.model.custom_action.title');
    }

    public function test_entity_alert_component_renders_for_default_action()
    {
        Alert::for('settings')->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');
    }

    public function test_entity_alert_component_renders_for_custom_action()
    {
        Alert::for('settings')->action('custom_action')->title('Title')->description('Description')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('Title');
        $component->assertSee('Description');
    }

    public function test_entity_alert_component_uses_correct_lang_values()
    {
        Alert::for('settings')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('alert::messages.settings.updated.description');
        $component->assertDontSee('alert::messages.settings.updated.title');

        Alert::for('settings')->action('custom_action')->flash();

        $component = $this
            ->component(
                AlertComponent::class,
            );

        $component->assertSee('alert::messages.settings.custom_action.description');
        $component->assertDontSee('alert::messages.settings.custom_action.title');
    }
}
