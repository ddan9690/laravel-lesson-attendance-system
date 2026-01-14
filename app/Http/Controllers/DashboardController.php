<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Services\PaymentService;
use App\Services\LessonAttendanceService;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Attendance;
use App\Models\User;

class DashboardController
{
    protected $paymentService;
    protected $lessonAttendanceService;

    public function __construct(PaymentService $paymentService, LessonAttendanceService $lessonAttendanceService)
    {
        $this->paymentService = $paymentService;
        $this->lessonAttendanceService = $lessonAttendanceService;
    }

    /**
     * Role-based home redirection
     */
    public function home()
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();

        $roleRedirects = [
            'super_admin' => 'dashboard.admin',
            'principal' => 'dashboard.admin',
            'deputy' => 'dashboard.admin',
            'dos' => 'dashboard.admin',
            'senior_teacher' => 'dashboard.admin',
            'committee_member' => 'dashboard.committee',
            'class_supervisor' => 'dashboard.class_supervisor',
            'class_teacher' => 'dashboard.class_teacher',
            'teacher' => 'dashboard.teacher',
        ];

        foreach ($roleRedirects as $role => $route) {
            if ($roles->contains($role)) {
                return redirect()->route($route);
            }
        }

        return redirect()->route('dashboard.admin');
    }

    /**
     * Admin Dashboard
     */
    public function admin(Request $request)
    {
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('active', true)->first();

        $filterYearId = $request->academic_year_id;
        $filterTermId = $request->term_id;

        $payments = $this->paymentService->getPayments($filterYearId, $filterTermId);
        $gradesAnalysis = $this->paymentService->analyzeGrades($payments);
        $activeTermTotal = $this->paymentService->getActiveTermTotal();
        $graduatedClearanceTotal = $this->paymentService->getGraduatedClearanceTotal();

        return view('dashboards.admin', compact(
            'currentYear',
            'currentTerm',
            'filterYearId',
            'filterTermId',
            'gradesAnalysis',
            'activeTermTotal',
            'graduatedClearanceTotal'
        ));
    }

    /**
     * Committee Dashboard
     */
    public function committee(Request $request): View
    {
        $user = Auth::user();

        // Only date range filters
        $from = $request->from;
        $to   = $request->to;

      
        $lessonAttendanceSummary = $this->lessonAttendanceService->getAllTeachersAttendanceSummary(
            null,   // year
            null,   // term
            [],    
            $from,
            $to
        );

        return view('dashboards.committee', [
            'user' => $user,
            'role' => $user->getRoleNames()->first(),

  
            'lessonAttendanceSummary' => $lessonAttendanceSummary,


            'fromDate' => $from,
            'toDate' => $to,
        ]);
    }


    /**
     * Class Supervisor Dashboard
     */
    public function classSupervisor(): View
    {
        $user = Auth::user();
        return view('dashboards.class_supervisor', [
            'user' => $user,
            'role' => $user->getRoleNames()->first(),
        ]);
    }

    /**
     * Class Teacher Dashboard
     */
    public function classTeacher(): View
    {
        $user = Auth::user();
        return view('dashboards.class_teacher', [
            'user' => $user,
            'role' => $user->getRoleNames()->first(),
        ]);
    }

    /**
     * Teacher Dashboard
     */
    public function teacher(): View
    {
        $user = Auth::user();
        return view('dashboards.teacher', [
            'user' => $user,
            'role' => $user->getRoleNames()->first(),
        ]);
    }

    /**
     * Show My Lesson Attendance (for all roles)
     */
    public function myAttendance()
    {
        $user = auth()->user();

        $attendanceData = $this->lessonAttendanceService->getMyAttendance($user->id);

        // Get summary for leadership roles
        $lessonAttendanceSummary = collect();
        $roles = $user->getRoleNames();
        if ($roles->intersect(['super_admin', 'principal', 'deputy', 'dos', 'senior_teacher', 'committee_member'])->isNotEmpty()) {
            $lessonAttendanceSummary = $this->lessonAttendanceService->getAllTeachersAttendanceSummary();
        }

        return view('attendance.my_attendance', [
            'user' => $user,
            'role' => $user->getRoleNames()->first(),
            'attendanceData' => $attendanceData,
            'lessonAttendanceSummary' => $lessonAttendanceSummary
        ]);
    }

    /**
     * Show attendance records for a specific week
     */
    public function weekAttendance($weekId)
    {
        $user = auth()->user();

        // Get current active academic year and term
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('academic_year_id', $currentYear->id ?? 0)
            ->where('active', true)->first();

        // Fetch all attendance for this teacher for the given week
        $attendance = Attendance::with([
            'lesson',
            'form',
            'formStream',
            'grade',
            'gradeStream',
            'curriculum'
        ])
            ->where('teacher_id', $user->id)
            ->where('week_id', $weekId)
            ->where('academic_year_id', $currentYear->id ?? 0)
            ->where('term_id', $currentTerm->id ?? 0)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($item) => $item->curriculum->name ?? 'Unknown');

        // Pass data to view
        return view('attendance.my_week_attendance', [
            'user' => $user,
            'week' => \App\Models\Week::find($weekId),
            'attendance' => $attendance,
            'currentYear' => $currentYear,
            'currentTerm' => $currentTerm
        ]);
    }
}
