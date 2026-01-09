<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Week;

class AcademicContextService
{
    public function getCurrentContext(): array
    {
        $today = Carbon::today();

        $year = AcademicYear::where('active', true)->first();

        $term = null;
        $week = null;

        if ($year) {
            $term = Term::where('active', true)
                ->where('academic_year_id', $year->id)
                ->first();
        }

        if ($term) {
            $week = Week::where('term_id', $term->id)
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->first();
        }

        return [
            'academicYear' => $year,
            'academicTerm' => $term,
            'academicWeek' => $week,
        ];
    }
}
