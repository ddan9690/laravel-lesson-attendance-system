<?php

namespace App\Models;

use App\Models\User;
use App\Models\Grade;
use App\Models\Student;
use App\Models\GradeStream;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'grade_id',
        'grade_stream_id',
        'student_name',
        'student_adm',
        'grade_name',
        'stream_name',
        'amount',
        'payment_type',
        'mpesa_transaction_number',
        'user_id',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function gradeStream()
    {
        return $this->belongsTo(GradeStream::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
