<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudyPlanController extends Controller
{
    //
    public function index()
    {
        return view('study_plan.index');
    }
}
