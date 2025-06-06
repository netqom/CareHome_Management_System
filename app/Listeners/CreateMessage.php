<?php

namespace App\Listeners;

use App\Events\CreateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Notification;
use App\Models\SubscriptionNotification;
use Auth;
use App\Services\FirebaseService;
use App\Models\User;
use App\Models\CareHome;
use App\Models\Chat;
use Illuminate\Support\Facades\Log;

class CreateMessage
{
    /**
     * Create the event listener.
     */
    public function __construct(FirebaseService $firebaseService)
    {
		$this->firebaseService = $firebaseService;
    }

    /**
     * Handle the event.
     */
    public function handle(CreateNotification $event): void
    {
		$type = $event->type;
		$data = $event->data;
		if($type == 'log_created' || $type == 'log_updated'){
			$this->patientLogCreatedUpdated($type, $data);
		}else if($type == 'patient_expense_added'){
			$this->patientExpenseAdded($type, $data);
		}else if($type == 'course_assigned' || $type == 'course_updated' || $type == 'course_certificate_uploaded' || $type=='coming_tarining_due_date'){
			$this->staffCourseAssignedOrUpdatd($type, $data);
		}else if($type == 'patient_created' || $type == 'patient_updated'){
			$this->patientCreatedOrUpdatd($type, $data);
		}else if($type == 'patient_discharged' || $type=='reject_patient_discharge_request'){
			$this->patientDischarged($type, $data);
		}else if($type == 'patient_delete_request'){
			$this->patientDeleteRequest($type, $data);
		}else if($type == 'patient_deleted'){
			$this->patientDeleted($type, $data);
		}else if($type == 'subscription_price_update'){
			$this->subscriptionPriceUpdate($type, $data);
		}else if($type == 'activity_assign_to_patient'){
			$this->activityAssignPatient($type, $data);
		}else if($type == 'task_created' || $type == 'task_updated' || $type=='coming_task_due_date'){
			$this->taskCreatedOrUpdatd($type, $data);
		}
		else if($type == 'activity_schedule_create' || $type == 'activity_schedule_update'){
			$this->activitySchedule($type, $data);
		}
		else if($type == 'patient_incident_added' || $type == 'patient_incident_update'){
			$this->incidentCreatedOrUpdated($type, $data);
		}
		else if($type == 'send_message'){
			$this->createChatMessage($type, $data);
		}
		else if($type == 'document_expiry_reminder'){
			$this->documentExpiryReminder($type, $data);
		}
		else if($type == 'staff_restore'){
			$this->staffRestore($type, $data);
		}
		else if($type == 'patient_restore'){
			$this->patientRestore($type, $data);
		}
    }
	


	public function documentExpiryReminder($type, $data)
	{
		$title ='Document Expiry Reminder';
       
		if($data->type==1)
		{

		}else{
			$userDetail=User::find($data->staff_id);
			$message= 'Staff document "' . $data->name . '" of "'.$userDetail->name.'" is expiring on ' . $data->expiry_date . '.';
			$app_message= 'Staff document "' .  $data->nam . '" of"'.$userDetail->name.'" is expiring on ' . $data->expiry_date . '.';
			$notification_data = [
				'home_id'     => $data->home_id,
				'patient_id'  => $data->id,
				'item_id'     => $data->id,
				'item_type'   => 'document_expiry_reminder',
				'message'     => $message,
				'app_message' => $app_message,
				'status'      => 1,
				'seen'        => 0,
			];
			Notification::create($notification_data);
			$getAllStaff = User::where(['home_id' => $data->home_id, 'status' => 1, 'id' => $userDetail->id])->orWhere('role_id',3)->pluck('id');
			
			foreach($getAllStaff as $key => $staff){
				try {
					$this->firebaseService->sendNotification($staff,$title , $app_message);
				} catch (\Exception $e) {
					// Log the error message for the specific user
					Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
					// Continue to the next user
					continue;
				}
				
				

			}
		}
	}
	public function patientDischarged($type, $data)
	{ 
		$patient_route = route('patients.show', $data->id);
		$staff_route   = route('users.show', $data->update_by->id);
		if($type=='patient_discharged')
		{
			$message       = 'Client <a target="_blank" href="'.$patient_route.'" class="noti-username">'.$data->name.'</a> has been discharged by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->update_by->name;
			$app_message   = 'Client  '.$data->name.' has been discharged by '.$data->update_by->name;
		}else{
			$message       = 'Client <a target="_blank" href="'.$patient_route.'" class="noti-username">'.$data->name.'</a> discharge request has been rejected by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->update_by->name;
			$app_message   = 'Client  '.$data->name.' discharge request has been rejected by '.$data->update_by->name;
		}
		
		
		$notification_data = [
            'home_id'     => $data->home_id,
			'patient_id'  => $data->id,
            'item_id'     => $data->id,
            'item_type'   => 'patient_log',
            'message'     => $message,
            'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		$getAllStaff = User::where('home_id', $data->home_id)
                   ->where('status', 1)
                   ->where(function ($query) {
                       $query->where('role_id', 4)
                             ->orWhere('role_id', 3);
                   })
                   ->pluck('id');
		
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff,'Client Discharge Request' , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}
	}
	
	public function patientLogCreatedUpdated($type, $data)
	{
		$type_array = explode('-', $type);
		$msg_type = $type_array[0] == 'log_created' ? 'added' : 'updated';
		
		$patient_route = route('patients.show', $data->patient->id);
		$staff_route   = route('users.show', $data->added_by->id);
		$message       = $type_array[1].' report '.$msg_type.' for client <a target="_blank" href="'.$patient_route.'" class="noti-username">'.$data->patient->name.'</a> by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->added_by->name.'</a>';
		$app_message   = $type_array[1].' report '.$msg_type.' for client '.$data->patient->name.' by '.$data->added_by->name;
		$notification_data = [
            'home_id'     => $data->patient->home_id,
			'patient_id'  => $data->patient->id,
            'item_id'     => $data->id,
            'item_type'   => 'patient_log',
            'message'     => $message,
            'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		//$getAllStaff = User::where(['home_id' => $data->patient->home_id, 'status' => 1, 'role_id' => 4])->orWhere('role_id',3)->pluck('id');
		$getAllStaff = User::where('home_id', $data->patient->home_id)
                   ->where('status', 1)
                   ->where(function ($query) {
                       $query->where('role_id', 4)
                             ->orWhere('role_id', 3);
                   })
                   ->pluck('id');
		
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff,$type_array[1].' report '.$msg_type , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}
	}
	
	public function patientExpenseAdded($type, $data)
	{
		$patient_route = $data->type == 1 ? route('patients.show', $data->item_id) : route('users.show', $data->item_id);
		$staff_route   = route('users.show', $data->added_by->id);
		$expense_type  = $data->expense_type == 1 ? 'General' : 'Specific';
		$type_name     = $data->type == 1 ? 'client' : 'staff';
		$name 		   = $data->type == 1 ? $data->patient->name : $data->user->name;
		$message       = $expense_type.' expense for '.$type_name.' <a target="_blank" href="'.$patient_route.'" class="noti-username">'.$name.'</a> added by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->added_by->name.'</a>';
		$app_message   = $expense_type.' expense for '.$type_name.' '.$name.' added by '.$data->added_by->name;
		$notification_data = [
            'home_id'     => $data->home_id,
			'item_id'     => $data->id,
			'patient_id'  => $data->type == 1 ? $data->item_id : 0,
			'staff_id'    => $data->type == 2 ? $data->item_id : 0,
			'item_type'   => $data->type == 1 ? 'patient_expense' : 'user_expense',
            'message'     => $message,
			'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		$getAllStaff = User::where('home_id', $data->home_id)
                   ->where('status', 1)
                   ->where(function ($query) {
                       $query->where('role_id', 4)
                             ->orWhere('role_id', 3);
                   })
                   ->pluck('id');
		
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff,$expense_type.' expense for '.$type_name , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}
	}
	
	public function staffCourseAssignedOrUpdatd($type, $data)
	{
		$manager_route = route('users.show', $data->added_by->id);
		$staff_route   = route('users.show', $data->staff->id);
		$msg_type      = $type == 'course_assigned' ? 'assigned' : 'updated';
		$msg_type = $type == 'course_assigned' ? 'assigned' : ($type == 'coming_training_due_date' ? 'Training Course Due Date' : 'updated');
		$message = $app_message = '';
		if($type == 'course_assigned'){
			$message = 'Training course '.$msg_type.' to staff member <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->staff->name.'</a> assigned by <a target="_blank" href="'.$manager_route.'" class="noti-username">'.$data->added_by->name.'</a>';
			$app_message = 'Training course '.$msg_type.' to staff member '.$data->staff->name.' assigned by '.$data->added_by->name;
		}else if($type == 'course_updated'){
			$message = 'Training course details updated for staff member <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->staff->name.'</a> updated by <a target="_blank" href="'.$manager_route.'" class="noti-username">'.$data->added_by->name.'</a>';
			$app_message = 'Training course details updated for staff member '.$data->staff->name.' updated by '.$data->added_by->name;
		}
		else if($type == 'course_certificate_uploaded'){
			$message = 'Training course completion certificate for staff member <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->staff->name.'</a> uploaded by <a target="_blank" href="'.$manager_route.'" class="noti-username">'.$data->added_by->name.'</a>';
			$app_message = 'Training course completion certificate for staff member '.$data->staff->name.' uploaded by '.$data->added_by->name;
		}
		else if($type == 'coming_training_due_date'){
			$message = 'Training course due date is coming for staff member <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->staff->name.'</a> uploaded by <a target="_blank" href="'.$manager_route.'" class="noti-username">'.$data->added_by->name.'</a>';
			$app_message = 'Training course due date is coming for staff member '.$data->staff->name.' uploaded by '.$data->added_by->name;
		}
		
		$notification_data = [
            'home_id'     => $data->staff->home_id,
			'item_id'     => $data->id,
			'staff_id'    => $data->user_id,
			'item_type'   => $type,
            'message'     => $message,
			'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		$msg_type      = $type == 'course_assigned' ? 'Assigned a course' : 'Updated a course';
		$msg_type = $type == 'course_assigned' ? 'Assigned a course' : ($type == 'coming_training_due_date' ? 'Training Course Due Date' : 'Updated a course');
		$title= $data->added_by->name. ' '.$msg_type;
        $body=$app_message;
		try {
			$this->firebaseService->sendNotification($data->user_id,$title , $app_message);
		} catch (\Exception $e) {
			// Log the error message for the specific user
			Log::error('Error sending notification to user ' . $data->user_id . ': ' . $e->getMessage());
			// Continue to the next user
			
		}
           
	}
	
	public function createChatMessage($type, $data)
	{
		
		$msg_type = $type == 'send_message' ? 'sent a message' : '';
		$patient_route = route('patients.show', $data->id);
		$chat =Chat::find($data->chat_id);
        $user_id=$chat->user_id;
        $userDetail=User::find($user_id);
        $SenderDetail=User::find($data->sender_id);
		$staff_route   = route('users.show', $SenderDetail->id);
		$message = '';

		$app_message = $SenderDetail->name.' '.$msg_type;
		$notification_data = [
            'home_id'     => $userDetail->home_id,
			'patient_id'  => 0,
            'item_id'     =>0,
            'item_type'   => $type,
            'message'     => $message,
            'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		
		try {
			$response = $this->firebaseService->sendNotification($user_id, $app_message , $data->message);
		} catch (\Exception $e) {
			// Log the error message for the specific user
			Log::error('Error sending notification to user ' . $data->user_id . ': ' . $e->getMessage());
			// Continue to the next user
			
		}
	}
	public function patientCreatedOrUpdatd($type, $data)
	{
		$msg_type = $type == 'patient_created' ? 'New client added' : 'Client data updated';
		$patient_route = route('patients.show', $data->id);
		$staff_route   = route('users.show', $data->added_by->id);
		$message = $msg_type.' <a target="_blank" href="'.$patient_route.'" class="noti-username">'.$data->name.'</a> by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->added_by->name.'</a>';
		$app_message = $msg_type.' '.$data->name.' by '.$data->added_by->name;
		$notification_data = [
            'home_id'     => $data->home_id,
			'patient_id'  => $data->id,
            'item_id'     => $data->id,
            'item_type'   => $type,
            'message'     => $message,
            'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		$getAllStaff = User::where('home_id', $data->home_id)
                   ->where('status', 1)
                   ->where(function ($query) {
                       $query->where('role_id', 4)
                             ->orWhere('role_id', 3);
                   })
                   ->pluck('id');
	
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff, $msg_type , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}
	}


	public function subscriptionPriceUpdate($type, $data){
		$subscription_route = route('subscription-manage',['id' => $data->care_home_id, 'upgrade_price' => 'yes']);
		// $message = '<p class="mr-5 mb-0">Your '.$data->type.' plan price has been updated with new price $'.$data->new_price.'. If you want to continue swap and upgrade your subscription. Please click on the here, otherwise your subscription has been canceled at the billing period end.</p><span class="ml-auto"><a target="_blank" href="'.$subscription_route.'" class="btn btn-primary btn-sm"> Continue </a></span>';

		$message = '<p class="mb-0">Your '.$data->type.' plan price has been updated with new price <strong>$'.$data->new_price.'</strong>. If you want to continue swap and upgrade your subscription. <br>Please click on the <a target="_blank" href="'.$subscription_route.'" class="font-bold"> Update Subscription </a>, otherwise your subscription has been canceled at the billing period end.</p>';
		$notification_data = [
            'home_id'     => $data->care_home_id,
            'item_id'     => $data->id,
            'item_type'   => $type,
            'message'     => $message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		SubscriptionNotification::create($notification_data);
	}

	public function activityAssignPatient($type, $data){
		$patient_route = route('patients.show', $data->patient_id);
		$staff_route   = route('users.show', $data->care_home_admin->id);
		$message       = ucfirst($data->getActivityName->name).' Activity has been assigned to client <a target="_blank" href="'.$patient_route.'" class="noti-username">'.$data->getPatientName->name.' by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->care_home_admin->name.'</a>';
		$app_message   = ucfirst($data->getActivityName->name).' Activity has been assigned to client '.$data->getPatientName->name.' by '.$data->care_home_admin->name;
		$notification_data = [
            'home_id'     => $data->home_id,
			'patient_id'  => $data->patient_id,
            'item_id'     => $data->id,
            'item_type'   => 'activity_assign_to_patient',
            'message'     => $message,
            'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		$getAllStaff = User::where(['home_id' => $data->home_id, 'status' => 1, 'role_id' => 4])->pluck('id');
		
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff, 'Activity assigned' , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}

	}

	public function activitySchedule($type, $data){
		$msg_type = $type == 'activity_schedule_create' ? 'created' : 'updated';
		$staff_route   = route('users.show', $data->care_home_admin->id);
		if($data->care_home_admin->role_id == 1)
		{
			$message       = ucfirst($data->name).' Activity has been '.$msg_type.' by '.$data->care_home_admin->name;
		}else{
			$message       = ucfirst($data->name).' Activity has been '.$msg_type.' by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->care_home_admin->name.'</a>';
		}
		$app_message   = ucfirst($data->name).' Activity has been '.$msg_type.' by '.$data->care_home_admin->name;
		$notification_data = [
            'home_id'     => $data->home_id ? $data->home_id : 0,
            'item_id'     => $data->id ? $data->id : 0,
            'item_type'   => $type,
            'message'     => $message,
            'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		$getAllStaff = User::where(['home_id' => $data->home_id, 'status' => 1, 'role_id' => 4])->pluck('id');
		
		
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff, 'Activity '.$msg_type , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}

	}

	public function taskCreatedOrUpdatd($type, $data)
	{
		$msg_type = $type == 'task_created' ? 'New task added' : ($type == 'coming_task_due_date' ? 'Task Due Date' : 'Task data updated');
		$task_route = route('tasks.show', $data->id);
		$staff_route   = route('users.show', $data->added_by->id);
		$message = $msg_type.' <a target="_blank" href="'.$task_route.'" class="noti-username">'.$data->title.'</a> by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->added_by->name.'</a>';
		$app_message = $msg_type.' '.$data->name.' by '.$data->added_by->name;
	
		if($type=='coming_task_due_date')
		{
			$message = $msg_type.' is coming  for <a target="_blank" href="'.$task_route.'" class="noti-username">'.$data->title.'</a>';
			$app_message = $msg_type.' is coming for'.$data->title;
			$notification_data = [
				'home_id'     => $data->home_id,
				'patient_id'  => $data->patient_id,
				'staff_id'    => $data->user_id,
				'item_id'     => $data->id,
				'item_type'   => $type,
				'message'     => $message,
				'app_message' => $app_message,
				'status'      => 1,
				'seen'        => 0,
			];
		}else{
			$notification_data = [
				'home_id'     => $data->home_id,
				'patient_id'  => $data->patient_id,
				'staff_id'    => $data->user_id,
				'item_id'     => $data->id,
				'item_type'   => $type,
				'message'     => $message,
				'app_message' => $app_message,
				'status'      => 1,
				'seen'        => 0,
				'created_by'  => Auth::user()->id,
				'updated_by'  => Auth::user()->id,
			];
		}
		
		Notification::create($notification_data);
		
		try {
			$response = $this->firebaseService->sendNotification($data->user_id, $msg_type , $app_message);
		} catch (\Exception $e) {
			// Log the error message for the specific user
			Log::error('Error sending notification to user ' . $data->user_id . ': ' . $e->getMessage());
			// Continue to the next user
			
		}
	}
	public function incidentCreatedOrUpdated($type, $data)
	{
		$msg_type = $type == 'patient_incident_added' ? 'New incident added' : 'Task incident updated';
		$task_route = route('patients-show-incident-detail', $data->id);
		$staff_route   = route('users.show', $data->incident_report_by);
		$message = $msg_type.' <a target="_blank" href="'.$task_route.'" class="noti-username">'.$data->title.'</a> by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->added_by->name.'</a>';
		$app_message = $msg_type.' '.$data->name.' by '.$data->added_by->name;
		$notification_data = [
            'home_id'     => $data->home_id,
			'patient_id'  => $data->patient_id,
			'staff_id'    => $data->incident_report_by,
            'item_id'     => $data->id,
            'item_type'   => $type,
            'message'     => $message,
            'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		$getAllStaff = User::where(['home_id' => $data->home_id, 'status' => 1, 'role_id' => 3])->pluck('id');
		
		
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff, $msg_type , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}
		
	}

	public function patientDeleteRequest($type, $data)
	{
		$patient_route = route('patients.show', $data->id);
		$staff_route   = route('users.show', $data->update_by->id);
		$message       = 'Client <a target="_blank" href="'.$patient_route.'" class="noti-username">'.$data->name.'</a> delete request has been sent by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->update_by->name;
		$app_message   = 'Client  '.$data->name.' delete request has been sent by '.$data->update_by->name;
		$notification_data = [
            'home_id'     => $data->home_id,
			'patient_id'  => $data->id,
            'item_id'     => $data->id,
            'item_type'   => $type,
            'message'     => $message,
            'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		$getAllStaff = User::where('home_id', $data->home_id)
                   ->where('status', 1)
                   ->where(function ($query) {
                       $query->where('role_id', 4)
                             ->orWhere('role_id', 3);
                   })
                   ->pluck('id');
		
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff,'Client Delete Request Sent' , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}
	}

	public function patientDeleted($type, $data)
	{
		$patient_route = route('patients.show', $data->id);
		$staff_route   = route('users.show', $data->update_by->id);
		$message       = 'Client <a target="_blank" href="'.$patient_route.'" class="noti-username">'.$data->name.'</a> deleted successfully by <a target="_blank" href="'.$staff_route.'" class="noti-username">'.$data->update_by->name;
		$app_message   = 'Client  '.$data->name.' deleted successfully by '.$data->update_by->name;
		$notification_data = [
            'home_id'     => $data->home_id,
			'patient_id'  => $data->id,
            'item_id'     => $data->id,
            'item_type'   => $type,
            'message'     => $message,
            'app_message' => $app_message,
            'status'      => 1,
            'seen'        => 0,
			'created_by'  => Auth::user()->id,
			'updated_by'  => Auth::user()->id,
        ];
		Notification::create($notification_data);
		$getAllStaff = User::where('home_id', $data->home_id)
                   ->where('status', 1)
                   ->where(function ($query) {
                       $query->where('role_id', 4)
                             ->orWhere('role_id', 3);
                   })
                   ->pluck('id');
		
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff,'Client Deleted' , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}
	}

	public function staffRestore($type, $data){
		// dd($data);
		$app_message   = 'Staff  '.$data->name.' has been restore successfully by '.$data->update_by->name;

		$getAllStaff = User::where('home_id', $data->home_id)
                   ->where('status', 1)
                   ->where(function ($query) {
                       $query->where('role_id', 3);
                   })
                   ->pluck('id');
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff,'Staff Restore' , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}
	}

	public function patientRestore($type, $data){
		$app_message   = 'Client  '.$data->name.' has been restore successfully by '.$data->update_by->name;

		$getAllStaff = User::where('home_id', $data->home_id)
                   ->where('status', 1)
                   ->where(function ($query) {
                       $query->where('role_id', 4)
                             ->orWhere('role_id', 3);
                   })
                   ->pluck('id');
		
		foreach($getAllStaff as $key => $staff){
			try {
				$response = $this->firebaseService->sendNotification($staff,'Client Restore' , $app_message);
			} catch (\Exception $e) {
				// Log the error message for the specific user
				Log::error('Error sending notification to user ' . $staff . ': ' . $e->getMessage());
				// Continue to the next user
				continue;
			}	

		}
	}

}
