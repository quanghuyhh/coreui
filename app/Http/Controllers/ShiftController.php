<?php

namespace App\Http\Controllers;

use App\Models\Shift;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();

        return view('shifts.index', [
            'shifts' => $shifts,
            'breadcrumbs' => [
                ['name' => 'シフト管理'],
            ],
        ]);
    }

    public function create()
    {
        return view('shifts.create', [
            'breadcrumbs' => [
                ['name' => 'シフト管理', 'url' => route('admin.shifts.index')],
                ['name' => '時間枠編集'],
            ],
        ]);
    }

    public function edit(int $shiftId)
    {
        return view('shifts.edit', [
            'id' => $shiftId,
            'breadcrumbs' => [
                ['name' => 'シフト管理', 'url' => route('admin.shifts.index')],
                ['name' => '時間枠編集'],
            ],
        ]);
    }

    public function show()
    {
        return view('shifts.show');
    }

    public function editShift(int $shiftId)
    {
        try {
            $shift = Shift::findOrFail($shiftId);

            return view('shifts.edit-shift', [
                'id' => $shiftId, 'shift' => $shift,
                'breadcrumbs' => [
                    ['name' => 'シフト管理', 'url' => route('admin.shifts.index')],
                    ['name' => sprintf('シフト作成 (%s)', $shift->month->format('Y年m月'))],
                ],
            ]);
        } catch (\Exception $exception) {
            return redirect('/');
        }
    }
}
