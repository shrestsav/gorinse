<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainArea extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['name'];
}
