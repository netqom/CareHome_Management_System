<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PatientActivityField;
use App\Models\PatientActivity;
use App\Models\PatientLog;
use App\Models\Patient;
use App\Models\User;
use Str, Arr, Auth;
use Carbon\Carbon;
use App\Events\CreateNotification;
use App\Models\Notification;
use App\Services\FirebaseService;

class SendNotificationAddIncidentReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:send-notification-add-incident-report';
    protected $signature = 'notifications:send-notification-add-incident-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification to staff to add Patient Incident report';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle() 
    {
        \Log::info(['hello']);
        $activity_form  = PatientActivityField::with('subchilds')->where(['parent_id' => 0])->get()->toArray();
		$incidents =[];
		foreach ($activity_form as $item) {
			foreach($item['subchilds'] as  $subItem){
				if ($subItem['name'] === 'Incidents') {
					foreach($subItem['subchilds'] as  $subChildItem){
						if($subChildItem['name'] === 'Reported'){
							foreach($subChildItem['options'] as $option)
							if ($option['name'] === 'Yes') {
								$incidents[] = $option;
							}
						}
					}
				}
			}
		}
		$current_date = Carbon::now()->toDateString();
		$log_data = PatientLog::where('report_date', $current_date)->get();

		$log_data->transform(function ($item) {
			$itemArray = $item->toArray();
			unset($itemArray['images']);
			return $itemArray;
		});		
		foreach($log_data as $data){
			$patient = Patient::find($data['patient_id']);
			foreach($incidents as $incident){
				$submited_form  = PatientActivity::where(['log_id' => $data['id'], 'field_id' => $incident['field_id']])->first();
				if($submited_form){
                    $notificationCount = Notification::where('patient_id', $data['patient_id'])
                        ->where('item_type', 'patient_incident_report')
                        ->whereDate('created_at', $current_date)
                        ->count();
                    \Log::info(['notificationCount',$notificationCount]);
                    if ($notificationCount < 3) {
                        $notificationData['home_id'] = $patient->home_id;
                        $notificationData['patient_id'] = $data['patient_id'];
                        $notificationData['staff_id'] = $submited_form->created_by;
                        $notificationData['item_id'] = $submited_form->id;
                        $notificationData['item_type'] = 'patient_incident_report';
                        // $notificationData['message'] = 'Add the incident report of patient '.$patient->name.'.';
                        $notificationData['app_message'] = "Add the incident report of patient '.$patient->name.'.";
                        // $notificationData['created_by'] = Auth::user()->id;
                        // $notificationData['updated_by'] = Auth::user()->id;
                        $notificationData['created_at'] = now();
                        $notificationData['updated_at'] = now();
                        Notification::insert($notificationData);

                        try {
                            $firebaseService = new FirebaseService();
                            $response = $firebaseService->sendNotification($submited_form->created_by, 'Patient Incident Report', "Add the incident report of patient '.$patient->name.'.");
                        } catch (\Exception $e) {
                            Log::error('Error sending notification to user ' . $submited_form->created_by . ': ' . $e->getMessage());
                            continue;
                        }                        
                    }
				}
			}
		}
    }
}
