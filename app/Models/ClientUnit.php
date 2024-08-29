<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientUnit extends Model
{
    //
    protected $guarded = [];
    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }
    public function unit()
    {
        return $this->belongsTo(Units::class, 'unit_id');
    }
}
