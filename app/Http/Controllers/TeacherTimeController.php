<?php

namespace App\Http\Controllers;

class TeacherTimeController extends Controller
{
    public function create()
    {
        return view('time.create', [
            'breadcrumbs' => [
                ['name' => '勤怠申請']
            ]
        ]);
    }

    public function edit()
    {
        return view('time.edit', [
            'breadcrumbs' => [
                ['name' => '勤怠申請']
            ]
        ]);
    }
}
