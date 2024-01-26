<?php

namespace App\Models;

use App\Models\Lesson;
use App\Models\Student;
use App\Models\StudentSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }


    public function students()
    {
        return $this->belongsToMany(Student::class, 'studentsubjects', 'subject_id', 'student_id');
    }



}
