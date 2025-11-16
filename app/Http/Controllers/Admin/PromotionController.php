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
        $currentMonth = Carbon::now()->month; 
        $currentYear = Carbon::now()->year;

        if (!in_array($currentMonth, [11, 12])) {
            return redirect()->back()->with('error', 'The operation was unsuccessful. Please contact the administrator for assistance.');
        }

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

                    if (!$nextStream) {
                        $nextStream = GradeStream::where('grade_id', $nextGrade->id)->first();
                    }

                    if (!$nextStream) {
                        Log::warning("Skipping promotion for {$student->name} (ID: {$student->id}) – no stream in next grade");
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

            $existingLowestGrade = Grade::where('curriculum_id', $lowestGrade->curriculum_id)
                ->where('level', $lowestGrade->level)
                ->first();

            if (!$existingLowestGrade) {
                $newGrade = Grade::create([
                    'name' => $lowestGrade->name,
                    'level' => $lowestGrade->level,
                    'curriculum_id' => $lowestGrade->curriculum_id,
                    'class_supervisor_id' => $lowestGrade->class_supervisor_id,
                ]);

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
