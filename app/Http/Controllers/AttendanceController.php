<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\User;
use App\Models\Week;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Alert;


class AttendanceController extends Controller
{

    public function index()
    {
        $users = User::with('attendances')
            ->orderBy('name', 'asc')
            ->get();

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
            'subject' => ['required', 'integer'],
        ]);

        // Get the form, subject, and week based on the request data
        $formId = $request->input('class');
        $subjectId = $request->input('subject');
        $weekId = $request->input('week');
        $teacherId = $request->input('teacher');

        $restrictedForms = [
            '2 Diamond', '2 Emerald', '2 Topaz', '2 Gold', '2 Sapphire', '2 Pearl',
            '3 Diamond', '3 Topaz', '3 Gold', '3 Sapphire', '3 Pearl',
            '4 East', '4 West', '4 Sapphire', '4 North'
        ];
        if (in_array(Form::find($formId)->name, $restrictedForms)) {
            $restrictedSubjects = ['English', 'Chemistry', 'Biology', 'Mathematics'];
            if (in_array(Subject::find($subjectId)->name, $restrictedSubjects)) {
                $attendanceCount = Attendance::where('form_id', $formId)
                    ->where('subject_id', $subjectId)
                    ->where('week_id', $weekId)
                    ->count();

                if ($attendanceCount >= 2) {
                    return response()->json(['success' => false, 'message' => 'This class can have a maximum of 2 remedials for this subject in the selected week.']);
                }
            } else if (Subject::find($subjectId)->name === 'Kiswahili') {
                $attendanceCount = Attendance::where('form_id', $formId)
                    ->where('subject_id', $subjectId)
                    ->where('week_id', $weekId)
                    ->count();

                if ($attendanceCount >= 1) {
                    return response()->json(['success' => false, 'message' => 'This class can have a maximum of 1 remedial in the selected week.']);
                }
            }
        }

        // Check if the maximum attendance limit has been reached
        // $maxAttendanceLimit = in_array(Subject::find($subjectId)->name, ['English', 'Chemistry', 'Mathematics', 'Biology']) ? 2 : 1;

        // $attendanceCount = Attendance::where('form_id', $formId)
        //     ->where('subject_id', $subjectId)
        //     ->where('week_id', $weekId)
        //     ->where('user_id', $teacherId)
        //     ->count();

        // if ($attendanceCount >= $maxAttendanceLimit) {
        //     return response()->json(['success' => false, 'message' => 'Maximum remedial limit reached for this subject and form in the selected week.']);
        // }

        // Create the attendance record
        $attendance = new Attendance;
        $attendance->user_id = $teacherId;
        $attendance->form_id = $formId;
        $attendance->lesson_id = $request->lesson;
        $attendance->week_id = $weekId;
        $attendance->subject_id = $subjectId;
        $attendance->status = $request->has('status') ? 'make-up' : '';
        $attendance->save();

        return response()->json(['success' => true, 'message' => 'Record created successfully.']);
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



    public function forms()
    {
        $forms = Form::all();

        return view('remedial.pages.attendances.showformsallweeks', compact('forms'));
    }

    // public function classrecords($id)
    // {
    //     $form = Form::findOrFail($id);
    //     $attendances = $form->attendances;

    //     return view('remedial.forms.show-attendance', compact('form', 'attendances'));
    // }

    public function showAttendance($id)
    {
        $form = Form::findOrFail($id);
        $attendances = $form->attendances;

        $weeklyAttendances = $attendances->groupBy('week_id')
            ->map(function ($group) {
                return $group->count();
            });

        return view('remedial.forms.show-attendance', compact('form', 'weeklyAttendances'));
    }



    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', 'Attendance deleted successfully.');
    }

    public function deleteAll()
    {
        Attendance::truncate();

        return redirect()->back()->with('message', 'All Remedial records records have been deleted.');
    }
}
