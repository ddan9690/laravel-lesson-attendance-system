<?php

namespace App\Models;

use App\Models\Clazz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stream extends Model
{
    use HasFactory;

    public function clazz()
    {
        return $this->belongsTo(Clazz::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'stream_id');
    }
}
