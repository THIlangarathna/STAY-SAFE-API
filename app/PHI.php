<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PHI extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'user_id', 'phi_id', 'region', 'contact', 'status',
    ];
}
