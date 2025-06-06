<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CareHome;
use App\Models\Chat;
use App\Models\ChatMessage;
use Auth;
use App\Models\Notification;

class ChatsController extends Controller
{
	private $users;
	private $care_home;
	private $chats;
	private $chat_message;
	private $notifications;
	
	/**
     * Constructor Instance
     */
	public function __construct(User $users, CareHome $care_home, Chat $chats,Notification $notifications, ChatMessage $chat_message)
	{
		$this->users        = $users;
		$this->care_home    = $care_home;
		$this->chats        = $chats;
		$this->chat_message = $chat_message;
        $this->notifications      = $notifications;
	}
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getChats()
    {
		//If User check user has chat with support otherwise create it
		$this->chats->checkAndInitChat();
		//Check and create chat with admin for staff and manager
		$home = CareHome::find(Auth::user()->home_id);
		$this->chats->checkAndInitAdminChatAPP($home);
		$admin_chat = $this->chats->where(['type' => 2, 'user_id' => Auth::user()->id]) 
            ->withCount(['chatMessages as unread_count' => function ($query) {

                $query->where('seen', 0)->where("sender_id","!=",Auth::user()->id); // Assuming `is_read` is the column to check for unread messages
            }])->first();
		$feedback_chat = $this->chats->where(['type' => 2, 'is_feedback' => 1,'user_id' => Auth::user()->id]) 
            ->withCount(['chatMessages as unread_count' => function ($query) {

                $query->where('seen', 0)->where("sender_id","!=",Auth::user()->id); // Assuming `is_read` is the column to check for unread messages
            }])->get();
            
		$chats = Auth::user()->role_id == 4 
    ? $this->chats->where(['type' => 1, 'user_id' => Auth::user()->id])
        ->withCount(['chatMessages as unread_count' => function ($query) {

            $query->where('seen', 0)->where("sender_id","!=",Auth::user()->id); // Assuming `is_read` is the column to check for unread messages
        }])
        ->get() 
    : $this->chats->where(['type' => 1, 'home_id' => Auth::user()->home_id])
        ->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        })
        ->withCount(['chatMessages as unread_count' => function ($query) {
            $query->where('seen', 0)->where("sender_id","!=",Auth::user()->id);
        }])
        ->orderBy('last_message', 'DESC')
        ->get();
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
		
		return response()->json(['status' => 'success', 'messages' => [], 'chats' => $chats, 'admin_chat' => $admin_chat,'feedback_chat'=>$feedback_chat,'chat_count'=>$chat_count,'task_count'=>$task_count,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveMessage(Request $request)
    {
       $result = $this->chat_message->saveNewMessage($request);
	   if($result){
			return response()->json(['status' => 'success', 'message' => 'Message saved successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
    }

    function rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars),0,$length);
	}

    public function sendAdminFeedback(Request $request)
    {
       $staffDetail = User::where('id', $request->sender_id)->select('parent_id', 'home_id')->first();
       if($staffDetail){
            $chatData = Chat::where(['type' => 2, 'home_id' => $staffDetail->home_id, 'user_id' => $request->sender_id, 'is_feedback' => 1])->first();
            if($chatData){
                $result = $this->chat_message->saveNewFeedback($request, $chatData);
            }else{
                $chatData = Chat::create([
                    'type' => 2, 
                    'home_id' => $staffDetail->home_id, 
                    'is_feedback' => 1,
                    'user_id' => $request->sender_id,
                    'unique_id' => $this->rand_string(8),
                    'last_message' => date('Y-m-d H:i:s'),
                    'message' => $request->message,
                    'status' => 1,
                    'last_message' => date('Y-m-d H:i:s'),
                    'last_message' => date('Y-m-d H:i:s'),
                    'created_by' => $request->sender_id,
                    'updated_by' => $request->sender_id,
                ]);
                $result = $this->chat_message->saveNewFeedback($request, $chatData);
            }
            return response()->json(['status' => 'success', 'message' => 'Feedback sent successfully'], 200);
       }else{
        return response()->json(['status' => 'error', 'message' => 'Staff not found'], 200);
       }
    }
	
	public function getMessage(Request $request)
	{
        $update = $this->chat_message->where('chat_id', $request->chat_id)->where('sender_id', '!=', Auth::user()->id)->update(["seen"=>1]);
		$messages = $this->chat_message->with('sender')->where('chat_id', $request->chat_id)->get();
		
		return response()->json(['status' => 'success', 'messages' => $messages]);
	}
	
	

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
