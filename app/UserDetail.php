<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'gender',
        'dob',
        'home_address',
        'area_id',
        'photo',
        'description',
        'joined_date',
        'documents',
        'referral_id',
        'referred_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driverMainArea()
    {
        return $this->belongsTo(MainArea::class,'area_id');
    }
}
