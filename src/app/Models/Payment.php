<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'colocation_id',
        'debtor_id',
        'creditor_id',
        'amount',
        'status',
        'paid_at',
    ];

    public function debtor()
    {
        return $this->belongsTo(User::class, 'debtor_id');
    }
    public function creditor()
    {
        return $this->belongsTo(User::class,'creditor_id');
    }
}
