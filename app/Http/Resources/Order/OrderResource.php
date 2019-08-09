<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'customer_id' => $this->customer_id,
            'driver_id' => $this->driver_id,
            'type' => $this->type,
            'pick_location' => $this->pick_location,
        ];
    }
}
