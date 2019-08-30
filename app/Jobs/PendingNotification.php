<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PendingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // protected $order_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $notification = [
        //     'notifyType' => 'new_order',
        //     'message' => ' Jobs Scheduler Test '.$this->order_id,
        //     'model' => 'order',
        //     'url' => '$order->id'
        // ];

        // User::find(1)->pushNotification($notification);

    }
}
