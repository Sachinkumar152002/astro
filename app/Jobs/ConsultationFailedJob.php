<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\{ConsultationFailedNotification};

class ConsultationFailedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $data;
    private $name;
    private $email;
    private $to;
    private $user;
    private $astrologer;
    public function __construct($data, $name, $email, $to, $user, $astrologer)
    {
        $this->data = $data;
        $this->name = $name;
        $this->email = $email;
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
        ])->notify(new ConsultationFailedNotification($this->data, $this->to, $this->user, $this->astrologer));
    }
}
