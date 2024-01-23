<?php

namespace App\Livewire\Teacher\Time;

use App\Enums\WorkTimeStatus;
use App\Models\WorkTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;

class Application extends Component
{
    public ?int $workTimeId;

    public array $workTimeForEdit = [];

    public ?string $date = null;

    public bool $use_transportation_expense = false;

    public int $transportation_expense_type = 0;

    public ?int $transportation_expense = 0;

    public bool $use_shift = false;

    public ?string $shift_start_time = null;

    public ?string $shift_end_time = null;

    public ?string $shift_break_time = null;

    public bool $use_off_shift = false;

    public $off_shift_qa_time = null;

    public $off_shift_training_time = null;

    public $off_shift_absent_time = null;

    public $off_shift_additional_time = null;

    public ?int $off_shift_break_time_type = 0;

    public $off_shift_blog_url = null;

    public $off_shift_notes = null;

    public ?bool $use_night_work = false;

    public $night_time = null;

    public $training_type = 0;

    public $training_time = null;

    public $note = null;

    public array $needFixs = [];

    public ?string $currentRoute;

    public function mount()
    {
        $this->workTimeId = request('id');
        $this->date = request('date');
        $this->currentRoute = Route::currentRouteName();
        if (! empty($this->workTimeId) || ! empty($this->date)) {
            $this->fetchWorkingTimeData();
        }
    }

    public function render()
    {
        return view('livewire.teacher.time.application');
    }

    public function fetchWorkingTimeData()
    {
        if (empty($this->workTimeId) && empty($this->date)) {
            return;
        }

        if (! empty($this->workTimeId)) {
            $workTime = $this->getWorkingById($this->workTimeId);
        } elseif (! empty($this->date)) {
            $workTime = $this->getWorkingDate($this->date);
        }
        if (empty($workTime)) {
            return;
        }

        $this->date = $workTime->date;

        $errorMsg = $this->generateErrorWhenEdit($workTime->status, $workTime->id, $workTime->date);
        if (! empty($errorMsg)) {
            $this->addError('common', $errorMsg);
        }

        $convertedData = array_merge(
            $workTime->toArray(),
            $this->parseTimeValue($workTime->toArray())
        );
        foreach ($convertedData as $field => $value) {
            if (! isset($this->{$field}) && ! property_exists($this, $field)) {
                continue;
            }
            $this->{$field} = $value;
        }
    }

    public function onSave()
    {
        $this->clearValidation();
        $this->resetErrorBag();
        try {
            $validationData = $this->prepareDataForValidation();
            $rules = [
                'date' => ['required'],
                'status' => [],
                'message' => [],
                'use_transportation_expense' => [],
                'transportation_expense_type' => [],
                'transportation_expense' => ['max:99999', 'numeric'],
                'use_shift' => [],
                'shift_start_time' => [],
                'shift_end_time' => [],
                'shift_break_time' => [],
                'use_off_shift' => [],
                'off_shift_qa_time' => [],
                'off_shift_training_time' => [],
                'off_shift_absent_time' => [],
                'off_shift_additional_time' => [],
                'off_shift_break_time_type' => [],
                'off_shift_blog_url' => ['nullable', 'url'],
                'off_shift_notes' => ['nullable', 'max:4000'],
                'use_night_work' => [],
                'night_time' => [],
                'training_type' => [],
                'training_time' => [],
                'note' => ['nullable', 'max:4000'],
            ];
            $validated = Validator::make($validationData, $rules);

            if ($validated->fails()) {
                $this->setErrorBag($validated->getMessageBag());
                dd($validated->getMessageBag());
                return;
            }

            if (! empty($this->workTimeId)) {
                $workingTime = $this->getWorkingById($this->workTimeId);
            } elseif (! empty($this->date)) {
                $workingTime = $this->getWorkingDate($this->date);
            }

            if (empty($workingTime)) {
                $workingTime = new WorkTime();
            }

            $errorMsg = $this->generateErrorWhenEdit($workingTime->status, $workingTime->id, $workingTime->date);
            if (! empty($errorMsg)) {
                $this->addError('common', $errorMsg);
                dd($errorMsg);
                return;
            }

            $workingTimeData = array_merge(
                $this->convertTimeValue($validationData),
                ['status' => WorkTimeStatus::IN_PROGRESS->value],
                $workingTime->id ? [] : ['user_id' => auth()->id()],
            );
            $workingTime->fill($workingTimeData)->save();

            return redirect()->route('teacher.time.create', ['date' => $workingTime->date])
                ->with('success', trans('The work time has been successfully updated.'));
        } catch (\Exception $exception) {
            $this->addError('common', $exception->getMessage());
            dd($exception);
        }
    }

    public function prepareDataForValidation()
    {
        $data = [
            'date' => $this->date,
            'status' => $this->status ?? $this->workTimeForEdit['status'] ?? WorkTimeStatus::IN_PROGRESS->value,
            'message' => $this->message ?? $this->workTimeForEdit['message'] ?? null,
            'use_transportation_expense' => $this->use_transportation_expense,
            'transportation_expense_type' => $this->transportation_expense_type,
            'transportation_expense' => $this->transportation_expense,
            'use_shift' => $this->use_shift,
            'shift_start_time' => $this->shift_start_time,
            'shift_end_time' => $this->shift_end_time,
            'shift_break_time' => $this->shift_break_time,
            'use_off_shift' => $this->use_off_shift,
            'off_shift_qa_time' => $this->off_shift_qa_time,
            'off_shift_training_time' => $this->off_shift_training_time,
            'off_shift_absent_time' => $this->off_shift_absent_time,
            'off_shift_additional_time' => $this->off_shift_additional_time,
            'off_shift_break_time_type' => $this->off_shift_break_time_type,
            'off_shift_blog_url' => $this->off_shift_blog_url,
            'off_shift_notes' => $this->off_shift_notes,
            'use_night_work' => $this->use_night_work,
            'night_time' => $this->night_time,
            'training_type' => $this->training_type,
            'training_time' => $this->training_time,
            'note' => $this->note,
        ];

        return $data;
    }

    public function toggleCheckbox($property, $isChecked = false)
    {
        $this->{$property} = $isChecked;
    }

    public function changeSelectValue($property, $value = null)
    {
        $this->{$property} = $value;
    }

    public function parseTimeValue($parseData)
    {
        $timeColumns = [
            'shift_start_time',
            'shift_end_time',
            'shift_break_time',
            'off_shift_qa_time',
            'off_shift_training_time',
            'off_shift_absent_time',
            'off_shift_additional_time',
            'night_work_time',
            'night_time',
            'training_time',
        ];
        foreach ($timeColumns as $timeColumn) {
            if (empty($parseData[$timeColumn])) {
                $parseData[$timeColumn] = 0;

                continue;
            }
            $explodes = str_split(Str::padLeft($parseData[$timeColumn], 4, 0));

            $hour = $explodes[0].$explodes[1];
            $minutes = $explodes[2].$explodes[3];
            $parseData[$timeColumn] = sprintf('%s:%s', $hour, $minutes);
        }

        return $parseData;
    }

    public function convertTimeValue($parseData)
    {
        $timeColumns = [
            'shift_start_time',
            'shift_end_time',
            'shift_break_time',
            'off_shift_qa_time',
            'off_shift_training_time',
            'off_shift_absent_time',
            'off_shift_additional_time',
            'night_work_time',
            'night_time',
            'training_time',
        ];
        foreach ($timeColumns as $timeColumn) {
            if (empty($parseData[$timeColumn])) {
                $parseData[$timeColumn] = 0;

                continue;
            }

            $parseData[$timeColumn] = str_replace(':', '', $parseData[$timeColumn]);
        }

        return $parseData;
    }

    protected function getWorkingById($id)
    {
        if (empty($id)) {
            return null;
        }

        return WorkTime::where('user_id', auth()->id())
            ->find($id);
    }

    protected function getWorkingDate($date)
    {
        if (empty($date)) {
            return null;
        }

        return WorkTime::where('user_id', auth()->id())
            ->where('date', $date)
            ->first();
    }

    public function onChangeDate($date)
    {
        $this->resetExcept('currentRoute');
        $this->date = $date;
        $this->fetchWorkingTimeData();
    }

    protected function generateNeedFix($id, $date)
    {
        return sprintf(
            '<a class="alert-link" href="%s">%sの勤怠の修正依頼があります</a>',
            route('teacher.time.edit', ['id' => $id]),
            Carbon::parse($date)->format('Y年m月d')
        );
    }

    public function getCanEditProperty()
    {
        $workingTime = null;
        if (! empty($this->workTimeId)) {
            $workingTime = $this->getWorkingById($this->workTimeId);
        } elseif (! empty($this->date)) {
            $workingTime = $this->getWorkingDate($this->date);
        }
        $isInEditPage = $this->currentRoute === 'teacher.time.edit';
        if (empty($workingTime) && ! $isInEditPage) {
            return true;
        }

        return $isInEditPage && $workingTime->status === WorkTimeStatus::NEED_FIX->value;
    }

    protected function generateErrorWhenEdit($status, $id = null, $date = null)
    {
        return match ($status) {
            WorkTimeStatus::IN_PROGRESS->value => '勤怠申請済です（承認待ち）',
            WorkTimeStatus::NEED_FIX->value => !$this->can_edit && (!empty($id) && ! empty($date))
                ? $this->generateNeedFix($id, $date)
                : null,
            WorkTimeStatus::APPROVED->value => '勤怠申請済です（承認済)',
            default => null
        };
    }
}
