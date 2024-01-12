<?php

namespace App\Livewire\Admin\Shifts;

use App\Enums\RoleEnum;
use App\Models\Shift;
use Carbon\Carbon;
use Livewire\Component;

class CreateForm extends Component
{
    public $month;

    public array $availableDates = [];

    public array $availableTimes = [];

    public $dateToAdd;
    public $timeStartToAdd;
    public $timeEndToAdd;

    public $shiftSlots = [];

    public array $checkedAllDays = [];
    public array $checkedAllTimes = [];

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.admin.shifts.create-form');
    }

    public function addDate()
    {
        if (!empty($this->availableDates[$this->dateToAdd])) {
            $this->addError('date_add', '重複した日付があります');
        }

        $this->availableDates[$this->dateToAdd] = $this->dateToAdd;
        $this->dispatch('hideModal');
    }

    public function addTime()
    {
        $key = sprintf("%s-%s", $this->timeStartToAdd, $this->timeEndToAdd);
        if (!empty($this->availableTimes[$key])) {
            $this->addError('time_add', '重複した時間があります');
        }

        $this->availableTimes[$key] = [
            'start' => $this->timeStartToAdd,
            'end' => $this->timeEndToAdd,
        ];
        $this->dispatch('hideModal');
    }

    public function saveShift()
    {
        $managerRole = auth()->user()->roles()->firstWhere('role', RoleEnum::MANAGER->value);
        if (empty($managerRole)) {
            $this->addError('month', "You don't have permission.");
            return;
        }

        $currentMonth = Carbon::createFromFormat('Y-m', $this->month)->format('Y-m-d');
        if (Shift::firstWhere('month',$currentMonth)) {
            $this->addError('month', 'Current month existed');
            return;
        }

        $shift = new Shift();
        $shift->month = Carbon::createFromFormat('Y-m', $this->month);
        $times = [];
        foreach ($this->availableTimes as $duration) {
            $times[] = $duration;
        }

        $days = [];
        foreach ($this->availableDates as $date) {
            $days[] = [
                'day' => $date,
                'note' => ''
            ];
        }

        $shiftSlots = $this->reGenerateSlots();

        $shift->data = [
            'times' => $times,
            'days' => $days,
            'shift-slots' => $shiftSlots,
            'teachers' => []
        ];
        $shift->school_id = $managerRole->school_id;

        $shift->save();
        return redirect()->route('admin.shifts.index');
    }

    public function checkAllDate($date, $isChecked)
    {
        if (!$isChecked) {
            unset($this->shiftSlots[$date]);
        } else {
            foreach ($this->availableTimes as $key => $time) {
                $slots[$key] = 1;
            }
            $this->shiftSlots[$date] = $slots;
        }
    }

    public function handleSlot($date, $slotIndex)
    {
        $slots = $this->shiftSlots[$date] ?? [];
        if (empty($slots)) {
            foreach ($this->availableTimes as $key => $time) {
                $slots[$key] = 0;
            }
        }
        $slots[$slotIndex] = empty($slots[$slotIndex]) ? 1 : 0;
        $this->shiftSlots[$date] = $slots;
    }

    public function reGenerateSlots(): array
    {
        $shiftSlots = [];
        foreach ($this->availableDates as $date) {
            $slots = $this->shiftSlots[$date] ?? [];
            if (empty($slots)) {
                foreach ($this->availableTimes as $key => $time) {
                    $slots[$key] = 0;
                }
            } else {
                foreach ($this->availableTimes as $key => $time) {
                    $slots[$key] = !empty($slots[$key]) ? $slots[$key] : 0;
                }
            }
            $shiftSlots[$date] = $slots;
        }

        return $shiftSlots;
    }
}
