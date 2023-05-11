<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Week extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
