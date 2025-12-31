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
}
