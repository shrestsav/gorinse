<?php

namespace App;

use App\Order;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $appends = ['can_delete'];
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

    /**
     * Get the can_delete flag for users.
     *
     * @return status
     */
    public function getCanDeleteAttribute()
    {
        $status = true;

        $check_if_used = Order::where('pick_location',$this->id)->orWhere('drop_location',$this->id);
        if($check_if_used->exists())
            $status = false;
        
        return $status;
    }
}
