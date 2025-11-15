<?php

namespace App\Models;

use App\Models\Form;
use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormStream extends Model
{
    use HasFactory;

      protected $fillable = [
        'name',
        'curriculum_id',
        'form_id',
        'class_teacher_id',
    ];


    public function classTeacher()
    {
        return $this->belongsTo(User::class, 'class_teacher_id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

      public function students()
    {
        return $this->hasMany(Student::class, 'stream_id');
    }
}
