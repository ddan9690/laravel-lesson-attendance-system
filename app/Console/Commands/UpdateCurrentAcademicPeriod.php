<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Week;
use Carbon\Carbon;

class UpdateCurrentAcademicPeriod extends Command
{
    protected $signature = 'academic:update-current';
    protected $description = 'Update the current academic year, term, and week automatically';

    public function handle()
    {
        $today = Carbon::today();

        // 1️⃣ Update current academic year
        $currentYear = AcademicYear::whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if (!$currentYear) {
            $this->info("No active academic year found for today ({$today->toDateString()}). Please check the academic years table.");
            return 0;
        }

        AcademicYear::where('active', true)->update(['active' => false]);
        $currentYear->update(['active' => true]);

        // 2️⃣ Update current term
        $currentTerm = Term::where('academic_year_id', $currentYear->id)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if (!$currentTerm) {
            $this->info("No active term found for today ({$today->toDateString()}) in academic year {$currentYear->year}.");
            return 0;
        }

        Term::where('academic_year_id', $currentYear->id)->where('active', true)->update(['active' => false]);
        $currentTerm->update(['active' => true]);

        // 3️⃣ Update current week
        $currentWeek = Week::where('term_id', $currentTerm->id)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if (!$currentWeek) {
            $this->info("No active week found for today ({$today->toDateString()}) in term {$currentTerm->name}.");
            return 0;
        }

        Week::where('term_id', $currentTerm->id)->where('active', true)->update(['active' => false]);
        $currentWeek->update(['active' => true]);

        $this->info("✅ Current academic year ({$currentYear->year}), term ({$currentTerm->name}), and week ({$currentWeek->name}) updated successfully.");
    }
}
