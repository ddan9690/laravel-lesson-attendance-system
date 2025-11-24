<?php

namespace App\Http\Controllers\Admin;

use App\Models\Curriculum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LessonController
{
    public function index()
    {
        $curricula = Curriculum::all();

        return view('lessons.index', compact('curricula'));
    
    }
}
