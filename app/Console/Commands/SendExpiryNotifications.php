<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ManageHomeDocument;
use Carbon\Carbon;
use App\Events\CreateNotification;

class SendExpiryNotifications extends Command
{
    protected $signature = 'notifications:send-expiry';
    protected $description = 'Send notifications for documents expiring within one week';

    protected $notificationService;

   
    public function handle()
    {
        // Calculate the date one week from now
        $expiryDate = Carbon::now()->addWeek();

        // Retrieve documents expiring within one week
        $documents = ManageHomeDocument::where('expiry_date', '<=', $expiryDate)->where('is_expiry_applicable',1)->get();

        // Send notifications for documents
        foreach ($documents as $document) {
            event(new CreateNotification('document_expiry_reminder', $document));
           
        }

        $this->info('Expiry notifications sent successfully.');
    }
}

