<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'curriculum_id',
    ];

     public function learningArea()
    {
        return $this->belongsTo(LearningArea::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
