<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Services\LessonAttendanceService;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Attendance;
use App\Models\User;

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

    if (!$currentYear || !$currentTerm) {
        return redirect()->back()->with('error', 'Active academic year or term not found.');
    }

    /**
     * STEP 1: Get summary (already respects filters)
     */
    $lessonAttendanceSummary = $this->lessonAttendanceService
        ->getAllTeachersAttendanceSummary(null, null, [], $from, $to);

    /**
     * STEP 2: Recalculate TAUGHT / MISSED per curriculum
     */
    $summaryWithTaughtMissed = $lessonAttendanceSummary->map(function ($summary) use ($from, $to) {

        $teacherId = $summary['teacher']->id;

        // ================= 8-4-4 =================
        $eightFourFourTaught = Attendance::where('teacher_id', $teacherId)
            ->whereHas('curriculum', fn ($q) => $q->where('name', '8-4-4'))
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->whereIn('status', ['attended', 'make-up'])
            ->count();

        $eightFourFourTotal = Attendance::where('teacher_id', $teacherId)
            ->whereHas('curriculum', fn ($q) => $q->where('name', '8-4-4'))
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->count();

        $eightFourFourMissed = $eightFourFourTotal - $eightFourFourTaught;

        // ================= CBC =================
        $cbcTaught = Attendance::where('teacher_id', $teacherId)
            ->whereHas('curriculum', fn ($q) => $q->where('name', 'CBC'))
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->whereIn('status', ['attended', 'make-up'])
            ->count();

        $cbcTotal = Attendance::where('teacher_id', $teacherId)
            ->whereHas('curriculum', fn ($q) => $q->where('name', 'CBC'))
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->count();

        $cbcMissed = $cbcTotal - $cbcTaught;

        // ================= GRAND TOTAL (TAUGHT ONLY) =================
        $totalLessons = $eightFourFourTaught + $cbcTaught;

        return [
            'teacher' => $summary['teacher'],
            'eight_four_four_taught' => $eightFourFourTaught,
            'eight_four_four_missed' => $eightFourFourMissed,
            'cbc_taught' => $cbcTaught,
            'cbc_missed' => $cbcMissed,
            'total_lessons' => $totalLessons,
        ];
    });

    /**
     * STEP 3: FOOTER TOTALS (FILTER-AWARE)
     */
    $totalEightFourFourTaught = $summaryWithTaughtMissed->sum('eight_four_four_taught');
    $totalEightFourFourMissed = $summaryWithTaughtMissed->sum('eight_four_four_missed');

    $totalCbcTaught = $summaryWithTaughtMissed->sum('cbc_taught');
    $totalCbcMissed = $summaryWithTaughtMissed->sum('cbc_missed');

    $grandTotalLessons = $summaryWithTaughtMissed->sum('total_lessons');

    /**
     * STEP 4: Generate PDF
     */
    $pdf = Pdf::loadView('attendance.pdf-exports.teachers-attendance-summary', [
        'lessonAttendanceSummary' => $summaryWithTaughtMissed,
        'currentYear' => $currentYear,
        'currentTerm' => $currentTerm,
        'fromDate' => $from,
        'toDate' => $to,
        'generatedAt' => now(),

        // Totals
        'totalEightFourFourTaught' => $totalEightFourFourTaught,
        'totalEightFourFourMissed' => $totalEightFourFourMissed,
        'totalCbcTaught' => $totalCbcTaught,
        'totalCbcMissed' => $totalCbcMissed,
        'grandTotalLessons' => $grandTotalLessons,
    ]);

    $fileName = 'remedial-attendance-' . now()->format('d-m-y-His') . '.pdf';

    return $pdf->download($fileName);
}

}
