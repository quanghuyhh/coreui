<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route(auth()->user()->isManager() ? config('role.manager') : config('role.teacher'));
    }
}
