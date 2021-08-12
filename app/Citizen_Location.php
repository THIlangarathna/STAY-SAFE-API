<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Citizen_Location extends Model
{
    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
    protected $fillable = [
        'citizen_id','latitude', 'longitude',
    ];
}
