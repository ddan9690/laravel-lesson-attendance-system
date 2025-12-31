<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Curriculum;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true), 
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
            'curriculum_id' => Curriculum::inRandomOrder()->first()->id,
        ];
    }
}
