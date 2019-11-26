<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoCash extends Model
{
	protected $fillable = ['user_id','Vcash'];
	
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
