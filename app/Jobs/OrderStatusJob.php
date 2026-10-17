<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     public $order;
     public $user_type;
    public function __construct($order, $user_type)
    {
        $this->order = $order;
        $this->user_type = $user_type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->order->sendOrderStatusNotification($this->user_type);
    }
}
