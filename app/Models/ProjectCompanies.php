<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCompanies extends Model
{
    //
    protected $guarded = [];
    public function projects()
    {
        return $this->hasMany(Projects::class,'company');
    }
    public function units()
    {
        return $this->hasManyThrough(
            'App\Models\Units',
            'App\Models\Projects',
            'company', // Local key on users table...
            'id', // Local key on users table...
        );
    }

}
