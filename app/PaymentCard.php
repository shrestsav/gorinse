<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentCard extends Model
{
    protected $fillable = [
    	'user_id',
    	'type',
    	'card_no',
    	'month_year',
    	'csv'
    ];
}
