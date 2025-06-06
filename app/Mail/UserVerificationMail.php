<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;

class UserVerificationMail extends VerifyEmail 
{
   use Queueable;
        public $user;            //you'll need this to address the user
    
        /**
         * Create a new notification instance.
         *
         * @return void
         */
        public function __construct($user='')
        {
            $this->user =  $user ?: Auth::user();         //if user is not supplied, get from session
        }
    
        /**
         * Get the notification's delivery channels.
         *
         * @param  mixed  $notifiable
         * @return array
         */
        public function via($notifiable)
        {
            return ['mail'];
        }
    
        /**
         * Get the mail representation of the notification.
         *
         * @param  mixed  $notifiable
         * @return \Illuminate\Notifications\Messages\MailMessage
         */
        public function toMail($notifiable)
        {
            // $actionUrl  = $this->verificationUrl($notifiable);     //verificationUrl required for the verification link
            $actionUrl  = url('/verify-email-test?token='.$this->user->token);    //verificationUrl required for the verification link
            $actionText  = 'Click here to verify your email';
            return (new MailMessage)->subject('Verify your account')->view(
                'emails.user-verify',
                [
                    'user'=> $this->user,
                    'actionText' => $actionText,
                    'actionUrl' => $actionUrl,
                ]);
        }
    
        /**
         * Get the array representation of the notification.
         *
         * @param  mixed  $notifiable
         * @return array
         */
        public function toArray($notifiable)
        {
            return [
                //
            ];
        }
}
