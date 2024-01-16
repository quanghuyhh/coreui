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

    public array $availableTeachers = [];
    public array $shiftTeachers = [];

    public bool $isPublic = false;

    public function mount()
    {
        $shift = Shift::find($this->shiftId);
        $this->shift = $shift->toArray();
        $this->shiftDates = $this->shift['data']['days'] ?? [];
        $this->isPublic = $this->shift['status'] == ShiftStatusEnum::COMPLETED->value;
        $this->availableTeachers = $this->getAppliedTeachers($shift->appliers->toArray());
        $this->shiftTeachers = $this->shift['data']['teachers'] ?? [];
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
        $shiftData['teachers'] = $this->generateShiftTeachers();
        $shift->data = $shiftData;

        $shift->status = $this->isPublic ? ShiftStatusEnum::COMPLETED->value : ShiftStatusEnum::IN_PROGRESS->value;

        $shift->save();
        session()->flash('Your shift updated!');
        $this->dispatch('reload');
    }

    public function generateShiftTeachers()
    {
        $applied = [];
        foreach($this->shift['data']['days'] as $index => $day) {
            $slotDay = $day['day'];
            $shiftSlots = $this->shift['data']['shift-slots'][$slotDay] ?? [];
            foreach($shiftSlots as $slotTime => $status) {
                $applied[$slotDay][$slotTime] = $this->shiftTeachers[$slotDay][$slotTime] ?? null;
            }
        }

        return $applied;
    }

    public function getAppliedTeachers(array $teachers)
    {
        return collect($teachers)->filter(function ($teacher) {
           $pivot = $teacher['pivot']['data'] ?? null;
           if (empty($pivot)) {
               return false;
           }

           $data = json_decode($pivot, true);
           $slots = $data['available-work-slots'] ?? [];
           if (empty($slots)) {
               return false;
           }
           return collect($slots)->filter(function ($slot) {
               return collect($slot)->filter(fn($apply) => !empty($apply))->count();
           })->count() > 0;
        })->all();
    }
}
