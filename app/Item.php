<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['category_id','name','description','price','status'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
