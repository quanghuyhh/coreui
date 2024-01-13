<?php

namespace App\Livewire\Admin\Common;

use Livewire\Component;

class FieldError extends Component
{
    public $message = '';
    public function render()
    {
        return view('livewire.admin.common.field-error');
    }
}
