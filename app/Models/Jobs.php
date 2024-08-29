<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    protected $guarded = [];
    public function users()
    {
        return $this->hasManyThrough(
            'App\Models\User',
            'App\Models\EmploymentProfile',
            'EmployeeID', // Local key on users table...
            'id', // Local key on users table...
        );
    }
}
