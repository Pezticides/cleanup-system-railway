<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CleanUpReport;

class CleanUpTeam extends Model
{
    protected $fillable = [
        'team_name',
        'contact_number',
        'assigned_area',
    ];

    public function reports()
    {
        return $this->hasMany(CleanUpReport::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'clean_up_team_id');
    }
}