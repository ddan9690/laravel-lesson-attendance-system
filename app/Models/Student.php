<?php

namespace App\Models;

use App\Models\Clazz;
use App\Models\Stream;
use App\Models\StudentSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'adm',
        'clazz_id',
        'stream_id',
    ];

    public function clazz()
    {
        return $this->belongsTo(Clazz::class, 'clazz_id');
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'studentsubjects', 'student_id', 'subject_id');
    }

    public function studentsubjects()
    {
        return $this->hasMany(StudentSubject::class);
    }



}
