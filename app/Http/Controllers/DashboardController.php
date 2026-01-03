<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\Week;
use Illuminate\View\View;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\LessonAttendanceService;
use Illuminate\Support\Facades\Auth;

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
    /**
     * Committee Dashboard
     */
    public function committee()
    {
        $user = Auth::user();

        // Call the service to get all teachers' attendance with curriculum breakdown
        $attendanceAnalysis = $this->lessonAttendanceService->getCommitteeAttendance();

        return view('dashboards.committee', [
            'user' => $user,
            'role' => $user->getRoleNames()->first(),
            'weeks' => [], // keep empty for now
            'selectedWeeks' => [],
            'attendanceAnalysis' => $attendanceAnalysis,
        ]);
    }








    /**
     * Class Supervisor Dashboard
     */
    public function classSupervisor(): View
    {
        return view('dashboards.class_supervisor', [
            'user' => Auth::user(),
            'role' => Auth::user()->getRoleNames()->first(),
        ]);
    }

    /**
     * Class Teacher Dashboard
     */
    public function classTeacher(): View
    {
        return view('dashboards.class_teacher', [
            'user' => Auth::user(),
            'role' => Auth::user()->getRoleNames()->first(),
        ]);
    }

    /**
     * Teacher Dashboard
     */
    public function teacher(): View
    {
        return view('dashboards.teacher', [
            'user' => Auth::user(),
            'role' => Auth::user()->getRoleNames()->first(),
        ]);
    }
}
