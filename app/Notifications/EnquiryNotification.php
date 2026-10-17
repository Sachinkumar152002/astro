<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnquiryNotification extends Notification
{
  use Queueable;
  /**
   * Create a new notification instance.
   */
    private $enquiry;
    private $pageType;
    private $to;
    private $user;
    private $lawyer;
    public function __construct($enquiry, $pageType, $to, $user, $lawyer)
    {
        $this->enquiry = $enquiry;
        $this->pageType = $pageType;
        $this->to = $to;
        $this->user = $user;
        $this->lawyer = $lawyer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
      $enquiry = $this->enquiry;
      $pageType = $this->pageType;
      $to = $this->to;
      $user = $this->user;
      $lawyer = $this->lawyer;
      $subject = '';
      $view = '';
      if($pageType=='hire_a_lawyer'){
        if($to=='user'){
          $subject = config('app.name').' - Your Case Submitted';
          $view = themeView().'emails.enquiry.hire_a_lawyer.user';
        }elseif($to=='lawyer'){
          $subject = config('app.name').' - New Case Submitted – Ready to Review & Quote';
          $view = themeView().'emails.enquiry.hire_a_lawyer.lawyer';
        }elseif($to=='admin'){
          $subject = config('app.name').' - New Case Submitted';
          $view = themeView().'emails.enquiry.hire_a_lawyer.admin';
        }
      }elseif($pageType=='ask_a_question'){
        if($to=='user'){
          $subject = config('app.name').' - Your Question Has Been Posted';
          $view = themeView().'emails.enquiry.ask_a_question.user';
        }elseif($to=='lawyer'){
          $subject = config('app.name').' - New Legal Question Posted';
          $view = themeView().'emails.enquiry.ask_a_question.lawyer';
        }elseif($to=='admin'){
          $subject = config('app.name').' - New Question Posted - Awaiting Review';
          $view = themeView().'emails.enquiry.ask_a_question.admin';
        }
      }elseif($pageType=='ask_a_question_published'){
        if($to=='user'){
          $subject = config('app.name').' - Your Question is Now Live';
          $view = themeView().'emails.enquiry.ask_a_question_published.user';
        }
      }elseif($pageType=='ask_a_question_answer'){
        if($to=='user'){
          $subject = config('app.name').' - A Lawyer Has Answered Your Question';
          $view = themeView().'emails.enquiry.ask_a_question_answer.user';
        }
      }elseif($pageType=='quotation_submitted'){
        $subject = config('app.name').' - A Lawyer Has Responded to Your Case on Advolive';
        $view = themeView().'emails.enquiry.quotation_submitted.user';
      }elseif($pageType=='purchase_quotation'){
        if($to=='user'){
          $subject = config('app.name').' - A Lawyer Has Responded to Your Case on Advolive';
          $view = themeView().'emails.enquiry.purchase_quotation.user';
        }elseif($to=='lawyer'){
          $subject = config('app.name').' - Congratulations! You’ve Been Hired for a New Case';
          $view = themeView().'emails.enquiry.purchase_quotation.lawyer';
        }elseif($to=='admin'){
          $subject = config('app.name').' - Congratulations! You’ve Been Hired for a New Case';
          $view = themeView().'emails.enquiry.purchase_quotation.admin';
        }
      }elseif($pageType=='purchase_lead'){
        if($to=='user'){
          $subject = config('app.name').' - A Lawyer Has Responded to Your Case on Advolive';
          $view = themeView().'emails.enquiry.purchase_lead.user';
        }elseif($to=='lawyer'){
          $subject = config('app.name').' -  Congratulations! You’ve Purchased A New Lead';
          $view = themeView().'emails.enquiry.purchase_lead.lawyer';
        }elseif($to=='admin'){
          $subject = config('app.name').' -  Lawyer '.$lawyer->display_name.' Purchased Lead for User '.$user->name;
          $view = themeView().'emails.enquiry.purchase_lead.admin';
        }
      }elseif($pageType=='send_lead_confirmation'){
        if($to=='user'){
          $subject = config('app.name').' - Following Up on Your Legal Consultation';
          $view = themeView().'emails.enquiry.send_lead_confirmation.user';
        }
      }
      return (new MailMessage)
        ->subject($subject)
        ->view($view , compact('enquiry', 'user', 'lawyer'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
