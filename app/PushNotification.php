<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $fillable = [
        'type',
        'subject',
        'message',
        'sent_to',
        'status'
    ];

    protected $casts = [
        'sent_to' => 'array',
    ];

}
