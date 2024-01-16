<?php

namespace App\Livewire\Admin\Shifts;

use App\Enums\ShiftStatusEnum;
use App\Models\Shift;
use Livewire\Component;

class EditShiftForm extends Component
{
    public int $shiftId;
    public array $shift = [];
    public array $shiftDates = [];

    public bool $isPublic = false;

    public function mount()
    {
        $this->shift = Shift::find($this->shiftId)->toArray();
        $this->shiftDates = $this->shift['data']['days'] ?? [];
        $this->isPublic = $this->shift['status'] == ShiftStatusEnum::COMPLETED->value;
    }

    public function render()
    {
        return view('livewire.admin.shifts.edit-shift-form');
    }

    public function onSave()
    {
        $shift = Shift::find($this->shiftId);
        $shiftData = $shift->data;
        $shiftData['days'] = $this->shiftDates;
        $shift->data = $shiftData;

        $shift->status = $this->isPublic ? ShiftStatusEnum::COMPLETED->value : ShiftStatusEnum::IN_PROGRESS->value;

        $shift->save();
        session()->flash('Your shift updated!');
        $this->dispatch('reload');
    }
}
