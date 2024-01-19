<?php

namespace App\Livewire\Teacher\Time;

use Livewire\Component;

class Application extends Component
{
    public string $date;

    public bool $use_transportation_expense = false;
    public int $transportation_expense_type = 0;




    public function render()
    {
        return view('livewire.teacher.time.application');
    }
}
