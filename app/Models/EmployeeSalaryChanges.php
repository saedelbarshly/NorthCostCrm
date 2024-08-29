<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryChanges extends Model
{
    //
    protected $guarded = [];
    public function responsible()
    {
        return $this->belongsTo(User::class,'UID');
    }
}
