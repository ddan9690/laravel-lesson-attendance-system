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
use App\Services\LessonAttendanceService;

class AttendanceController
{
    public function create()
    {
        $curriculums = Curriculum::all();

        $forms = Form::with('streams')->get();
        $formStreams = FormStream::orderBy('name')->get();

        $grades = Grade::with('streams')->get();
        $gradeStreams = GradeStream::orderBy('name')->get();


        $lessons = Lesson::all()->map(function ($lesson) {
            $lesson->start_time_formatted = \Carbon\Carbon::parse($lesson->start_time)->format('g:ia');
            $lesson->end_time_formatted = \Carbon\Carbon::parse($lesson->end_time)->format('g:ia');
            return $lesson;
        });

        $subjects = Subject::orderBy('name')->get();
        $learningAreas = LearningArea::orderBy('name')->get();


        $academicYear = AcademicYear::where('active', 1)->firstOrFail();

        $term = Term::where('academic_year_id', $academicYear->id)
            ->where('active', 1)
            ->firstOrFail();

        $weeks = Week::where('term_id', $term->id)->get();

        $users = User::where('email', '!=', 'dancanokeyo08@gmail.com')
            ->orderBy('name', 'asc')
            ->get();


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

        // Prepare attendance summary per week per curriculum
        $weekAttendance = [];

        foreach ($weeks as $week) {
            $curriculums = ['8-4-4', 'CBC'];
            $weekData = [];

            foreach ($curriculums as $curriculumName) {
                $attendedCount = Attendance::where('teacher_id', $teacher->id)
                    ->where('week_id', $week->id)
                    ->where('academic_year_id', $currentYear->id)
                    ->where('term_id', $currentTerm->id)
                    ->whereHas('curriculum', fn($q) => $q->where('name', $curriculumName))
                    ->whereIn('status', ['attended', 'make-up'])
                    ->count();

                $totalLessons = Attendance::where('teacher_id', $teacher->id)
                    ->where('week_id', $week->id)
                    ->where('academic_year_id', $currentYear->id)
                    ->where('term_id', $currentTerm->id)
                    ->whereHas('curriculum', fn($q) => $q->where('name', $curriculumName))
                    ->count();

                $missedCount = $totalLessons - $attendedCount;

                $weekData[$curriculumName] = [
                    'attended' => $attendedCount,
                    'missed' => $missedCount,
                ];
            }

            $weekAttendance[$week->id] = $weekData;
        }

        return view('attendance.teacher_weeks', [
            'teacher' => $teacher,
            'weeks' => $weeks,
            'currentYear' => $currentYear,
            'currentTerm' => $currentTerm,
            'weekAttendance' => $weekAttendance,
        ]);
    }


    public function teacherWeekAttendance($teacherId, $weekId)
    {
        $teacher = User::findOrFail($teacherId);

        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('active', true)
            ->where('academic_year_id', $currentYear->id ?? 0)
            ->first();

        $attendanceRecords = Attendance::with([
            'lesson',
            'learningArea',
            'subject',
            'capturedBy'
        ])
            ->where('teacher_id', $teacherId)
            ->where('week_id', $weekId)
            ->where('academic_year_id', $currentYear->id ?? 0)
            ->where('term_id', $currentTerm->id ?? 0)
            ->orderBy('created_at', 'desc')
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

    public function classAttendance()
    {

        $curriculums = Curriculum::orderBy('name')->get();

        return view('attendance.class-attendance.index', [
            'curriculums' => $curriculums
        ]);
    }

    public function classAttendanceByCurriculum($curriculumId)
    {
        $curriculum = Curriculum::findOrFail($curriculumId);

        if ($curriculum->name === '8-4-4') {
            $formsOrGrades = Form::orderBy('name')->get();
        } else {
            $formsOrGrades = Grade::orderBy('name')->get();
        }

        return view('attendance.class-attendance.forms-or-grades', [
            'curriculum' => $curriculum,
            'formsOrGrades' => $formsOrGrades,
        ]);
    }

    public function classAttendanceByFormOrGrade($curriculumId, $itemId, LessonAttendanceService $lessonService)
    {
        $curriculum = Curriculum::findOrFail($curriculumId);

        if ($curriculum->name === '8-4-4') {
            $formOrGrade = Form::findOrFail($itemId);
            $streams = $formOrGrade->streams()->orderBy('name')->get(); // use streams() for Form
        } else {
            $formOrGrade = Grade::findOrFail($itemId);
            $streams = $formOrGrade->streams()->orderBy('name')->get(); // use streams() for Grade
        }

        $summary = $lessonService->getClassAttendanceSummary(
            $curriculum->name,
            $curriculum->name === '8-4-4' ? $formOrGrade->id : null,
            $curriculum->name === 'CBC' ? $formOrGrade->id : null
        );

        return view('attendance.class-attendance.curriculum-classes', [
            'curriculum' => $curriculum,
            'formOrGrade' => $formOrGrade,
            'streams' => $streams,
            'summary' => $summary,
        ]);
    }

    public function classAttendanceByStream($curriculumId, $formOrGradeId, $streamId, LessonAttendanceService $lessonService)
    {
        $curriculum = Curriculum::findOrFail($curriculumId);

        if ($curriculum->name === '8-4-4') {
            $formOrGrade = Form::findOrFail($formOrGradeId);
        } else {
            $formOrGrade = Grade::findOrFail($formOrGradeId);
        }

        $stream = $formOrGrade->streams()->findOrFail($streamId);

        // Get weekly attendance summary for this stream
        $weekData = $lessonService->getClassAttendanceSummary(
            $curriculum->name,
            $curriculum->name === '8-4-4' ? $formOrGrade->id : null,
            $curriculum->name === 'CBC' ? $formOrGrade->id : null
        )['weeks'] ?? [];

        return view('attendance.class-attendance.stream-weeks', [
            'curriculum'  => $curriculum,
            'formOrGrade' => $formOrGrade,
            'stream'      => $stream,
            'weeks'       => collect($weekData), // Convert to Collection for Blade
        ]);
    }

    public function classAttendanceWeekRecords($curriculumId, $formOrGradeId, $streamId, $weekId)
    {
        $curriculum = Curriculum::findOrFail($curriculumId);
        $week = Week::findOrFail($weekId);

        if ($curriculum->name === '8-4-4') {
            $formOrGrade = Form::findOrFail($formOrGradeId);
            $stream = FormStream::findOrFail($streamId);
            $query = Attendance::with(['lesson', 'teacher', 'subject', 'learningArea'])
                ->where('week_id', $week->id)
                ->where('form_stream_id', $stream->id);
        } else {
            $formOrGrade = Grade::findOrFail($formOrGradeId);
            $stream = GradeStream::findOrFail($streamId);
            $query = Attendance::with(['lesson', 'teacher', 'subject', 'learningArea'])
                ->where('week_id', $week->id)
                ->where('grade_stream_id', $stream->id);
        }

        $records = $query->whereIn('status', ['attended', 'missed', 'makeup'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('attendance.class-attendance.week-records', compact(
            'curriculum',
            'formOrGrade',
            'stream',
            'week',
            'records'
        ));
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
