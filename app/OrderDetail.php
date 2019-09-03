<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
    	'order_id',
		'PAT',
		'DAT',
		'PFC',
		'DAO',
		'PFO',
		'DTC',
		'PT',
		'PDR'
    ];
}
