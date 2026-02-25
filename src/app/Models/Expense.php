<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public function users()
    {
        $this->belongsTo(User::class);
    }
    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
    public function colocations()
    {
        return $this->belongsTo(Colocation::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
