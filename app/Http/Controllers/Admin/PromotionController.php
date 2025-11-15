<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\Grade;
use App\Models\Student;
use App\Models\GradeStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PromotionController
{
    public function promoteAll()
{
    $currentYear = now()->year;

    DB::transaction(function () use ($currentYear) {

        // Only real students
        $students = Student::where('status', 'active')
            ->whereNotNull('joined_academic_year_id')
            ->get();

        // Get all grades ordered by level
        $grades = Grade::orderBy('level')->get();
        $lowestGrade = $grades->first();

        foreach ($students as $student) {
            $nextGrade = Grade::where('level', $student->grade->level + 1)->first();

            if ($nextGrade) {
                $studentStreamName = optional($student->gradeStream)->name;

                $nextStream = GradeStream::where('grade_id', $nextGrade->id)
                    ->where('name', $studentStreamName)
                    ->first();

                // If no matching stream, assign first available stream in that grade
                if (!$nextStream) {
                    $nextStream = GradeStream::where('grade_id', $nextGrade->id)->first();
                }

                // Skip students with no streams in next grade
                if (!$nextStream) {
                    \Log::warning("Skipping promotion for {$student->name} (ID: {$student->id}) – no stream in next grade");
                    continue;
                }

                $student->grade_id = $nextGrade->id;
                $student->grade_stream_id = $nextStream->id;
                $student->save();
            } else {
                // Grade 12 → graduate
                $student->status = 'graduated';
                $student->graduation_year = $currentYear;
                $student->save();
            }
        }

        // Check if lowest grade already exists in curriculum
        $existingLowestGrade = Grade::where('curriculum_id', $lowestGrade->curriculum_id)
            ->where('level', $lowestGrade->level)
            ->first();

        if (!$existingLowestGrade) {
            // Only create if it doesn't exist
            $newGrade = Grade::create([
                'name' => $lowestGrade->name,
                'level' => $lowestGrade->level,
                'curriculum_id' => $lowestGrade->curriculum_id,
                'class_supervisor_id' => $lowestGrade->class_supervisor_id, // preserve supervisor
            ]);

            // Copy streams
            foreach ($lowestGrade->streams as $stream) {
                GradeStream::create([
                    'name' => $stream->name,
                    'grade_id' => $newGrade->id,
                    'class_teacher_id' => null,
                ]);
            }
        }
    });

    return redirect()->back()->with('success', 'All students have been promoted.');
}
}
