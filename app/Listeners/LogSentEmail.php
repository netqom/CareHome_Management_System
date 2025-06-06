<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Events\MessageSent;
use App\Models\EmailLog;

class LogSentEmail
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
    public function handle(MessageSent $event)
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
    //    $cleanedBody = str_replace('=09', '', $body);
       $cleanedBody = $this->cleanEmailBody($body);

        EmailLog::create([
            'recipient' => $recipientEmails,
            'subject' => $subject,
            'body' => $cleanedBody,
            'status' => 'success',
        ]);
    }

    private function cleanEmailBody($body)
    {
        // Remove unwanted characters and placeholders
        $patterns = [
            '/=3D/',      // Remove encoded equals signs
            '/<=/',       // Remove encoded less-than signs
            // '/<!--/',     // Remove HTML comments
            '/\*[\|].*[\|]\*/', // Remove placeholders like *|MC_PREVIEW_TEXT|*
            '/=09/',            // Remove encoded tab characters    
            '/=/',              // Remove remaining equal signs (=)
            // '/\s{2,}/',         // Remove extra whitespace
        ];

        $replacements = [
            '',           // Replace =3D with an empty string
            '',           // Replace <= with an empty string
            // '',           // Replace <!-- with an empty string
            '',           // Remove placeholders
            '',           // Remove =09 (tab character)
            '',           // Remove remaining equal signs (=)
            // '',           // Remove extra whitespace
        ];

        // Apply replacements
        $cleanedBody = preg_replace($patterns, $replacements, $body);

        return $cleanedBody;
    }
}
