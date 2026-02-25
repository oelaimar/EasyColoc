<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function debtor()
    {
        return $this->belongsTo(User::class, 'debtor_id');
    }
    public function creditor()
    {
        return $this->belongsTo(User::class,'creditor_id');
    }
}
