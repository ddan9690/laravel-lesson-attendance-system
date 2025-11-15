<?php

namespace App\Models;

use App\Models\Form;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\LearningArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Curriculum extends Model
{
    use HasFactory;

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    // Grades under this curriculum (for CBC)
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function learningAreas()
    {
        return $this->hasMany(LearningArea::class);
    }
}
