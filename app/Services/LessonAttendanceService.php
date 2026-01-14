<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Week;

class LessonAttendanceService
{
    public function getMyAttendance(int $teacherId)
    {
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('academic_year_id', $currentYear->id ?? 0)
            ->where('active', true)
            ->first();

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
                'week_id' => $week->id,
                'week_name' => $week->name,
                'total' => $attended844 + $attendedCBC,
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

    public function getClassAttendanceSummary(
        string $curriculumName,
        ?int $formId = null,
        ?int $gradeId = null,
        ?array $weekIds = [],
        ?string $from = null,
        ?string $to = null
    ) {
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('academic_year_id', $currentYear->id ?? 0)
            ->where('active', true)
            ->first();

        if (!$currentYear || !$currentTerm) return collect();

        $query = Attendance::where('academic_year_id', $currentYear->id)
            ->where('term_id', $currentTerm->id)
            ->whereHas('curriculum', fn($q) => $q->where('name', $curriculumName));

        if ($curriculumName === '8-4-4' && $formId) {
            $query->where('form_id', $formId);
        } elseif ($curriculumName === 'CBC' && $gradeId) {
            $query->where('grade_id', $gradeId);
        }

        if (!empty($weekIds)) {
            $query->whereIn('week_id', $weekIds);
        }

        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to) $query->whereDate('created_at', '<=', $to);

        $attended = (clone $query)->whereIn('status', ['attended', 'makeup'])->count();
        $missed = (clone $query)->where('status', 'missed')->count();

        $weeks = $currentTerm ? Week::where('term_id', $currentTerm->id)->orderBy('id')->get() : collect();
        $weekData = [];
        foreach ($weeks as $week) {
            $weekQuery = (clone $query)->where('week_id', $week->id);
            $weekData[] = [
                'week_id' => $week->id,
                'week_name' => $week->name,
                'attended' => (clone $weekQuery)->whereIn('status', ['attended', 'makeup'])->count(),
                'missed' => (clone $weekQuery)->where('status', 'missed')->count(),
            ];
        }

        return [
            'curriculum' => $curriculumName,
            'total_attended' => $attended,
            'total_missed' => $missed,
            'weeks' => $weekData,
        ];
    }

    public function getStreamAttendance(
        string $curriculumName,
        ?int $formStreamId = null,
        ?int $gradeId = null,
        ?int $weekId = null
    ) {
        $currentYear = AcademicYear::where('active', true)->first();
        $currentTerm = Term::where('academic_year_id', $currentYear->id ?? 0)
            ->where('active', true)
            ->first();

        if (!$currentYear || !$currentTerm) return collect();

        $query = Attendance::where('academic_year_id', $currentYear->id)
            ->where('term_id', $currentTerm->id)
            ->whereHas('curriculum', fn($q) => $q->where('name', $curriculumName));

        if ($curriculumName === '8-4-4' && $formStreamId) {
            $query->where('form_stream_id', $formStreamId);
        } elseif ($curriculumName === 'CBC' && $gradeId) {
            $query->where('grade_id', $gradeId);
        }

        if ($weekId) {
            $query->where('week_id', $weekId);
        }

        $records = $query->with([
            'lesson',
            'lesson.subject',
            'lesson.learningArea',
            'teacher',
            'curriculum'
        ])->orderBy('created_at', 'desc')->get();

        return $records;
    }
}
