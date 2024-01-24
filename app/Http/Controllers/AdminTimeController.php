<?php

namespace App\Http\Controllers;

class AdminTimeController extends Controller
{
    public function index()
    {
        return view('admin.times.index', [
            'breadcrumbs' => [
                ['name' => '勤怠承認'],
            ],
        ]);
    }
    public function edit()
    {
        return view('admin.times.edit', [
            'breadcrumbs' => [
                ['name' => '勤怠承認', 'url' => route('admin.times.index')],
                ['name' => '勤怠申請詳細'],
            ],
        ]);
    }
}
