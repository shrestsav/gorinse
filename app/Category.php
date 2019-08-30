<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','description','icon','status'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
