<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'location',
        'building_community',
        'type',
        'appartment_no',
        'remarks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
