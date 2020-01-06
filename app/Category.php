<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','description','icon','status'];

    protected $appends = ['can_delete'];

    protected $hidden = ['can_delete'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get the can_delete flag for users.
     *
     * @return status
     */
    public function getCanDeleteAttribute()
    {
        $status = true;

        if(count($this->items)){
            $status = false;
        }
        
        return $status;
    }
}
