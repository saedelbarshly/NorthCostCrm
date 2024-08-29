<?php

namespace App\Models;

use App\Models\Units;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Broker extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function units()
    {
        return $this->hasMany(Units::class,'broker_id');
    }
}
