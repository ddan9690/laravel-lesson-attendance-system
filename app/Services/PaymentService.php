<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Grade;
use App\Models\Term;
use App\Models\AcademicYear;

class PaymentService
{
    /**
     * Get payments filtered by optional academic year or term
     */
    public function getPayments($filterYearId = null, $filterTermId = null)
    {
        $startDate = null;
        $endDate = null;

        if ($filterTermId && $term = Term::find($filterTermId)) {
            $startDate = $term->start_date;
            $endDate = $term->end_date;
        } elseif ($filterYearId && $year = AcademicYear::find($filterYearId)) {
            $startDate = $year->start_date;
            $endDate = $year->end_date;
        }

        $query = Payment::with(['student', 'grade', 'gradeStream']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->get();
    }

    /**
     * Total payments for the active term
     */
    public function getActiveTermTotal()
    {
        $currentTerm = Term::where('active', true)->first();

        if (!$currentTerm) return 0;

        return Payment::whereBetween('created_at', [
            $currentTerm->start_date,
            $currentTerm->end_date
        ])->sum('amount');
    }

    /**
     * Total clearance payments for graduated students
     */
    public function getGraduatedClearanceTotal()
    {
        return Payment::whereHas('student', function ($query) {
            $query->where('status', 'graduated');
        })->sum('amount');
    }

    /**
     * Analysis per grade & stream
     */
    public function analyzeGrades($payments)
    {
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

        return $gradesAnalysis;
    }
}
