<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRegistrationNotification;

class UserRegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $email;
    public $name;
    public $to;
    public $user;
    public function __construct($email,  $name, $to, $user)
    {
      $this->email = $email;
      $this->name = $name;
      $this->to = $to;
      $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
      Notification::route('mail', [
        $this->email => $this->name,
      ])->notify(new UserRegistrationNotification($this->to, $this->user));
    }
}
