<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Services\LessonAttendanceService;
use App\Models\AcademicYear;
use App\Models\Term;

class PdfReportController
{
    protected LessonAttendanceService $lessonAttendanceService;

    public function __construct(LessonAttendanceService $lessonAttendanceService)
    {
        $this->lessonAttendanceService = $lessonAttendanceService;
    }

  
    public function teachersAttendanceSummaryPdf(Request $request)
    {
      
        $from = $request->from;
        $to   = $request->to;

      
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('active', true)
            ->where('academic_year_id', $currentYear->id ?? 0)
            ->first();

    
        $lessonAttendanceSummary =
            $this->lessonAttendanceService->getAllTeachersAttendanceSummary(
                null,
                null,
                [],
                $from,
                $to
            );

      
        $summaryWithTaughtMissed = $lessonAttendanceSummary->map(function($summary) {
            // 8-4-4
            $eightFourFourTaught = $summary['eight_four_four'];
            $eightFourFourMissed = ($summary['eight_four_four_total'] ?? $eightFourFourTaught) - $eightFourFourTaught;

            // CBC
            $cbcTaught = $summary['cbc'];
            $cbcMissed = ($summary['cbc_total'] ?? $cbcTaught) - $cbcTaught;

            $totalLessons = $eightFourFourTaught + $eightFourFourMissed + $cbcTaught + $cbcMissed;

            return array_merge($summary, [
                'eight_four_four_taught' => $eightFourFourTaught,
                'eight_four_four_missed' => $eightFourFourMissed,
                'cbc_taught' => $cbcTaught,
                'cbc_missed' => $cbcMissed,
                'total_lessons' => $totalLessons,
            ]);
        });

   
        $pdf = Pdf::loadView(
            'attendance.pdf-exports.teachers-attendance-summary',
            [
                'lessonAttendanceSummary' => $summaryWithTaughtMissed,
                'currentYear' => $currentYear,
                'currentTerm' => $currentTerm,
                'fromDate' => $from,
                'toDate' => $to,
                'generatedAt' => now(),
            ]
        );

    
        $fileName =
            'teachers_lesson_attendance_summary_' .
            now()->format('Y_m_d_His') .
            '.pdf';

        return $pdf->download($fileName);
    }
}
