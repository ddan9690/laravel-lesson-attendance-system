<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Week;

class LessonAttendanceService
{
    /**
     * Get authenticated teacher's lesson attendance for active term
     */
    public function getMyAttendance(int $teacherId)
    {
        // Get current active academic year
        $currentYear = AcademicYear::where('active', true)->first();

        // Get current active term in that year
        $currentTerm = Term::where('academic_year_id', $currentYear->id)
            ->where('active', true)
            ->first();

        // Get all weeks in this term
        $weeks = $currentTerm ? Week::where('term_id', $currentTerm->id)->orderBy('id')->get() : collect();

        $attendanceByWeek = [];

        foreach ($weeks as $week) {
            $attended844 = Attendance::where('teacher_id', $teacherId)
                ->where('status', 'attended')
                ->where('academic_year_id', $currentYear->id)
                ->where('term_id', $currentTerm->id)
                ->where('week_id', $week->id)
                ->whereHas('curriculum', fn($q) => $q->where('name', '8-4-4'))
                ->count();

            $attendedCBC = Attendance::where('teacher_id', $teacherId)
                ->where('status', 'attended')
                ->where('academic_year_id', $currentYear->id)
                ->where('term_id', $currentTerm->id)
                ->where('week_id', $week->id)
                ->whereHas('curriculum', fn($q) => $q->where('name', 'CBC'))
                ->count();

            $attendanceByWeek[] = [
                'week_id'   => $week->id,
                'week_name' => $week->name,
                'total'     => $attended844 + $attendedCBC,
            ];
        }

        return [
            'teacher' => optional(User::find($teacherId))->name,
            'weeks' => $attendanceByWeek,
        ];
    }

    public function getAllTeachersAttendanceSummary($yearId = null, $termId = null, $weekIds = [], $from = null, $to = null)
    {
        $currentYear = $yearId ? AcademicYear::find($yearId) : AcademicYear::where('active', true)->first();
        $currentTerm = $termId ? Term::find($termId) : Term::where('active', true)
            ->where('academic_year_id', $currentYear->id ?? 0)
            ->first();

        if (!$currentYear || !$currentTerm) return collect();

        $teachers = User::where('email', '!=', 'dancanokeyo08@gmail.com')
            ->orderBy('name')
            ->get();

        $summary = [];

        foreach ($teachers as $teacher) {
            $query = Attendance::where('teacher_id', $teacher->id)
                ->where('status', 'attended')
                ->where('academic_year_id', $currentYear->id)
                ->where('term_id', $currentTerm->id);

            if (!empty($weekIds)) {
                $query->whereIn('week_id', $weekIds);
            }

            if ($from) $query->whereDate('created_at', '>=', $from);
            if ($to) $query->whereDate('created_at', '<=', $to);

            $cbcCount = (clone $query)->whereHas('curriculum', fn($q) => $q->where('name', 'CBC'))->count();
            $eightFourFourCount = (clone $query)->whereHas('curriculum', fn($q) => $q->where('name', '8-4-4'))->count();

            $summary[] = [
                'teacher' => $teacher,
                'cbc' => $cbcCount,
                'eight_four_four' => $eightFourFourCount,
                'total' => $cbcCount + $eightFourFourCount,
            ];
        }

        return collect($summary);
    }
}
