<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EnquiryNotification;

class EnquiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     private $enquiry;
     private $pageType;
     private $name;
     private $email;
     private $to;
     private $user;
     private $lawyer;
    public function __construct($enquiry, $pageType, $name, $email, $to, $user, $lawyer){
      $this->enquiry = $enquiry;
      $this->pageType = $pageType;
      $this->name = $name;
      $this->email = $email;
      $this->to = $to;
      $this->user = $user;
      $this->lawyer = $lawyer;
    }

    /**
     * Execute the job.
     */
    public function handle(){
      Notification::route('mail', [
        $this->email => $this->name,
      ])->notify(new EnquiryNotification($this->enquiry, $this->pageType, $this->to, $this->user, $this->lawyer));
    }
}
