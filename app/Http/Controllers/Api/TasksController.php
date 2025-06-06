<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use App\Models\Tasks;
use Str, Arr, Auth;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Notification;

class TasksController extends Controller
{
    private $users;
    private $tasks;
    private $patients;
	private $chats;
	private $chat_message;
	private $notifications;

    /**
    * Constructor Instance
    */
	public function __construct(User $users, Tasks $tasks, Notification $notifications,Patient $patients,Chat $chats, ChatMessage $chat_message)
	{
		$this->users    = $users;
		$this->tasks    = $tasks;
        $this->patients = $patients;
		$this->chats        = $chats;
		$this->chat_message = $chat_message;
		$this->notifications      = $notifications;
	}

    public function getAssignedTaskList(Request $request)
    { 
		$data = $this->tasks->getAssignedTasksList($request); 

		// echo $request->id.'----'.$data;die(); 
		$total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
		[$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		
		$records->transform(function ($record) {
			
			// $record->task_status_name = $record->task_status == 1 ? 'Complete' : 'Incomplete';
			$record->task_status_name = $record->task_status == '1' ? 'Complete' : ($record->task_status == '2' ? 'Inprogress' : ($record->task_status == '0' ? 'Incomplete' : null));
			$record->task_type_name = $record->task_type == 1 ? 'Continuous' : 'One Time'; // Modify as per your actual logic
			$record->continuous_type_name = $record->continuous_type == 0 ? 'Weekly' : 'Monthly';
			$record->task_remarks = $record->task_remarks;
			// $record->task_remarks->marked_by_name = $record->task_remarks->added_by->name;
			$selected_week_days = json_decode($record->selected_week_days);
			if ($selected_week_days !== null) {
			// Initialize an array to store the result
				$days_with_selected = [];

				// Define all days of the week
				$days_of_week = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

				// Iterate over each day of the week
				foreach ($days_of_week as $day) {
					// Check if the current day is selected
					$selected = in_array($day, $selected_week_days);

					// Add the day along with its selection status to the result array
					$days_with_selected[] = [
						"key" => $day,
						"value" => $day,
						"name" => $day,
						"selected" => $selected,
					];
				}

				$record->selected_week_days_data = $days_with_selected;
				$record->selected_week_days = $selected_week_days;
			}

			$selected_month_dates = json_decode($record->selected_month_dates);
			if($selected_month_dates !== null){
				// Initialize an array to store the result
				$dates_with_selected = [];

				// Iterate over each day of the month (assuming 31 days)
				for ($date = 1; $date <= 31; $date++) {
					// Check if the current day is selected
					$selected = in_array(strval($date), $selected_month_dates);
			
					// Add the day along with its selection status to the result array
					$dates_with_selected[] = [
						"key" => $date,
						"value" => $date,
						"name" => $date,
						"selected" => $selected,
					];
				}
			
				$record->selected_month_dates_data = $dates_with_selected;
				$record->selected_month_dates = $selected_month_dates;
			}
			$today = \Carbon\Carbon::today()->toDateString(); // Get today's date in YYYY-MM-DD format
			$record->disabled = false;

			// Check if today's date exists in any of the appointment_date entries in task_remarks array
			foreach ($record->task_remarks as $remark) {
				if (isset($remark['created_at'])) {
					// Extract the date part from created_at
					$createdDate = \Carbon\Carbon::parse($remark['created_at'])->toDateString();
				
					if ($createdDate === $today && $remark->status!=2) {
						$record->disabled = true;
						break; // No need to continue checking once we find today's date
					}
				}
			}
			return $record;
		}); 
		if(Auth::user()->role_id==4)
		{
		if(!$records->isEmpty())
			{
			foreach ($records as $act) {
				$records = $records->filter(function ($act) {
					$currentDateOfMonth = date('Y-m-d');
					if(strtotime($currentDateOfMonth)<= strtotime($act->end_date) && strtotime($currentDateOfMonth)>= strtotime($act->start_date))
					{
					$keepRecord = true;
					if ($act->task_type == 0) {
						
						if ($currentDateOfMonth !== $act->start_date) {
							$keepRecord = false;
						}
					}else{
						if ($act->continuous_type == 0) { // Weekly
							$currentDay = strtoupper(date('l'));
							\Log::info(["weekdays"=>$act]);
							$expectedDays = $act->selected_week_days;
							$expectedDays = is_array($expectedDays) ? array_map('strtoupper', $expectedDays) : [];
							if (!in_array($currentDay, $expectedDays)) {
								$keepRecord = false;
							}
						} 
						else{
							$currentDateOfMonth = date('j');
						$expectedDays = $act->selected_month_dates;
						$expectedDays = is_array($expectedDays) ? $expectedDays : [];
						if (!in_array($currentDateOfMonth, $expectedDays)) {
							$keepRecord = false;
						}
						}
					}
					return $keepRecord;
				}
				});
			
				}
				$records= $records->values();
			}
		}
		$task_update = $this->notifications->where(['staff_id'=>Auth::user()->id,'item_type'=> "task_created",'seen'=>0])->update(["seen"=>1]);
        $pagination = $this->prepareApiPaginationData($request, $records, $total_count); 
		$chat_count = $this->chats->join('chat_messages', 'chats.id', '=', 'chat_messages.chat_id')
                //    ->where('chats.user_id', Auth::user()->id)
					->where(function($query) {
						$query->where('chats.user_id', Auth::user()->id)
							->orWhere('chats.created_by', Auth::user()->id);
					})
                   ->where('chat_messages.sender_id', '!=', Auth::user()->id)
                   ->where('chat_messages.seen', 0)
                   ->count('chat_messages.id');
		$task_count = $this->notifications->where(['staff_id'=>Auth::user()->id,'item_type'=> "task_created",'seen'=>0])->count();
		return response()->json([
			'status' => 'success', 
			'list' => $records,
			'pagination' => $pagination,
			'chat_count'=>$chat_count,
			'task_count'=>$task_count,
		]); 
    }

    public function updateTaskStatus(Request $request)
    {
		$validator = Validator::make($request->all(), [ 
            'task_status' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()], 200);
        }
		
        $result = $this->tasks->updateTaskStatus($request, $request->task_id);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Task status updated successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
    }

	public function addUpdateTasks(Request $request)
    { 
        // dd($request);
       //Validate data
	   $validator = Validator::make($request->all(), [ 
		'user_type' => 'required', 
		'title'       => 'required',
		'description' => 'required',
		'start_date'  => 'required',
		// 'end_date'    => 'required',
		'start_time'  => 'required',
		// 'end_time'    => 'required',
		'task_type'   => 'required',
		]);

		//Send failed response if request is not valid
		if ($validator->fails()) {
			return response()->json(['status' => 'error', 'message' => $validator->messages()], 200);
		}

        $result = $this->tasks->addUpdateTasks($request);

        if($result){
			return response()->json(['status' => 'success', 'message' => 'Task add or update successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
    }

	public function deleteTask(Request $request)
	{
        $result = $this->tasks->deleteTask($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Task deleted successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}

    
}
