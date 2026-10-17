<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConsultationCancelNotification;

class ConsultationCancelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $email_id;

    public $data;
    public $from;
    public $to;
    public $status;
    public $message;
    public function __construct($email_id, $from, $to, $data, $status, $message)
    {
        $this->email_id = $email_id;
        $this->from = $from;
        $this->to = $to;
        $this->data = $data;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      Notification::route('mail', [
        $this->email_id => ucwords($this->to),
      ])->notify(new ConsultationCancelNotification($this->data, $this->from, $this->to, $this->status, $this->message));
    }
}
