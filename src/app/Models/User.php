<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_banned',
        'is_global_admin',
        'current_colocation_id',
        'reputation_score',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected static function booted()
    {
        static::creating(function ($user) {
            if (static::count() === 0) {
                $user->is_global_admin = true;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_global_admin'   => 'boolean',
            'is_banned'         => 'boolean',
            'is_admin'          => 'boolean',
        ];
    }
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
    // The colocation the user is currently active in
    public function currentColocation()
    {
        return $this->belongsTo(Colocation::class, 'current_colocation_id');
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    // Debts the user needs to pay
    public function debts()
    {
        return $this->hasMany(Payment::class, 'debtor_id')->where('status', 'pending');
    }
}
