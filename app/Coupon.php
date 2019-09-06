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
		'valid_from',
		'valid_to',
		'status'
    ];
}
