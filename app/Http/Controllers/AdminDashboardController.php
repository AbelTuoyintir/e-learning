<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    public function index(){
        $stuCount = \App\Models\Student::count();
        $averageScore = \App\Models\Quiz::avg('score') ?? 0;
        $courseCount = \App\Models\Course::count();
        $moduleCount = \App\Models\Module::count();
        return view('dashboard');
    }
}
