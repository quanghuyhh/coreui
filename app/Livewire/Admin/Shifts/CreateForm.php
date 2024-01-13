<?php

namespace App\Livewire\Admin\Shifts;

use App\Enums\RoleEnum;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CreateForm extends Component
{
    public ?int $shiftId = null;
    public array $shift = [];

    public $month;

    public array $availableDates = [];

    public array $availableTimes = [];

    // date to add
    public $dateToAdd;

    public $dateToEdit;
    public $dateToUpdate;
    public $timeStartToAdd;
    public $timeEndToAdd;

    public $timeToEdit = '';
    public $timeStartToUpdate;
    public $timeEndToUpdate;

    public $shiftSlots = [];

    public array $checkedAllDays = [];
    public array $checkedAllTimes = [];

    public function mount()
    {
        if (!empty($this->shiftId)) {
            $this->fetchShift();
        }
    }

    public function render()
    {
        return view('livewire.admin.shifts.create-form');
    }

    public function fetchShift()
    {
        $shift = Shift::find($this->shiftId);
        $this->shift = $shift->toArray();

        $this->month = $shift->month->format('Y-m');
        $this->availableDates = collect(array_keys($shift->data['shift-slots']))
            ->mapWithKeys(fn($date) => [$date => $date])
            ->all();
        $this->availableTimes = collect($shift->data['times'])->mapWithKeys(function ($range) {
            $key = sprintf("%s-%s", $range['start'], $range['end']);
            return [$key => $range];
        })->all();
        $this->shiftSlots = $shift->data['shift-slots'];

        // check all row
        foreach ($this->shiftSlots as $date => $slots) {
            if (collect($slots)->filter(fn($checked) => !empty($checked))->count() !== count($slots)) {
                continue;
            }

            $this->checkedAllDays[$date] = $date;
            $this->onCheckAllRow($date, true);
        }
    }

    public function onAddDate()
    {
        try {
            throw_if(
                !empty($this->availableDates[$this->dateToAdd]),
                new \Exception('重複した日付があります')
            );

            $this->availableDates[$this->dateToAdd] = $this->dateToAdd;
            foreach ($this->checkedAllTimes as $timeChecked => $status) {
                if (empty($status)) {
                    continue;
                }
                $this->shiftSlots[$this->dateToAdd][$timeChecked] = 1;
            }
            $this->hideModal();
        } catch (\Exception $exception) {
            $this->addError('date_add', $exception->getMessage());
            $this->showModal('#modal-add-date');
        }
    }

    public function onAddTime()
    {
        try {
            $sortedTime = $this->sortTimeRange($this->timeStartToAdd, $this->timeEndToAdd);
            $timeRange = sprintf("%s-%s", $sortedTime[0], $sortedTime[1]);
            $validated = Validator::make(
                ['timeStartToAdd' => $sortedTime[0], 'timeEndToAdd' => $sortedTime[1]],
                ['timeStartToAdd' => 'required', 'timeEndToAdd' => 'required'],
            );

            if ($validated->fails()) {
                $this->setErrorBag($validated->getMessageBag());
                $this->showModal('#modal-add-time');
                return;
            }

            throw_if(
                $this->checkIsValidTime($sortedTime[0], $sortedTime[1]),
                new \Exception('重複した時間があります')
            );

            $this->availableTimes[$timeRange] = [
                'start' => $sortedTime[0],
                'end' => $sortedTime[1],
            ];
            $this->hideModal();
            foreach ($this->checkedAllDays as $dayChecked => $status) {
                if (empty($status)) {
                    continue;
                }
                $this->shiftSlots[$dayChecked][$timeRange] = 1;
            }
        } catch (\Exception $exception) {
            $this->addError('timeStartToAdd', $exception->getMessage());
            $this->showModal('#modal-add-time');
        }
    }

    public function openEditTime($timeKey)
    {
        $this->timeToEdit = $timeKey;
        $timeRange = explode('-', $timeKey);
        $this->timeStartToUpdate = $timeRange[0];
        $this->timeEndToUpdate = $timeRange[1];
        $this->showModal('#modal-update-time');
    }

    public function onUpdateTime()
    {
        try {
            $sortedTime = $this->sortTimeRange($this->timeStartToUpdate, $this->timeEndToUpdate);
            $timeRange = sprintf("%s-%s", $sortedTime[0], $sortedTime[1]);
            $validated = Validator::make(
                ['timeStartToUpdate' => $sortedTime[0], 'timeEndToUpdate' => $sortedTime[1]],
                ['timeStartToUpdate' => 'required', 'timeEndToUpdate' => 'required'],
            );

            if ($validated->fails()) {
                $this->setErrorBag($validated->getMessageBag());
                $this->showModal('#modal-update-time');
                return;
            }

            throw_if(
                $this->checkIsValidTime($sortedTime[0], $sortedTime[1], $this->timeToEdit),
                new \Exception('重複した時間があります')
            );

            // process if change
            if ($timeRange !== $this->timeToEdit) {
                // remove check all
                $this->checkedAllTimes[$timeRange] = $this->checkedAllTimes[$this->timeToEdit] ?? false;
                unset($this->checkedAllTimes[$this->timeToEdit]);

                // add to available time
                $this->availableTimes[$timeRange] = [
                    'start' => $sortedTime[0],
                    'end' => $sortedTime[1],
                ];
                unset($this->availableTimes[$this->timeToEdit]);

                // update check item
                foreach ($this->availableDates as $date) {
                    $this->shiftSlots[$date][$timeRange] = $this->shiftSlots[$date][$this->timeToEdit] ?? 0;
                    unset($this->shiftSlots[$date][$this->timeToEdit]);
                }
            }

            $this->hideModal();

        } catch (\Exception $exception) {
            $this->addError('timeStartToUpdate', $exception->getMessage());
            $this->showModal('#modal-update-time');
        }
    }

    public function openEditDate($date)
    {
        $this->dateToEdit = $date;
        $this->dateToUpdate = $date;
        $this->showModal('#modal-update-date');
    }

    public function onUpdateDate()
    {
        try {
            throw_if(
                !empty($this->availableDates[$this->dateToUpdate]),
                new \Exception('重複した日付があります')
            );

            $this->availableDates[$this->dateToUpdate] = $this->dateToUpdate;
            unset($this->availableDates[$this->dateToEdit]);

            $this->shiftSlots[$this->dateToUpdate] = $this->shiftSlots[$this->dateToEdit] ?? [];
            unset($this->shiftSlots[$this->dateToEdit]);

            $this->checkAllRow($this->dateToUpdate);
            $this->hideModal();
        } catch (\Exception $exception) {
            $this->addError('date_update', $exception->getMessage());
            $this->showModal('#modal-update-date');
        }
    }

    public function onCheckAllRow($date, $isChecked)
    {
        foreach ($this->availableTimes as $timeRange => $time) {
            $this->shiftSlots[$date][$timeRange] = $isChecked ? 1 : 0;
            $this->checkAllCol($timeRange);
        }
        if (!$isChecked) {
            unset($this->checkedAllDays[$date]);
        } else {
            $this->checkedAllDays[$date] = true;
        }
        $this->checkAllRow($date);
        $this->dispatch('forceCheckedRow', date: $date, checked: $isChecked);
    }

    public function onCheckAllCol($timeRange, $isChecked)
    {
        foreach ($this->availableDates as $date) {
            $this->shiftSlots[$date][$timeRange] = $isChecked ? 1 : 0;
            $this->checkAllRow($date);
        }

        if (!$isChecked) {
            unset($this->checkedAllTimes[$timeRange]);
        } else {
            $this->checkedAllTimes[$timeRange] = true;
        }
        $this->dispatch('forceCheckedCol', time: $timeRange, checked: $isChecked);
        $this->checkAllCol($timeRange);
    }

    public function onClickSlot($date, $slotIndex)
    {
        $slots = $this->shiftSlots[$date] ?? [];
        if (empty($slots)) {
            foreach ($this->availableTimes as $key => $time) {
                $slots[$key] = 0;
            }
        }
        $slots[$slotIndex] = empty($slots[$slotIndex]) ? 1 : 0;
        $this->shiftSlots[$date] = $slots;
        $this->checkAllRow($date);
        $this->checkAllCol($slotIndex);
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

    public function hideModal()
    {
        $this->dispatch('hideModal');
    }

    public function showModal($target)
    {
        $this->dispatch('showModal', target: $target);
    }

    public function getSlotResultProperty()
    {
        $result = [];
        foreach ($this->availableDates as $date) {
            // create row
            if (empty($result[$date])) {
                $result[$date] = [];
            }

            foreach ($this->availableTimes as $rangeKey => $rangeValues) {
                $result[$date][$rangeKey] = (
                    !empty($this->checkedAllDays[$date]) ||
                    !empty($this->checkedAllTimes[$rangeKey]) ||
                    !empty($this->shiftSlots[$date][$rangeKey])
                ) ? 1 : 0;
            }
        }

        return $result;
    }

    public function checkAllRow($rowDate)
    {
        $allRowChecked = collect($this->shiftSlots[$rowDate] ?? [])
            ->filter(fn($checked) => !empty($checked))
            ->count();
        if ($allRowChecked === count($this->availableTimes)) {
            $this->checkedAllDays[$rowDate] = true;
            $this->dispatch('markCheckAllRow', date: $rowDate, checked: true);
        } else {
            unset($this->checkedAllDays[$rowDate]);
            $this->dispatch('markCheckAllRow', date: $rowDate, checked: false);
        }
    }

    public function checkAllCol($timeRange)
    {
        $allColChecked = collect($this->shiftSlots)
            ->filter(fn($rowSlot) => !empty($rowSlot[$timeRange]))
            ->count();
        if ($allColChecked === count($this->availableDates)) {
            $this->checkedAllTimes[$timeRange] = true;
            $this->dispatch('markCheckAllCol', time: $timeRange, checked: true);
        } else {
            unset($this->checkedAllTimes[$timeRange]);
            $this->dispatch('markCheckAllCol', time: $timeRange, checked: false);
        }
    }

    public function getSortedTimeProperty()
    {
        return collect($this->availableTimes)->sortBy(function ($timeRange) {
            return Carbon::createFromFormat(
                "Y-m-d H:m:s",
                sprintf("%s %s:00", Carbon::today()->format('Y-m-d'), $timeRange['start'])
            )->timestamp;
        })->values()->all();
    }

    public function getSortedDateProperty()
    {
        return collect($this->availableDates)->sortBy(function ($date) {
            return Carbon::createFromFormat(
                "Y-m-d",
                $date
            )->startOfDay()->timestamp;
        })->values()->all();
    }

    public function checkIsValidTime($start, $end, $ignoreTimeRange = null)
    {
        $sortedTimestamp = $this->sortTimeRange($start, $end);
        return collect($this->availableTimes)->filter(function ($timeRange, $key) use ($sortedTimestamp, $ignoreTimeRange) {
            if ($key === $ignoreTimeRange && !empty($ignoreTimeRange)) {
                return false;
            }
            $_startTime = Carbon::parse($timeRange['start']);
            $_endTime = Carbon::parse($timeRange['end']);
            return Carbon::parse($sortedTimestamp[0])->between($_startTime, $_endTime, false) ||
                Carbon::parse($sortedTimestamp[1])->between($_startTime, $_endTime, false) ||
                $_startTime->between(Carbon::parse($sortedTimestamp[0]), Carbon::parse($sortedTimestamp[1]), false) ||
                $_endTime->between(Carbon::parse($sortedTimestamp[0]), Carbon::parse($sortedTimestamp[1]), false);
        })->count();
    }

    public function sortTimeRange($start, $end): array
    {
        $result = [$start, $end];
        usort($result, function ($a, $b) {
            $timeA = strtotime($a);
            $timeB = strtotime($b);

            return $timeA - $timeB;
        });
        return $result;
    }

    public function saveShift()
    {
        if (empty($this->availableTimes) || empty($this->availableDates)) {
            $this->addError('common', 'Please add at least day/time');
            return;
        }

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

    public function updateShift()
    {
        if (empty($this->availableTimes) || empty($this->availableDates)) {
            session()->flash('error', 'Please add at least day/time');
            return;
        }

        $managerRole = auth()->user()->roles()->firstWhere('role', RoleEnum::MANAGER->value);
        if (empty($managerRole)) {
            $this->addError('month', "You don't have permission.");
            return;
        }

        $currentMonth = Carbon::createFromFormat('Y-m', $this->month)->format('Y-m-d');
        $checkMonth = Shift::query()->whereNotIn('id', [$this->shiftId])->firstWhere('month',$currentMonth);
        if ($checkMonth) {
            $this->addError('month', 'Current month existed');
            return;
        }

        $shift = Shift::find($this->shiftId);
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
}
