<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Events\MessageFailed;
use App\Models\EmailLog;

class LogFailedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageFailed $event)
    {
        $message = $event->message;

        $headers = $message->getHeaders();
        $recipients = [];

        // Manually parse the 'To' header
        if ($headers->has('To')) {
            $toHeader = $headers->get('To');
            foreach ($toHeader->getAddresses() as $address) {
                $recipients[] = $address->getAddress();
            }
        }

        $recipientEmails = !empty($recipients) ? implode(', ', $recipients) : 'No recipients found';

        \Log::info('Recipient Emails:', ['emails' => $recipientEmails]);

        $subject = $message->getSubject();
        $body = $message->getBody()->bodyToString();

        // Clean up the body by removing encoded tabs
        // $cleanedBody = str_replace('=09', '', $body);

        $errorMessage = $event->exception->getMessage();

        EmailLog::create([
            'recipient' => $recipientEmails,
            'subject' => $subject,
            'body' => $body,
            'status' => 'error',
            'error_message' => $errorMessage,
        ]);
    }
}
