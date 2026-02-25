<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [
        'name',
        'token',
        'status',
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships')
                    ->withPivot('role', 'join_at', 'left_at')
                    ->wherePivot('left_at', 'null');
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    //this for get the owner quickly
    public function owner()
    {
        return $this->members()->withPivot('role', 'owner')->first();
    }
}
