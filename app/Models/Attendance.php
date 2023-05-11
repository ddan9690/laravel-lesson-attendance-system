<?php

namespace App\Models;

use App\Models\Form;
use App\Models\User;
use App\Models\Week;
use App\Models\Lesson;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'form_id',
        'subject_id',
        'lesson_id',
        'week_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
