<?php

namespace Mayank\Alert\View\Components;

use Illuminate\View\Component;

use Mayank\Alert\Alert;

class AlertComponent extends Component
{
    public string $title = '';

    public ?string $description = null;

    public string $type = 'success';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $alert = Alert::current();

        if ($alert != null) {
            $this->title = $alert->getTitle();
            $this->description = $alert->getDescription();
            $this->type = $alert->getType();
        }

        return view("alert::components.$this->type");
    }

    public function shouldRender(): bool
    {
        return Alert::exists();
    }
}
