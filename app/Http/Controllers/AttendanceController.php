<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Comment;
use App\Models\Form;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\User;
use App\Models\Week;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    public function index()
    {
        // Get all users with their attendance records
        $users = User::with('attendances')->orderBy('name', 'asc')->get();
    
        // Get all weeks from the Weeks model
        $weeks = Week::all();
    
        // Calculate the total count of attendances
        $totalAttendances = Attendance::count();
    
        // Pass the users, weeks, and total attendances to the view
        return view('remedial.pages.attendances.index', [
            'users' => $users,
            'totalAttendances' => $totalAttendances,
            'weeks' => $weeks,
        ]);
    }

    public function showByWeek(Request $request)
{
    // Get the selected week ID from the request
    $weekId = $request->input('week');
    
    // Get all users with their attendance records filtered by the selected week
    $users = User::with(['attendances' => function($query) use ($weekId) {
        $query->where('week_id', $weekId);
    }])->orderBy('name', 'asc')->get();

    // Get all weeks from the Weeks model
    $weeks = Week::all();

    // Calculate the total count of attendances for the selected week
    $totalAttendances = Attendance::where('week_id', $weekId)->count();

    // Pass the users, weeks, and total attendances to the view
    return view('remedial.pages.attendances.showByWeek', [
        'users' => $users,
        'weeks' => $weeks,
        'totalAttendances' => $totalAttendances,
        'selectedWeek' => $weekId,
    ]);
}

    

    public function latestRecords()
    {
        // Get the last 10 attendance records
        $latestAttendances = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('remedial.pages.attendances.latest-records', [
            'latestAttendances' => $latestAttendances,
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

        // Check restrictions
        $response = $this->checkRestrictions($request);
        if (!$response['success']) {
            return response()->json($response);
        }

        // Create the attendance record
        $attendance = new Attendance;
        $attendance->user_id = $request->input('teacher');
        $attendance->form_id = $request->input('class');
        $attendance->lesson_id = $request->input('lesson');
        $attendance->week_id = $request->input('week');
        $attendance->subject_id = $request->input('subject');
        $attendance->status = $request->has('status') ? 'make-up' : '';
        $attendance->save();

        return response()->json(['success' => true, 'message' => 'Record created successfully.']);
    }

    private function checkRestrictions(Request $request)
    {
        $formId = $request->input('class');
        $subjectId = $request->input('subject');
        $weekId = $request->input('week');
        $lessonId = $request->input('lesson'); // Capture the selected lesson
    
        // Retrieve the lesson name based on the lesson ID
        $lessonName = Lesson::find($lessonId)->name ?? '';
    
        // Allow subjects Chemistry, Biology, and Physics for blocked classes if the lesson name is 'Practical'
        if ($lessonName === 'Practical' && in_array(Subject::find($subjectId)->name, ['Chemistry', 'Biology', 'Physics'])) {
            return ['success' => true]; // Bypass restrictions
        }
    
        // Define classes with specific restrictions for Mathematics
        $classesWithMathRestrictions = [
            '2 Diamond', '2 Emerald', '2 Gold', '2 Pearl',
            '3 Diamond', '3 Emerald', '3 Gold', '3 Pearl', 
            '4 Diamond', '4 Emerald', '4 Gold', '4 Pearl', '4 Sapphire', '4 Topaz',
        ];
    
        // Define classes with maximum 1 remedial lessons per week for all subjects
        $classesWithMaxOneRemedial = [
            '2 Diamond', '2 Emerald', '2 Gold', '2 Pearl',
            '3 Diamond', '3 Emerald', '3 Gold', '3 Pearl',
            '4 Diamond', '4 Emerald', '4 Gold', '4 Pearl', '4 Sapphire', '4 Topaz',
        ];
    
        // Define classes with subjects excluded from remedial lessons
        $classesWithExcludedSubjects = [
            '2 Diamond', '2 Emerald', '2 Gold', '2 Pearl',
            '3 Diamond', '3 Emerald', '3 Gold', '3 Pearl', 
            '4 Diamond', '4 Emerald', '4 Gold', '4 Pearl', '4 Sapphire', '4 Topaz',
        ];
    
        // Define excluded subjects
        $excludedSubjects = ['Agriculture', 'Business', 'Computer', 'CRE', 'French', 'Geography', 'History', 'Home Science', 'Physics'];
    
        // Define classes with weekly lesson restrictions for specific subjects
        $classesWithWeeklyRestrictions = [
            'Form 2', 'Form 3', 'Form 4',
        ];
    
        // Define restricted subjects for Form 2, Form 3, and Form 4
        $restrictedSubjects = ['English', 'Kiswahili', 'Mathematics', 'Biology', 'Chemistry'];
    
        // Check restrictions for excluded subjects
        if (in_array(Form::find($formId)->name, $classesWithExcludedSubjects) && in_array(Subject::find($subjectId)->name, $excludedSubjects)) {
            return ['success' => false, 'message' => 'Block subject cannot be recorded to a single stream. Please select a block class.'];
        }
    
        // Restriction: Restricted subjects cannot be added to Form 2, Form 3, and Form 4
        elseif (in_array(Form::find($formId)->name, $classesWithWeeklyRestrictions) && in_array(Subject::find($subjectId)->name, $restrictedSubjects)) {
            return ['success' => false, 'message' => 'This subject cannot be added to a blocked class.'];
        }
    
        // Check restrictions for Mathematics
        elseif (in_array(Form::find($formId)->name, $classesWithMathRestrictions) && Subject::find($subjectId)->name === 'Mathematics') {
            $attendanceCountForMath = Attendance::where('form_id', $formId)
                ->where('subject_id', $subjectId)
                ->where('week_id', $weekId)
                ->count();
    
            if ($attendanceCountForMath >= 2) {
                return ['success' => false, 'message' => 'Mathematics can have a maximum of 2 remedials per week.'];
            }
        }
    
        // Check restrictions for other subjects
        elseif (in_array(Form::find($formId)->name, $classesWithMaxOneRemedial)) {
            $attendanceCountForOtherSubjects = Attendance::where('form_id', $formId)
                ->where('subject_id', $subjectId)
                ->where('week_id', $weekId)
                ->count();
    
            if ($attendanceCountForOtherSubjects >= 1) {
                return ['success' => false, 'message' => 'This class can have a maximum of 1 remedial per week for the selected subject.'];
            }
        }
    
        // Check weekly lesson restrictions for specific subjects
        elseif (in_array(Form::find($formId)->name, $classesWithWeeklyRestrictions) && in_array(Subject::find($subjectId)->name, ['Agriculture', 'Business', 'Computer', 'CRE', 'French', 'Geography', 'History', 'Home Science', 'Physics'])) {
            $attendanceCountForWeeklyRestrictions = Attendance::where('form_id', $formId)
                ->where('subject_id', $subjectId)
                ->where('week_id', $weekId)
                ->count();
    
            if ($attendanceCountForWeeklyRestrictions >= 3) {
                return ['success' => false, 'message' => 'Maximum teachers reached for this block subject in the selected class.'];
            }
        }
    
        return ['success' => true];
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

        // Fetch all comments associated with the specified week
        $comments = Comment::where('week_id', $week->id)->get();

        return view('remedial.pages.attendances.showusersperweek', compact('user', 'week', 'attendances', 'comments'));
    }

    public function forms()
    {
        $forms = Form::all();

        return view('remedial.pages.attendances.showformsallweeks', compact('forms'));
    }

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

    public function destroy($id, Request $request)
    {
        $attendance = Attendance::findOrFail($id);
        $success = $attendance->delete();

        $response = [
            'message' => $success ? 'Attendance deleted successfully.' : 'Unable to delete attendance record.',
        ];

        return response()->json($response);
    }

    public function deleteAll()
    {
        Attendance::truncate();

        return redirect()->back()->with('message', 'All Remedial records records have been deleted.');
    }
}
