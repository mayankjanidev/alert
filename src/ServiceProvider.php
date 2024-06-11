<?php

namespace Mayank\Alert;

use Illuminate\Support\Facades\Blade;
use Mayank\Alert\View\Components\AlertLayoutComponent;
use Mayank\Alert\View\Components\AlertComponent;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'alert');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/alert'),
        ]);

        Blade::component('alert-layout', AlertLayoutComponent::class);
        Blade::component('alert', AlertComponent::class);
    }
}
