<?php

namespace App\Http\Controllers;

use App\Models\Shift;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('shifts.index', [
            'shifts' => $shifts
        ]);
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function edit(int $shiftId)
    {
        return view('shifts.edit', ['id' => $shiftId]);
    }


    public function show()
    {
        return view('shifts.show');
    }
}
