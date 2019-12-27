<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'code',
		'description',
		'discount',
		'type',
		'coupon_type',
		'valid_from',
		'valid_to',
		'status'
    ];

    protected $appends = ['redeemed','total_redeems'];
    protected $hidden = ['redeemed','total_redeems'];

    public function userWithAccess()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getRedeemedAttribute($value)
    {
    	if($this->coupon_type=3){
    		$redeemed = Order::where('customer_id',$this->user_id)
    						 ->where('coupon',$this->code)
    						 ->exists();

    		if($redeemed){
    			return true;
    		}
    	}

        return false;
    }

    public function getTotalRedeemsAttribute($value)
    {
    	$redeems = 0;
    	if($this->coupon_type=1 || $this->coupon_type=2){
    		$redeems = Order::where('coupon',$this->code)
    						 ->get()
    						 ->count();

    	}

        return $redeems;
    }
}
