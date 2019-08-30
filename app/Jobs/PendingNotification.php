<?php

namespace App\Jobs;

use App\Order;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PendingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checkStatus = Order::find($this->order_id)->status;
        
        $notification = [
            'notifyType' => 'pending_time_exceeded',
            'message' => $checkStatus.' Pending status exceeded for order '.$this->order_id,
            'model' => 'order',
            'url' => $this->order_id
        ];

        if($checkStatus==0)
            User::find(1)->pushNotification($notification);
    }
}
