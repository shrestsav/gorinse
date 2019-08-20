<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppDefault extends Model
{
    protected $fillable = [
		'VAT',
		'delivery_charge',
		'OTP_expiry',
		'order_time',
		'driver_notes',
		'FAQ_link',
		'online_chat',
		'hotline_contact',
		'company_email',
		'company_logo'
    ];
}
