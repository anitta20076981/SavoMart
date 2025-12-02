<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Toolbar extends Component
{
    public $breadcrumbs;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(array $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.layouts.toolbar');
    }
}
