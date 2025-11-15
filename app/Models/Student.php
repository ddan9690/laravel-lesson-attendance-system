<?php

namespace App\Models;

use App\Models\Term;
use App\Models\User;
use App\Models\Grade;
use App\Models\GradeStream;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'adm',
        'phone',
        'grade_id',
        'grade_stream_id',
        'status',
        'joined_academic_year_id',
        'joined_term_id',
        'left_academic_year_id',
        'left_term_id',
        'graduation_year',
        'added_by',
    ];

     public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function gradeStream()
    {
        return $this->belongsTo(GradeStream::class);
    }

   public function joinedAcademicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'joined_academic_year_id');
    }

    public function joinedTerm()
    {
        return $this->belongsTo(Term::class, 'joined_term_id');
    }

    public function leftAcademicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'left_academic_year_id');
    }

    public function leftTerm()
    {
        return $this->belongsTo(Term::class, 'left_term_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    
}
