<?php

namespace App\Livewire\Teacher\Shift;

use App\Models\Shift;
use App\Models\ShiftApplication;
use Livewire\Component;

class Application extends Component
{
    public ?int $selectedShift = null;

    public array $shifts = [];

    public array $applied = [];

    public array $appliedShift = [];

    public function mount()
    {
        $this->shifts = Shift::published()->get()->toArray();
        if (!empty($this->shifts)) {
            $this->selectedShift = request()->get('shift_id') ?? $this->shifts[0]['id'] ?? null;
        }

        $this->fetchAppliedShift();
    }

    public function render()
    {
        return view('livewire.teacher.shift.application');
    }

    public function onChangeMonth($month = null)
    {
        $this->selectedShift = $month;
        $this->applied = [];
        $this->dispatch('uncheck');
        $this->fetchAppliedShift();
    }

    public function onCheckboxChecked($date, $time, $checked)
    {
        $appliedDate = $this->applied[$date] ?? [];
        if (empty($appliedDate)) {
            $appliedDate = $this->generateDefaultApplied($this->selectedShift, $date);
        }
        $appliedDate[$time] = $checked ? 1 : 0;
        $this->applied[$date] = $appliedDate;
    }

    public function generateDefaultApplied($selectedShift, $date)
    {
        $shift = collect($this->shifts)->firstWhere('id', $selectedShift);
        $shiftSlots = $shift['data']['shift-slots'][$date] ?? [];
        $result = [];
        foreach ($shiftSlots as $slotTime => $slotStatus) {
            $result[$slotTime] = 0;
        }
        return $result;
    }

    public function onSave()
    {
        try {
            if (empty($this->selectedShift)) {
                throw new \Exception(trans('Please select a valid shift'));
            }

            $shiftApply = $this->getCurrentShift();
            if (empty($shiftApply)) {
                $shiftApply = new ShiftApplication();
            }
            $shiftApply->fill([
                'shift_id' => $this->selectedShift,
                'user_id' => auth()->user()->id,
                'data' => [
                    'available-work-slots' => $this->applied
                ]
            ])->save();

//            session()->flash('success', trans('Your shift application updated!'));
//            $this->dispatch('reload');
            return redirect()->route('teacher.shift.application', ['shift_id' => $this->selectedShift])->with('success', trans('Your shift application updated!'));
        } catch (\Exception $exception) {
            $this->addError('common', $exception->getMessage());
        }
    }

    public function fetchAppliedShift()
    {
        $appliedShift = $this->getCurrentShift();
        if (empty($appliedShift)) {
            return;
        }

        $this->selectedShift = $appliedShift->shift_id;
        $this->appliedShift = $appliedShift->toArray();
        $this->applied = $appliedShift->data['available-work-slots'] ?? [];
    }

    public function getCurrentShift()
    {
        return ShiftApplication::where('user_id', auth()->user()->id)
            ->where('shift_id', $this->selectedShift)
            ->first();
    }
}
