<?php

namespace App\Models;

use App\Models\Term;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYear extends Model
{
    use HasFactory;

     public function terms()
    {
        return $this->hasMany(Term::class);
    }
}
