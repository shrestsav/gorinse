<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id','gender','dob','home_address','main_area','photo','description','joined_date','documents'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
