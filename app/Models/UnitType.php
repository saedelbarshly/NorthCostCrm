<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function units()
    {
        return $this->hasMany(Units::class,'type_id');
    }
}
