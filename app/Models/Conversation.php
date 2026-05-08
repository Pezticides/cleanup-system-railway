<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Conversation extends Model
{
    protected $fillable = ['name', 'is_group', 'created_by'];
    protected $casts = ['is_group' => 'boolean'];
    public function users(){ return $this->belongsToMany(User::class)->withTimestamps(); }
    public function messages(){ return $this->hasMany(Message::class); }
    public function creator(){ return $this->belongsTo(User::class, 'created_by'); }
}
