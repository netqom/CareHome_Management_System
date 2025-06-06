<?php

namespace App\Http\Controllers;

use App\Models\CareHome;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\FirebaseService;
use Auth;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    private $users;
    private $care_home;
    private $chats;
    private $chat_message;

    /**
     * Constructor Instance
     */
    public function __construct(User $users, CareHome $care_home, Chat $chats, ChatMessage $chat_message, FirebaseService $firebaseService)
    {
        $this->users = $users;
        $this->care_home = $care_home;
        $this->chats = $chats;
        $this->chat_message = $chat_message;
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        if (Auth::user()->role_id != '1') {
            $house_ids = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
            $users = $this->users->whereIn('users.home_id', $house_ids)->get();

            $users_ids = $this->chats->where('is_feedback', 1)->pluck('created_by')->toArray();
            $usersFeedback = $this->users->whereIn('users.id', $users_ids)->get();
            $usersFeedback = $usersFeedback->map(function ($user) {
                // Add a new key-value pair to each user
                $user->is_feedback = 1; // Change 'new_key' and 'new_value' to your desired key and value
                return $user;
            });
            
            if (count($users)) {
                if (count($usersFeedback)) {
                    $users = $users->concat($usersFeedback);
                    //$users = collect(array_merge($users->toArray(), $usersFeedback->toArray()));
                }
            }

            $users = $users->map(function ($user) {
                $user->chats = $this->getUserChats($user);
                // Now merge the chat info with the user data
                if ($user->chats) {
                    $user->chat_id = $user->chats[0]['chat_id']; // Set chat_id on user
                    $user->unread_count = $user->chats[0]['unread_count']; // Set unread_count on user
                }
                unset($user->chats);
                return $user;
            });
        } else {
            $users_ids = $this->chats->where('is_super_admin_allow', 1)->pluck('user_id')->toArray();
            $users = $this->users->whereIn('users.id', $users_ids)->get();
            $users = $users->map(function ($user) {
                // Add a new key-value pair to each user
                $user->is_feedback = 1; // Change 'new_key' and 'new_value' to your desired key and value
                $user->chats = $this->getUserChats($user);
                // Now merge the chat info with the user data
                if ($user->chats) {
                    $user->chat_id = $user->chats[0]['chat_id']; // Set chat_id on user
                    $user->unread_count = $user->chats[0]['unread_count']; // Set unread_count on user
                }
                unset($user->chats);
                return $user;
            });
        }

        return view('chat.index', compact('users'));
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
        if ($result) {
            $chat = $this->chats->find($request->chat_id);
            $user_id = $chat->user_id;
            $userDetail = $this->users->find($user_id);
            $SenderDetail = $this->users->find($request->sender_id);
            $fcmToken = $userDetail->id;

            return response()->json(['status' => 'success', 'message' => 'Message saved successfully'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
        }
    }

    public function getMessage(Request $request)
    {
        
        $chat_id = $request->chat_id;
        //If User check user has chat with support otherwise create it
        if ($chat_id == 0 && $request->isFeedback != '1') {
            $chat = $this->chats->checkAndInitAdminChat($request);
            $chat_id = $chat->id;
        } else {
            $chat = $this->chats->checkAndInitAdminChatFeedback($request);
            $chat_id = $chat->id;
        }
        $chatData = $this->chats->where('id', $chat_id)->first();
        
        $isAdminAllow = 0;
        if ($chatData && $chatData->is_feedback == 1 && $chatData->is_super_admin_allow != 1) {
            $isAdminAllow = $chatData->is_feedback;
        }
        $update = $this->chat_message->where('chat_id', $chat_id)->where('sender_id', '!=', Auth::user()->id)->update(["seen"=>1]);
        $messages = $this->chat_message->where('chat_id', $chat_id)->get();
        $html = view('chat.partials.messages', compact('messages'))->render();
        return response()->json(['status' => 'success', 'html' => $html, 'chat_id' => $chat_id, 'is_super_admin_allow' => $isAdminAllow]);
    }

    public function superAdminAllow(Request $request)
    {
        $chat_id = $request->chat_id;
        $this->chats->where('id', $chat_id)->update(['is_super_admin_allow' => 1]);
        return response()->json(['status' => 'success']);
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

    public function getUserChats($user){
        if($user->is_feedback && $user->is_feedback == 1){
            return $this->chats->where(['type' => 2, 'is_feedback' => 1,'user_id' => $user->id]) 
            ->withCount(['chatMessages as unread_count' => function ($query) {

                $query->where('seen', 0)->where("sender_id","!=",Auth::user()->id); // Assuming `is_read` is the column to check for unread messages
            }])
            ->get(['id'])
            ->map(function ($chat) {
                $chat->makeHidden(['user_detail', 'chat_admin', 'feedback_chat']);
                return [
                    'chat_id' => $chat->id, // Keep only 'id'
                    'unread_count' => $chat->unread_count // Include the 'unread_count'
                ];
            })->toArray();
        }else{
            return $this->chats->where(['type' => 2, 'is_feedback' => 0,'user_id' => $user->id]) 
            ->withCount(['chatMessages as unread_count' => function ($query) {

                $query->where('seen', 0)->where("sender_id","!=",Auth::user()->id); // Assuming `is_read` is the column to check for unread messages
            }])
            ->get(['id'])
            ->map(function ($chat) {
                $chat->makeHidden(['user_detail', 'chat_admin', 'feedback_chat']);
                return [
                    'chat_id' => $chat->id, // Keep only 'id'
                    'unread_count' => $chat->unread_count // Include the 'unread_count'
                ];
            })->toArray();
        }
        
    }

    public function getChatMessageCount()
    {
        return response()->json(['count' => chatMessageUserCount()]);
    }
}
