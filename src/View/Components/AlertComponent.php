<?php

namespace Mayank\Alert\View\Components;

use Illuminate\View\Component;

use Mayank\Alert\Alert;

class AlertComponent extends Component
{
    public ?string $title = null;

    public string $description = '';

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

        if (view()->exists("alert::components.$this->type"))
            return view("alert::components.$this->type");

        else
            return view("alert::components.default.$this->type");
    }

    public function shouldRender(): bool
    {
        return Alert::exists();
    }
}
