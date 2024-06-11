<?php

namespace Mayank\Alert\View\Components;

use Illuminate\View\Component;

use Mayank\Alert\Alert;

class AlertComponent extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $alert = Alert::current();

        if ($alert != null)
            $alertType = $alert->getType();

        else
            $alertType = 'info';

        return view("alert::components.$alertType");
    }

    public function shouldRender(): bool
    {
        return Alert::exists();
    }
}
