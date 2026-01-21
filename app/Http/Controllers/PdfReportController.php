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

    /**
     * Download Teachers Lesson Attendance Summary (Committee)
     */
    public function teachersAttendanceSummaryPdf(Request $request)
    {
        // Filters
        $from = $request->from;
        $to   = $request->to;

        // Active academic context
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('active', true)
            ->where('academic_year_id', $currentYear->id ?? 0)
            ->first();

        // Attendance summary (same data as dashboard)
        $lessonAttendanceSummary =
            $this->lessonAttendanceService->getAllTeachersAttendanceSummary(
                null,
                null,
                [],
                $from,
                $to
            );

        // Generate PDF
        $pdf = Pdf::loadView(
            'attendance.pdf-exports.teachers-attendance-summary',
            [
                'lessonAttendanceSummary' => $lessonAttendanceSummary,
                'currentYear' => $currentYear,
                'currentTerm' => $currentTerm,
                'fromDate' => $from,
                'toDate' => $to,
                'generatedAt' => now(),
            ]
        )->setPaper('a4', 'landscape');

        // File name
        $fileName =
            'teachers_lesson_attendance_summary_' .
            now()->format('Y_m_d_His') .
            '.pdf';

        // Download PDF
        return $pdf->download($fileName);
    }
}
