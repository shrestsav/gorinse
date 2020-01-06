<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','description','icon','status'];

    protected $appends = ['can_delete','icon_src'];

    protected $hidden = ['can_delete','icon_src'];

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

    public function getIconSrcAttribute()
    {
  		$src = $this->icon ? asset('files/categories/'.$this->icon) : asset('files/categories/no_image.png');
  		
        return $src;
    }
}
