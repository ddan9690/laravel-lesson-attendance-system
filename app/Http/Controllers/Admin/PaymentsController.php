<?php

namespace App\Http\Controllers\Admin;

use App\Models\Term;
use App\Models\Week;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\Student;
use App\Models\RemedialFee;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentsController
{
    public function index(Request $request)
    {
        // Active academic period
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('active', true)->first();

        // Filters from form
        $filterYearId = $request->academic_year_id;
        $filterTermId = $request->term_id;

        // Load all years and terms for filters
        $years = AcademicYear::orderBy('year')->get();
        $terms = Term::orderBy('start_date')->get();

        // -------------------------------------------------------
        // Determine the date range to filter payments
        // -------------------------------------------------------

        $startDate = null;
        $endDate = null;

        if ($filterTermId) {
            $term = Term::find($filterTermId);
            if ($term) {
                $startDate = $term->start_date;
                $endDate = $term->end_date;
            }
        } elseif ($filterYearId) {
            $year = AcademicYear::find($filterYearId);
            if ($year) {
                $startDate = $year->start_date;
                $endDate = $year->end_date;
            }
        }

        // -------------------------------------------------------
        // Fetch payments using created_at date filter
        // -------------------------------------------------------
        $paymentsQuery = Payment::with(['student', 'grade', 'gradeStream']);

        if ($startDate && $endDate) {
            $paymentsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $payments = $paymentsQuery->get();

        // -------------------------------------------------------
        // Active term total
        // -------------------------------------------------------
        $activeTermTotal = 0;

        if ($currentTerm) {
            $activeTermTotal = Payment::whereBetween(
                'created_at',
                [$currentTerm->start_date, $currentTerm->end_date]
            )->sum('amount');
        }

        // -------------------------------------------------------
        // Build Grades → Streams → Payments Analysis
        // -------------------------------------------------------
        $grades = Grade::with(['students', 'streams.students', 'supervisor'])->orderBy('name')->get();

        $gradesAnalysis = [];

        foreach ($grades as $grade) {
            $gradePayments = $payments->where('grade_id', $grade->id);

            $gradeTotal = $gradePayments->sum('amount');
            $studentsPaid = $gradePayments->pluck('student_id')->unique()->count();
            $totalStudents = $grade->students->count();
            $percentage = $totalStudents > 0 ? round(($studentsPaid / $totalStudents) * 100) : 0;

            // Streams
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
                'supervisor' => optional($grade->classSupervisor)->name,
                'total_collected' => $gradeTotal,
                'students_paid' => $studentsPaid,
                'total_students' => $totalStudents,
                'percentage' => $percentage,

                'streams' => $streamsData,
                'streams_total_collected' => $streamsTotalCollected,
                'streams_total_paid' => $streamsTotalPaid,
            ];
        }

        return view('admin.remedial.payments.index', compact(
            'currentYear',
            'currentTerm',
            'years',
            'terms',
            'filterYearId',
            'filterTermId',
            'gradesAnalysis',
            'activeTermTotal'
        ));
    }

    public function captureForm()
    {

        return view('admin.remedial.payments.capturePayment');
    }

    public function searchStudent(Request $request)
    {

        if ($request->has('adm')) {
            $adm = $request->get('adm');

            $student = Student::with('grade', 'gradeStream')
                ->where('adm', $adm)
                ->first();



            if ($student) {
                return response()->json([
                    'id' => $student->id,
                    'name' => $student->name,
                    'adm' => $student->adm,
                    'grade' => $student->grade->name ?? 'N/A',
                    'stream' => $student->gradeStream->name ?? 'N/A',
                ]);
            }

            return response()->json(null, 404);
        }

        // New behavior ─ autocomplete search
        if ($request->has('q')) {
            $query = $request->q;

            if (strlen($query) < 2) {
                return response()->json([]);
            }

            $students = Student::where('adm', 'like', "%$query%")
                ->orWhere('name', 'like', "%$query%")
                ->limit(10)
                ->get(['id', 'name', 'adm']);

            return response()->json($students);
        }

        return response()->json([], 400);
    }


    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:cash,mpesa',
            'mpesa_transaction_number' => 'nullable|string|max:50',
        ]);

        $student = Student::with('grade', 'gradeStream')->findOrFail($request->student_id);

        $payment = Payment::create([
            'student_id' => $student->id,
            'grade_id' => $student->grade_id,
            'grade_stream_id' => $student->grade_stream_id,
            'student_name' => $student->name,
            'student_adm' => $student->adm,
            'grade_name' => $student->grade->name ?? null,
            'stream_name' => $student->gradeStream->name ?? null,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'mpesa_transaction_number' => $request->payment_type === 'mpesa' ? $request->mpesa_transaction_number : null,
            'user_id' => auth()->id(),
            'notes' => null,
        ]);


        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment captured successfully',
                'payment' => $payment,
            ]);
        }

        return redirect()->route('remedial.payments.index')
            ->with('success', 'Payment captured successfully');
    }

    public function studentProfile(Student $student)
{
    $student->load(['grade', 'gradeStream', 'joinedAcademicYear']);

    $payments = Payment::where('student_id', $student->id)
        ->orderBy('created_at', 'desc')
        ->get();

    $totalPaid = $payments->sum('amount');

    // Calculate expected amount
    $remedialFee = RemedialFee::latest()->first()->amount ?? 0;

    $joinedDate = $student->joinedAcademicYear->start_date ?? now();

    $termsCount = Term::where('start_date', '>=', $joinedDate)
        ->where('start_date', '<=', now())
        ->count();

    $expectedAmount = $remedialFee * max($termsCount, 1);

    $balance = $expectedAmount - $totalPaid;

    return view('admin.remedial.payments.studentProfile', [
        'student' => $student,
        'payments' => $payments,
        'totalPaid' => $totalPaid,
        'balance' => $balance,
    ]);
}

}
