<?php

namespace Mayank\Alert\View\Components;

use Illuminate\View\Component;
use Mayank\Alert\AlertConfig;

class AlertLayoutComponent extends Component
{
    public $icon = null;

    public function __construct(
        public ?string $title,
        public string $description,
        public string $type
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (view()->exists('alert::components.layout')) {
            return view('alert::components.layout');
        } else {
            $theme = AlertConfig::getTheme()->value;
            return view("alert::components.$theme.layout");
        }
    }
}
