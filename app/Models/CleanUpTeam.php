<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}