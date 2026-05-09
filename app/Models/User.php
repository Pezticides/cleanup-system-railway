<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'clean_up_team_id',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function assignedReports()
    {
        return $this->hasMany(CleanUpReport::class, 'assigned_user_id');
    }

    public function cleanUpTeam()
    {
        return $this->belongsTo(CleanUpTeam::class, 'clean_up_team_id');
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
