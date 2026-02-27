<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [
        'name',
        'status',
        'invite_token',
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->withPivot('role', 'join_at', 'left_at')
            ->wherePivotNull('left_at');
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
        return $this->members()->wherePivot('role', 'owner')->first();
    }
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
