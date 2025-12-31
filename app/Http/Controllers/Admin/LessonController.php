<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Curriculum;
use Illuminate\Http\Request;

class LessonController
{
   public function index()
{
   
    $curricula = Curriculum::with(['lessons' => function ($query) {
        $query->orderBy('start_time', 'asc');
    }])->get();

 
    $curriculaLessons = $curricula->keyBy('name')->map(function ($curriculum) {
        return $curriculum->lessons;
    });

    return view('admin.lessons.index', compact('curriculaLessons'));
}


    public function create()
    {
        $curricula = Curriculum::all();
        return view('admin.lessons.create', compact('curricula'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'curriculum_id' => 'required|exists:curricula,id',
        ]);

        Lesson::create($data);
        return redirect()->route('lessons.index')->with('success', 'Lesson created successfully');
    }




    public function edit(Lesson $lesson)
    {
        // Get all curricula for the dropdown
        $curriculaList = Curriculum::all();

        return view('admin.lessons.edit', compact('lesson', 'curriculaList'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'curriculum_id' => 'required|exists:curricula,id',
        ]);

        $lesson->update($data);

        return redirect()->route('lessons.index')->with('success', 'Lesson updated successfully');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('lessons.index')->with('success', 'Lesson deleted successfully');
    }
}
