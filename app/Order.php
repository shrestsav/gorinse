<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'driver_id',
		'type',
		'pick_location',
		'pick_date',
		'pick_timerange',
		'drop_location',
        'drop_date',
        'drop_timerange',
        'payment',
		'status'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class,'driver_id');
    }

    public function pick_location_details()
    {
        return $this->belongsTo(UserAddress::class,'pick_location');
    }

    public function drop_location_details()
    {
        return $this->belongsTo(UserAddress::class,'drop_location');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
