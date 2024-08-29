<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneyTransfeers extends Model
{
    protected $guarded = [];
    //
    public function fromSafe()
    {
        return $this->belongsTo(SafesBanks::class,'from_safe_id');
    }
    public function toSafe()
    {
        return $this->belongsTo(SafesBanks::class,'to_safe_id');
    }
}
