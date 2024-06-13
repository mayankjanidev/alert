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
            __DIR__ . '/../resources/views/components/default' => resource_path('views/vendor/alert/components'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../resources/views/components/tailwind' => resource_path('views/vendor/alert/components'),
        ], 'tailwind');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'alert');
        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/alert'),
        ], 'lang');

        Blade::component('alert-layout', AlertLayoutComponent::class);
        Blade::component('alert', AlertComponent::class);
    }
}
