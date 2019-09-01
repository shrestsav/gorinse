<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'area_id',
        'map_coordinates',
        'building_community',
        'type',
        'appartment_no',
        'remarks',
        'is_default',
    ];

    protected $casts = [
        'map_coordinates' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
