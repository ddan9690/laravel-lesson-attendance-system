<?php

namespace App\Models;

use App\Models\User;
use App\Models\Student;
use App\Models\Curriculum;
use App\Models\GradeStream;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'curriculum_id',
        'class_supervisor_id',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    // Optional supervisor
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'class_supervisor_id');
    }

    // Grade has many streams
    public function streams()
    {
        return $this->hasMany(GradeStream::class);
    }

     public function students()
    {
        return $this->hasMany(Student::class, 'grade_id');
    }

  
}
