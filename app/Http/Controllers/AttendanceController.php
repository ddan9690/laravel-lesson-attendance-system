<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Term;
use App\Models\User;
use App\Models\Week;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Curriculum;
use App\Models\FormStream;
use App\Models\GradeStream;
use App\Models\AcademicYear;
use App\Models\LearningArea;
use Illuminate\Http\Request;

class AttendanceController
{
    public function create()
    {
        $curriculums = Curriculum::all();

        $forms = Form::with('streams')->get();
        $formStreams = FormStream::all();

        $grades = Grade::with('streams')->get();
        $gradeStreams = GradeStream::all();

        $lessons = Lesson::all();

        $subjects = Subject::all();
        $learningAreas = LearningArea::all();

        $academicYear = AcademicYear::where('active', 1)->firstOrFail();

        $term = Term::where('academic_year_id', $academicYear->id)
            ->where('active', 1)
            ->firstOrFail();

        $weeks = Week::where('term_id', $term->id)->get();

        $users = User::where('email', '!=', 'dancanokeyo08@gmail.com')->get();

        return view('attendance.create', compact(
            'curriculums',
            'forms',
            'formStreams',
            'grades',
            'gradeStreams',
            'lessons',
            'subjects',
            'learningAreas',
            'academicYear',
            'term',
            'weeks',
            'users'
        ));
    }

    public function store(Request $request){
     
    }
}
