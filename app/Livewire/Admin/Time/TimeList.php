<?php

namespace App\Livewire\Admin\Time;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\UserRole;
use App\Models\WorkTime;
use Illuminate\Contracts\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class TimeList extends Component
{

    public ?int $status = 0;

    public $items;

    public array $paginator = [];

    public ?int $page = 1;

    public function mount()
    {
        $this->page = request('page', 1);
        $this->fetchData();
    }

    public function render()
    {
        return view('livewire.admin.time.time-list');
    }

    public function onChangeFilterStatus($status = 0)
    {
        $this->status = $status;
        $this->fetchData();
    }

    public function fetchData()
    {
        $managerRole = auth()->user()->roles()->firstWhere('role', RoleEnum::MANAGER->value);
        $teacherOfSchool = UserRole::query()->where('role', RoleEnum::TEACHER->value)
            ->where('school_id', $managerRole->school_id)
            ->pluck('user_id')
            ->all();
        $query = WorkTime::query()->with('teacher')->whereIn('user_id',$teacherOfSchool);
        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        /** @var Paginator $paginator */
        $paginator = $query->paginate(
            page: $this->page
        );
        $this->paginator = [
            'hasPages' => $paginator->hasPages(),
            'onFirstPage' => $paginator->onFirstPage(),
            'previousPageUrl' => $paginator->previousPageUrl(),
            'hasMorePages' => $paginator->hasMorePages(),
            'nextPageUrl' => $paginator->nextPageUrl(),
            'firstItem' => $paginator->firstItem(),
            'lastItem' => $paginator->lastItem(),
            'total' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'linkCollection' => $paginator->linkCollection()->toArray(),
        ];
        $this->items = $paginator->getCollection()->transform(fn($item) => !empty($item) ? $item->toArray() : [])->all();
    }
}
