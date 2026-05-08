<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleanUpReport extends Model
{
    protected $fillable = [
        'reporter_name',
        'location',
        'latitude',
        'longitude',
        'concern_type',
        'description',
        'photo',
        'status',
        'clean_up_team_id',
        'assigned_user_id',
    ];

    public function team()
    {
        return $this->belongsTo(CleanUpTeam::class, 'clean_up_team_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class, 'clean_up_report_id')->latest();
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function hasCoordinates()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }
}
