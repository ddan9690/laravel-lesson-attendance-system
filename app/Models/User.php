<?php

namespace App\Models;

use App\Models\Form;
use App\Models\Lesson;
use App\Models\Attendance;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'code',
        'slug',
        'password',
        'role',
        'last_login',
        'bukua_user_id',
        'bukua_access_token',
        'bukua_refresh_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Booted method to assign default role
     */
    protected static function booted()
    {
        static::created(function ($user) {
           
            if (!$user->roles()->exists()) {
                $user->assignRole('teacher');
            }
        });
    }
}
