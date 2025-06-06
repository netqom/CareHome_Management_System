<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        // Add your command classes here
        Commands\SendNotificationAddIncidentReport::class,
        Commands\SendMedicineReportNotification::class,
        Commands\CheckSubscriptionStatus::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('app:send-notification-add-incident-report')->everySecond();
        $schedule->command('app:send-ad-hoc-medicine-notification')->everyTenMinutes();
        $schedule->command('notifications:send-notification-add-incident-report')->everyTenMinutes();
        $schedule->command('notifications:send-due-date')->daily();
        $schedule->command('notifications:send-expiry')->daily();
        $schedule->command('notifications:send-subscription-expiry')->daily();
        $schedule->command('notifications:send-medicine-report-notification')->daily();
        $schedule->command('app:check-subscription-status')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
