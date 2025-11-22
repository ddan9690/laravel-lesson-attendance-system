<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Models\Grade;
use App\Models\Payment;
use Illuminate\View\View;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController 
{

    public function home()
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();

        // Role-based redirection mapping
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
     * Admin Dashboard - DIRECT VIEW RETURN
     */
    public function admin(Request $request)
    {
        // Current academic year & term
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('active', true)->first();

        // Filters from request (optional)
        $filterYearId = $request->academic_year_id;
        $filterTermId = $request->term_id;

        // Determine date range
        $startDate = null;
        $endDate = null;

        if ($filterTermId && $term = Term::find($filterTermId)) {
            $startDate = $term->start_date;
            $endDate = $term->end_date;
        } elseif ($filterYearId && $year = AcademicYear::find($filterYearId)) {
            $startDate = $year->start_date;
            $endDate = $year->end_date;
        }

        // Payments query
        $paymentsQuery = Payment::with(['student', 'grade', 'gradeStream']);
        if ($startDate && $endDate) {
            $paymentsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $payments = $paymentsQuery->get();

        // Active term total
        $activeTermTotal = $currentTerm
            ? Payment::whereBetween('created_at', [$currentTerm->start_date, $currentTerm->end_date])->sum('amount')
            : 0;

        // Total clearance collection for graduated students
        $graduatedClearanceTotal = Payment::whereHas('student', function ($query) {
            $query->where('status', 'graduated');
        })->sum('amount');

        // Grades → Streams → Payments Analysis
        $grades = Grade::with(['students', 'streams.students', 'supervisor'])->orderBy('name')->get();
        $gradesAnalysis = [];

        foreach ($grades as $grade) {
            $gradePayments = $payments->where('grade_id', $grade->id);
            $gradeTotal = $gradePayments->sum('amount');
            $studentsPaid = $gradePayments->pluck('student_id')->unique()->count();
            $totalStudents = $grade->students->count();
            $percentage = $totalStudents > 0 ? round(($studentsPaid / $totalStudents) * 100) : 0;

            $streamsData = [];
            $streamsTotalCollected = 0;
            $streamsTotalPaid = 0;

            foreach ($grade->streams as $stream) {
                $streamPayments = $gradePayments->where('grade_stream_id', $stream->id);
                $streamCollected = $streamPayments->sum('amount');
                $streamStudentsPaid = $streamPayments->pluck('student_id')->unique()->count();

                $streamsTotalCollected += $streamCollected;
                $streamsTotalPaid += $streamStudentsPaid;

                $streamsData[] = [
                    'stream_name' => $stream->name,
                    'teacher' => optional($stream->classTeacher)->name,
                    'students_paid' => $streamStudentsPaid,
                    'total_collected' => $streamCollected,
                ];
            }

            $gradesAnalysis[] = [
                'grade' => $grade,
                'supervisor' => optional($grade->supervisor)->name,
                'total_collected' => $gradeTotal,
                'students_paid' => $studentsPaid,
                'total_students' => $totalStudents,
                'percentage' => $percentage,
                'streams' => $streamsData,
                'streams_total_collected' => $streamsTotalCollected,
                'streams_total_paid' => $streamsTotalPaid,
            ];
        }

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
     * Committee Dashboard - DIRECT VIEW RETURN
     */
    public function committee(): View
    {
        return view('dashboards.committee', [
            'user' => Auth::user(),
            'role' => Auth::user()->getRoleNames()->first(),
        ]);
    }

    /**
     * Class Supervisor Dashboard - DIRECT VIEW RETURN
     */
    public function classSupervisor(): View
    {
        return view('dashboards.class_supervisor', [
            'user' => Auth::user(),
            'role' => Auth::user()->getRoleNames()->first(),
        ]);
    }

    /**
     * Class Teacher Dashboard - DIRECT VIEW RETURN
     */
    public function classTeacher(): View
    {
        return view('dashboards.class_teacher', [
            'user' => Auth::user(),
            'role' => Auth::user()->getRoleNames()->first(),
        ]);
    }

    /**
     * Teacher Dashboard - DIRECT VIEW RETURN
     */
    public function teacher(): View
    {
        return view('dashboards.teacher', [
            'user' => Auth::user(),
            'role' => Auth::user()->getRoleNames()->first(),
        ]);
    }
}