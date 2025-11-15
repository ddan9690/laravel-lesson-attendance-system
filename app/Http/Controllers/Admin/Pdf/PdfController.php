<?php

namespace App\Http\Controllers\Admin\Pdf;

use App\Models\Term;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\RemedialFee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class PdfController
{
    public function generateGradeRemedialPDF($gradeId)
    {
        $grade = Grade::with(['streams.students.joinedAcademicYear'])->findOrFail($gradeId);

        $remedialFee = RemedialFee::latest()->first()->amount ?? 0;

        $streamsData = [];

        foreach ($grade->streams as $stream) {
            $studentsData = [];

            foreach ($stream->students as $student) {
                // Total cumulative paid by student
                $totalPaid = Payment::where('student_id', $student->id)->sum('amount');

                // Determine how many terms have passed since joining until today
                $joinedDate = $student->joinedAcademicYear->start_date ?? now();

                // Count terms from student's join date until today
                $termsCount = Term::where('start_date', '>=', $joinedDate)
                    ->where('start_date', '<=', now())
                    ->count();

                // Expected payment for **current term only**
                $currentTermExpected = $remedialFee * max($termsCount, 1); // at least 1 term

                // Balance is difference between what should have been paid so far and total paid
                $balance = $currentTermExpected - $totalPaid;

                $studentsData[] = [
                    'adm' => $student->adm,
                    'name' => $student->name,
                    'total_paid' => $totalPaid,
                    'balance' => $balance,
                ];
            }

            $streamsData[] = [
                'stream_name' => $stream->name,
                'students' => $studentsData,
            ];
        }

        $pdf = PDF::loadView('admin.remedial.payments.pdf.payments.grade', [
            'grade' => $grade,
            'streamsData' => $streamsData,
            'currentDate' => now(),
        ]);

        return $pdf->download("Remedial_Payment_Slips_{$grade->name}.pdf");
    }
}
