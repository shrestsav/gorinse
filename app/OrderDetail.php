<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
    	'order_id',
    	'PAB',
    	'DAB',
		'PAT',
		'DAT',
		'PFC',
		'DAO',
		'PFO',
		'DTC',
		'payment_type',
		'invoice_id',
		'PT',
		'PDR'
    ];
}
