<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tasks;
use App\Models\Training;
use Carbon\Carbon;
use App\Events\CreateNotification;
use App\Models\StaffAssignedTraining;

class SendDueDateNotifications extends Command
{
    protected $signature = 'notifications:send-due-date';
    protected $description = 'Send notifications for tasks and trainings due within one week';

    public function handle()
    {
        // Calculate the due date one week from now
        $dueDate = Carbon::now()->addWeek();
        $oneDayDue=Carbon::now()->addDay(1);

        // Retrieve tasks due within one week
        $tasks = Tasks::where('due_date', '<=', $oneDayDue)->orWhere('end_date','<=',$oneDayDue)->get();

        // Retrieve trainings due within one week
        $trainings = StaffAssignedTraining::where('due_date', '<=', $oneDayDue)->get();

        // Send notifications for tasks
        foreach ($tasks as $task) {
            event(new CreateNotification('coming_task_due_date', $task));
        }

        // Send notifications for trainings
        foreach ($trainings as $training) {
            event(new CreateNotification('coming_training_due_date', $training));
        }

        $this->info('Notifications sent successfully.');
    }
}
