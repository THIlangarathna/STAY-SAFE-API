<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
    protected $fillable = [
        'citizen_id', 'report', 'status',
    ];
}
