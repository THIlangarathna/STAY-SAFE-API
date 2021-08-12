<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'user_id', 'dob', 'sex', 'mobile', 'address', 'profession', 'affiliation', 'cl_latitude', 'cl_longitude',
        'health_status',
    ];
    public function report()
    {
        return $this->hasMany(Report::class);
    }
    public function citizen_locations()
    {
        return $this->hasMany(Citizen_Location::class);
    }
}
