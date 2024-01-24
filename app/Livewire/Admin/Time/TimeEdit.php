<?php

namespace App\Livewire\Admin\Time;

use App\Enums\RoleEnum;
use App\Enums\WorkTimeStatus;
use App\Models\UserRole;
use App\Models\WorkTime;
use Illuminate\Support\Str;
use Livewire\Component;

class TimeEdit extends Component
{
    public ?int $workId;

    public array $item = [];

    public ?int $status = 1;
    public ?string $message = '';

    public function mount()
    {
        $this->workId = request()->time ?? null;
        $this->fetchData();
    }

    public function render()
    {
        return view('livewire.admin.time.time-edit');
    }

    public function fetchData()
    {
        $managerRole = auth()->user()->roles()->firstWhere('role', RoleEnum::MANAGER->value);
        $teacherOfSchool = UserRole::query()->where('role', RoleEnum::TEACHER->value)
            ->where('school_id', $managerRole->school_id)
            ->pluck('user_id')
            ->all();
        $workTime = WorkTime::query()->with('teacher')
            ->whereIn('user_id',$teacherOfSchool)
            ->find($this->workId);

        $this->item = $workTime->toArray();
        $this->status = $this->item['status'] ?? WorkTimeStatus::IN_PROGRESS->value;
        $this->message = $this->item['message'] ?? '';
    }

    public function getTrainingTypeProperty()
    {
        return match ($this->item['training_type'] ?? '') {
            0 => '本日の特訓はなし',
            1 => '時間数を入れる',
            default => '本日の特訓はなし',
        };
    }

    public function onSave()
    {
        try {
            $managerRole = auth()->user()->roles()->firstWhere('role', RoleEnum::MANAGER->value);
            $teacherOfSchool = UserRole::query()->where('role', RoleEnum::TEACHER->value)
                ->where('school_id', $managerRole->school_id)
                ->pluck('user_id')
                ->all();
            $workTime = WorkTime::query()->find($this->workId);
            throw_if(
                empty($workTime),
                new \Exception(trans('Work time does not exists'))
            );

            throw_if(
                !in_array($workTime->user_id, $teacherOfSchool),
                new \Exception(trans('You don\'t have permission!'))
            );

            $workTime->fill([
                'status' => $this->status ?? $workTime->status ?? WorkTimeStatus::IN_PROGRESS->value,
                'message' => $this->message ?? ''
            ])->save();
            return redirect()->route('admin.times.index')->with('success', trans('Work time updated!'));
        } catch (\Exception $exception) {
            $this->addError('common', $exception->getMessage());
        }
    }

    public function getOffShiftBreakTimeTypeProperty()
    {
        return match ($this->item['off_shift_break_time_type'] ?? null) {
            0 => '追加事務では休憩時間をとっていない',
            1 => '追加事務には休憩時間を除いた時間を入力した',
            default => '追加事務では休憩時間をとっていない',
        };
    }

    public function parseTimeValue($value = 0)
    {
        if (empty($value)) {
            return null;
        }

        $explodes = str_split(Str::padLeft($value, 4, 0));
        $hour = $explodes[0].$explodes[1];
        $minutes = $explodes[2].$explodes[3];
        return sprintf('%s:%s', $hour, $minutes);
    }

    public function changeStatusValue($value = 1)
    {
        $this->status = $value;
    }
}
