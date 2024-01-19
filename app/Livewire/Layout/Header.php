<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class Header extends Component
{
    public array $breadcrumbs = [];

    public function render()
    {
        return view('livewire.layout.header');
    }
}
