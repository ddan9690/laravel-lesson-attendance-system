<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Curriculum;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // Get 8-4-4 and CBC curriculum IDs
        $curricula = Curriculum::whereIn('name', ['8-4-4', 'CBC'])->get()->keyBy('name');

        // 8-4-4: create 10 lessons
        Lesson::factory()->count(10)->state(function () use ($curricula) {
            return [
                'curriculum_id' => $curricula['8-4-4']->id,
            ];
        })->create();

        // CBC: create 10 lessons
        Lesson::factory()->count(10)->state(function () use ($curricula) {
            return [
                'curriculum_id' => $curricula['CBC']->id,
            ];
        })->create();
    }
}
