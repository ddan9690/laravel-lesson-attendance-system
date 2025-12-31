<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'curriculum',
        'subject_id',
        'form_id',
        'form_stream_id',
        'learning_area_id',
        'grade_id',
        'grade_stream_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function formStream()
    {
        return $this->belongsTo(FormStream::class);
    }

    public function learningArea()
    {
        return $this->belongsTo(LearningArea::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function gradeStream()
    {
        return $this->belongsTo(GradeStream::class);
    }
}

