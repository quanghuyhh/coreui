<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class Sidebar extends Component
{
    public bool $isManager = false;

    public function mount()
    {
        $this->isManager = auth()->check() && auth()->user()->isManager();
    }

    public function render()
    {
        return view('livewire.layout.sidebar');
    }
}
