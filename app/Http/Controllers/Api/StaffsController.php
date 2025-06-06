<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CareHome;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Notification;

class StaffsController extends Controller
{
	
	private $users;
	private $chats;
	private $chat_message;
	private $notifications;
	
	/**
     * Constructor Instance
     */
	public function __construct(User $users,Chat $chats,Notification $notifications, ChatMessage $chat_message)
	{
		$this->users = $users;
		$this->chats        = $chats;
		$this->chat_message = $chat_message;
        $this->notifications      = $notifications;
	}

	public function getTaskStaffList(Request $request)
    {
		$staff    = $this->users->getTaskStaff($request);
		return response()->json([
			'status' => 'success',
			'list' => $staff,
		]);
	}

    public function getStaffList(Request $request)
    {
		$data = $this->users->getCareHomeStaffList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $pagination = $this->prepareApiPaginationData($request, $records, $total_count);
		$shifts =  config('const.work_shifts');
	    return response()->json([
                'status' => 'success',
				'shifts' => $shifts,
                'list' => $records,
				'pagination' => $pagination,
            ]);
    }
	
	public function addUpdateStaff(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => $request->id == 0 ? 'required|email|unique:users,email' : 'required|email|unique:users,email,'.$request->id,
			 //'email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required',
            'home_id' => 'required',
            'shift_id' => 'required',
            'phone_number' => 'required',
            'status' => 'required',
        ]);
		//Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()], 200);
        }
		$result = $this->users->addUpdateUser($request, $request->id);
		if($result){
			$message_type = $request->id == 0 ? 'created' : 'updated';
			return response()->json(['status' => 'success', 'message' => 'User '.$message_type.' successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}
	
	public function addUpdateStaffShift(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'staff_id' => 'required',
            'shift_id' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()], 200);
        }
		
		if(!checkManager('staff', $request->staff_id)){
			return response()->json(['status' => 'error', 'message' => 'You are not authorized for this operation'], 200);
		}
		
        $result = $this->users->addUpdateStaffShift($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Shift updated successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
    }

	public function getStaffDetail(Request $request)
    {
		$user = Auth::user();
		$user['check_staff_shift'] = false;
        $care_home = CareHome::find($user->home_id);
		$chat_count = $this->chats->join('chat_messages', 'chats.id', '=', 'chat_messages.chat_id')
		// ->where('chats.user_id', Auth::user()->id)
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
			'user' => $user,
			'care_home' => $care_home,
			'check_shift_msg'=>'Access Denied: You do not have the necessary permissions to enter this report.',
			'check_geofencing_msg'=>'Oops! It seems you are currently outside the boundary of '.$care_home->name . ' Please return to the designated area for assistance',
			'chat_count'=>$chat_count,
			'task_count'=>$task_count,
		]);
    }

	
}