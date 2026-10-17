<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderRequestNotification;

class OrderRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     public $email;
     public $order;
     public $orderRequest;
     public $consignment;
     public $request_type;//cancel, return
     public $status;//0->request, 1->accept, 2->reject
     public function __construct($email, $order, $orderRequest, $consignment, $request_type, $status)
     {
         $this->email = $email;
         $this->order = $order;
         $this->orderRequest = $orderRequest;
         $this->consignment = $consignment;
         $this->request_type = $request_type;
         $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      Notification::route('mail', [
        $this->email => $this->status?'User':'Admin',
      ])->notify(new OrderRequestNotification($this->order, $this->orderRequest, $this->consignment, $this->request_type, $this->status));
    }
}
