<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BuySubscriptionPlanMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

   /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->data['subject'])->view('emails.buy-subscription-email')->with('data', $this->data);
    }
}
