<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'amount',
        'user_id',
        'colocation_id',
        'category_id',
        'title',
        'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
}
