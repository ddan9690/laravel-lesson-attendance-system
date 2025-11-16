<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\AcademicYear;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    /**
     * Redirect user to their role-specific dashboard after login.
     */
    public function index()
    {
        $user = Auth::user();

        // Role → Route mapping
        $map = [
            // Admin-level group
            ['roles' => ['super_admin', 'principal', 'deputy', 'dos', 'senior_teacher'], 'route' => 'dashboard.admin'],

            // Committee
            ['roles' => ['committee_member'], 'route' => 'dashboard.committee'],

            // Class supervisors
            ['roles' => ['class_supervisor'], 'route' => 'dashboard.class_supervisor'],

            // Class teachers
            ['roles' => ['class_teacher'], 'route' => 'dashboard.class_teacher'],

            // Normal teacher
            ['roles' => ['teacher'], 'route' => 'dashboard.teacher'],
        ];


        foreach ($map as $item) {
            if ($user->hasAnyRole($item['roles'])) {
                return redirect()->route($item['route']);
            }
        }

        // Fallback view (no role assigned)
        return view('home', compact('user'));
    }


    // =======================
    // DASHBOARD VIEWS
    // =======================

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


    public function committee()
    {
        return $this->view('dashboards.committee');
    }
    public function classSupervisor()
    {
        return $this->view('dashboards.class_supervisor');
    }
    public function classTeacher()
    {
        return $this->view('dashboards.class_teacher');
    }
    public function teacher()
    {
        return $this->view('dashboards.teacher');
    }

    private function view($path)
    {
        return view($path, ['user' => Auth::user()]);
    }
}
