<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QR_Location extends Model
{
    protected $fillable = [
        'name', 'contact', 'address', 'latitude', 'longitude',
    ];
}
