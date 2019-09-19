<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppDefault extends Model
{
    protected $fillable = [
		'VAT',
		'delivery_charge',
		'EDT',
		'OTP_expiry',
		'order_time',
		'driver_notes',
		'FAQ_link',
		'online_chat',
		'hotline_contact',
		'company_email',
		'company_logo',
		'TACS',
		'FAQS',
		'app_rows',
		'sys_rows'
    ];
    
    protected $casts = [
        'order_time' => 'array',
        'driver_notes' => 'array',
        'online_chat' => 'array',
        'TACS' => 'array',
        'FAQS' => 'array',
    ];
}
