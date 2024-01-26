<?php

namespace App\Models;

use App\Models\Stream;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clazz extends Model
{
    use HasFactory;

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'clazz_id');
    }
}
