<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\User;
use App\Models\Week;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    public function index()
    {
        $users = User::with('attendances')->get();

        return view('remedial.pages.attendances.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $teachers = User::orderBy('name')->get();
        $weeks = Week::all();
        $forms = Form::all();
        $lessons = Lesson::all();
        $subjects = Subject::all();

        return view('remedial.pages.attendances.create', compact('teachers', 'weeks', 'forms', 'lessons', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher' => ['required', 'integer'],
            'class' => ['required', 'integer'],
            'lesson' => ['required', 'integer'],
            'week' => ['required', 'integer'],
        ]);

        $attendance = new Attendance;
        $attendance->user_id = $request->teacher;
        $attendance->form_id = $request->class;
        $attendance->lesson_id = $request->lesson;
        $attendance->week_id = $request->week;
        $attendance->subject_id = $request->subject;

        if ($request->has('status')) {
            $attendance->status = 'make-up';
        }

        $attendance->save();

        return back()->with('success', 'Attendance added successfully.');
    }


    public function show($id)
    {
        $user = User::findOrFail($id);
        $attendances = $user->attendances;
        $weeks = Week::orderBy('week_number')->get();

        return view('remedial.pages.attendances.showuserallweeks', compact('user', 'attendances', 'weeks'));
    }

    public function userweekly($week, $user_id)
    {
        $user = User::findOrFail($user_id);
        $week = Week::where('week_number', $week)->firstOrFail();
        $attendances = Attendance::where('user_id', $user_id)
            ->where('week_id', $week->id)
            ->with('form', 'subject', 'lesson')
            ->orderBy('created_at')
            ->get();
        return view('remedial.pages.attendances.showusersperweek', compact('user', 'week', 'attendances'));
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', 'Attendance deleted successfully.');
    }
}
