<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\{WithdrawNotification};

class WithdrawNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     private $data;
     private $name;
     private $email;
     private $user;
     private $astrologer;
     private $to;
     private $status;
    public function __construct($data, $name, $email, $user, $astrologer, $to, $status){
      $this->data = $data;
      $this->name = $name;
      $this->email = $email;
      $this->user = $user;
      $this->astrologer = $astrologer;
      $this->to = $to;
      $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(){
      Notification::route('mail', [
        $this->email => $this->name,
      ])->notify(new WithdrawNotification($this->data, $this->user, $this->astrologer, $this->to, $this->status));
    }
}
