<?php

namespace App\Http\Controllers;

class TeacherController extends Controller
{
    public function index()
    {
        return view('teacher');
    }

    public function application()
    {
        return view('shift.application', [
            'breadcrumbs' => [
                ['name' => 'シフト申請'],
            ],
        ]);
    }
}
