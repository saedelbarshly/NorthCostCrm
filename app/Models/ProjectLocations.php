<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectLocations extends Model
{
    //
    protected $guarded = [];
    public function projects()
    {
        return $this->hasMany(Projects::class,'location');
    }
    public function units()
    {
        return $this->hasManyThrough(
            'App\Models\Units',
            'App\Models\Projects',
            'location', // Local key on users table...
            'id', // Local key on users table...
        );
    }
}
