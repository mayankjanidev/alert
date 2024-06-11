<?php

namespace Mayank\Alert\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

use Mayank\Alert\ServiceProvider;
use Mayank\Alert\Alert;
use Mayank\Alert\View\Components\AlertComponent;
use Mayank\Alert\View\Components\AlertLayoutComponent;

class AlertComponentTest extends \Orchestra\Testbench\TestCase
{
    use InteractsWithViews;

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
}
