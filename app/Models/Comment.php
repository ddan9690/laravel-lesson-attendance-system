<?php

namespace App\Models;

use App\Models\Week;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    public function week()
    {
        return $this->belongsTo(Week::class);
    }
    public function user()
{
    return $this->belongsTo(User::class);
}
}
