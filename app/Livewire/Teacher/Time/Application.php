<?php

namespace App\Livewire\Teacher\Time;

use App\Enums\WorkTimeStatus;
use App\Models\WorkTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Application extends Component
{
    public int $workTimeId;

    public array $workTimeForEdit = [];

    public ?string $date = null;

    public bool $use_transportation_expense = false;
    public int $transportation_expense_type = 0;
    public ?int $transportation_expense = 0;

    public bool $use_shift = false;
    public $shift_start_time = null;
    public $shift_end_time = null;
    public $shift_break_time = null;

    public bool $use_off_shift = false;
    public $off_shift_qa_time = null;
    public $off_shift_training_time = null;
    public $off_shift_absent_time = null;
    public $off_shift_additional_time = null;
    public ?int $off_shift_break_time_type = null;
    public $off_shift_blog_url = null;
    public $off_shift_notes = null;

    public $use_night_work = null;
    public $night_time = null;

    public $training_type = null;
    public $training_time = null;
    public $note = null;

    public function render()
    {
        return view('livewire.teacher.time.application');
    }

    public function onSave()
    {
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
                return;
            }

            $workingTime = new WorkTime();
            $workingTime->fill($validationData)->save();

            session()->flash('success', 'Your working time updated');
            return;
        } catch (\Exception $exception) {
            $this->addError('common', $exception->getMessage());
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
            'training_time',
        ];
        foreach ($timeColumns as $timeColumn) {
            if (empty($parseData[$timeColumn])) {
                $parseData[$timeColumn] = 0;
                continue;
            }
            $mang_ky_tu = str_split($parseData[$timeColumn]);

            $gio = $mang_ky_tu[0] . $mang_ky_tu[1];
            $phut = $mang_ky_tu[2] . $mang_ky_tu[3];
            $parseData[$timeColumn] = sprintf("%s:%s", $gio, $phut);
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
            'training_time',
        ];
        foreach ($timeColumns as $timeColumn) {
            if (empty($parseData[$timeColumn])) {
                $parseData[$timeColumn] = 0;
                continue;
            }

            $parseData[$timeColumn] = str_replace(":", '', $parseData[$timeColumn]);
        }

        return $parseData;
    }
}
