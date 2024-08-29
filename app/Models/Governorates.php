<?php

namespace App\Models;

use App\Models\Units;
use Illuminate\Database\Eloquent\Model;

class Governorates extends Model
{
    //
    protected $guarded = [];
    public function cities()
    {
        return $this->hasMany(Cities::class,'GovernorateID');
    }
    public function apiData($lang)
    {
        return [
            'id' => $this->id,
            'name' => $this['name']
        ];
    }

    public function units()
    {
        return $this->hasMany(Units::class,'governorate_id');
    }

}
