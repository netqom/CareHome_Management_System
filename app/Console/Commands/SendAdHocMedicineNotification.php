<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PatientActivity;
use App\Models\PatientLog;
use App\Models\Patient;
use Carbon\Carbon;
use App\Events\CreateNotification;
use App\Models\Notification;

class SendAdHocMedicineNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-ad-hoc-medicine-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Adhoc medicine notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $current_date = Carbon::now()->toDateString();
        $adhocs = PatientActivity::join('patient_logs as pl', 'pl.id', '=', 'patient_activities.log_id')->where(['pl.log_type'=>1, 'patient_activities.shift_id' => 5])->whereNull('patient_activities.staff_note')->select('pl.patient_id', 'patient_activities.field_id as medicine_id','patient_activities.created_by','patient_activities.id')->get();
        foreach($adhocs as $adhoc){
            $patient = Patient::find($adhoc->patient_id);
            $medicineName = medicineName($adhoc->medicine_id);
            $notificationCount = Notification::where('patient_id', $adhoc->patient_id)
                        ->where('item_type', 'adhoc_medicine_notification')
                        ->whereDate('created_at', $current_date)
                        ->count();

            if (!empty($patient->home_id) && $notificationCount < 3) {
                $notificationData['home_id'] = $patient->home_id;
                $notificationData['patient_id'] = $adhoc->patient_id;
                $notificationData['staff_id'] = $adhoc->created_by;
                $notificationData['item_id'] = $adhoc->id;
                $notificationData['item_type'] = 'adhoc_medicine_notification';
                $notificationData['message'] = 'Need to add staff note for '.$medicineName.'medicine for '.$patient->name.' patient.' ;
                // $notificationData['message'] = 'Add the incident report of patient '.$patient->name.'.';
                $notificationData['app_message'] = 'Need to add staff note for '.$medicineName.'medicine for '.$patient->name.' patient.' ;
                // $notificationData['created_by'] = Auth::user()->id;
                // $notificationData['updated_by'] = Auth::user()->id;
                $notificationData['created_at'] = now();
                $notificationData['updated_at'] = now();
                Notification::insert($notificationData);
            }
        }
        dd('success');
    }
}
