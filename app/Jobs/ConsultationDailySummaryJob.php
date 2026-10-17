<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConsultationDailySummaryNotification;

class ConsultationDailySummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     private $appointmentsArray;
     private $email;
     private $name;
     private $to;
     private $user;
     private $astrologer;
    public function __construct($appointmentsArray, $email, $name, $to, $user, $astrologer){
      $this->appointmentsArray = $appointmentsArray;
      $this->email = $email;
      $this->name = $name;
      $this->to = $to;
      $this->user = $user;
      $this->astrologer = $astrologer;
    }

    /**
     * Execute the job.
     */
    public function handle(){
      Notification::route('mail', [
        $this->email => $this->name,
      ])->notify(new ConsultationDailySummaryNotification($this->appointmentsArray, $this->to, $this->user, $this->astrologer));
    }
}
