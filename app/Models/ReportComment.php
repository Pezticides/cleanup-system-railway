<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    protected $fillable = ['clean_up_report_id', 'user_id', 'body', 'photo'];

    public function report()
    {
        return $this->belongsTo(CleanUpReport::class, 'clean_up_report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}
