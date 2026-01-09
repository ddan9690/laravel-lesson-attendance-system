<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Term;
use App\Models\User;
use App\Models\Week;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\Curriculum;
use App\Models\FormStream;
use App\Models\GradeStream;
use App\Models\AcademicYear;
use App\Models\LearningArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController
{
    public function create()
    {
        $curriculums = Curriculum::all();

        $forms = Form::with('streams')->get();
        $formStreams = FormStream::all();

        $grades = Grade::with('streams')->get();
        $gradeStreams = GradeStream::all();

        $lessons = Lesson::all()->map(function ($lesson) {
            $lesson->start_time_formatted = \Carbon\Carbon::parse($lesson->start_time)->format('g:ia');
            $lesson->end_time_formatted = \Carbon\Carbon::parse($lesson->end_time)->format('g:ia');
            return $lesson;
        });

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


    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curricula,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id' => 'required|exists:terms,id',
            'week_id' => 'required|exists:weeks,id',
            'form_id' => 'nullable|exists:forms,id',
            'form_stream_id' => 'nullable|exists:form_streams,id',
            'grade_id' => 'nullable|exists:grades,id',
            'grade_stream_id' => 'nullable|exists:grade_streams,id',
            'learning_area_id' => 'nullable|exists:learning_areas,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'lesson_id' => 'required|exists:lessons,id',
            'teacher_id' => 'required|exists:users,id',
            'status' => 'required|in:attended,missed,makeup',
            'notes' => 'nullable|string',
        ]);


        $validated['captured_by'] = Auth::id();


        Attendance::create($validated);


        return redirect()->back()->with('success', 'Attendance has been successfully recorded.');
    }

    public function getFormStreams(Form $form)
    {
        // Return all streams for the selected form
        return response()->json($form->streams);
    }

    public function getGradeStreams(Grade $grade)
    {
        // Return all streams for the selected grade
        return response()->json($grade->streams);
    }

    public function teacherWeeks(User $teacher)
    {
        // Get current active academic year
        $currentYear = AcademicYear::where('active', true)->first();

        // Get current active term
        $currentTerm = Term::where('academic_year_id', $currentYear->id ?? 0)
            ->where('active', true)
            ->first();

        if (!$currentYear || !$currentTerm) {
            return redirect()->back()->with('error', 'Active academic year or term not found.');
        }

        // Get all weeks for this term
        $weeks = Week::where('term_id', $currentTerm->id)
            ->orderBy('id')
            ->get();

        return view('attendance.teacher_weeks', [
            'teacher' => $teacher,
            'weeks' => $weeks,
            'currentYear' => $currentYear,
            'currentTerm' => $currentTerm,
        ]);
    }

    public function teacherWeekAttendance($teacherId, $weekId)
    {
        $teacher = User::findOrFail($teacherId);

        // Current active academic year and term
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('active', true)
            ->where('academic_year_id', $currentYear->id ?? 0)
            ->first();

        $attendanceRecords = Attendance::with([
            'lesson',
            'lesson.learningArea',
            'lesson.subject',
            'capturedBy'
        ])
            ->where('teacher_id', $teacherId)
            ->where('week_id', $weekId)
            ->where('academic_year_id', $currentYear->id ?? 0)
            ->where('term_id', $currentTerm->id ?? 0)
            ->orderBy('created_at', 'desc') // Latest attendance first
            ->get();

        $week = Week::findOrFail($weekId);

        return view('attendance.teacher_week_attendance', [
            'teacher' => $teacher,
            'week' => $week,
            'attendanceRecords' => $attendanceRecords,
            'currentYear' => $currentYear,
            'currentTerm' => $currentTerm
        ]);
    }


    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance record deleted successfully.'
        ]);
    }

  
}
