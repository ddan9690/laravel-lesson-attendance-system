<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

   protected $fillable = [
    'teacher_id',
    'lesson_id',
    'subject_id',
    'learning_area_id',
    'form_id',
    'form_stream_id',
    'grade_id',
    'grade_stream_id',
    'academic_year_id',
    'term_id',
    'week_id',
    'curriculum_id',
    'status',
    'notes',
    'captured_by',
];


    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function formStream()
    {
        return $this->belongsTo(FormStream::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function gradeStream()
    {
        return $this->belongsTo(GradeStream::class);
    }

    public function capturedBy()
    {
        return $this->belongsTo(User::class, 'captured_by');
    }

    public function learningArea()
    {
        return $this->belongsTo(LearningArea::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
