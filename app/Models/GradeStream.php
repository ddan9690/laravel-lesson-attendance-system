<?php

namespace App\Models;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradeStream extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'curriculum_id',
        'grade_id',
        'class_teacher_id',
    ];

    public function classTeacher()
    {
        return $this->belongsTo(User::class, 'class_teacher_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function students()
{
    return $this->hasMany(Student::class, 'grade_stream_id');
}
}
