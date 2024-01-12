<?php

namespace App\Livewire\Admin\Common;

use Livewire\Component;

class Alert extends Component
{
    public bool $isSuccess = true;
    public string $message = '';

    public function mount()
    {
        $this->isSuccess = session()->has('success');
        $this->message = session('success') ?? session('error') ?? '';
    }

    public function render()
    {
        return view('livewire.admin.common.alert');
    }
}
