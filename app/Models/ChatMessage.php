<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SupportChat;
use Auth;
use App\Events\CreateNotification;

class ChatMessage extends Model
{
    use HasFactory;
	
	 /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'chat_id',
		'sender_id',
		'message',
		'file_path',
		'seen',
		'status',
		'created_by',
		'updated_by',
    ];
	
	public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }
	
	public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
	
	public function saveNewMessage($request)
	{
		$message_data = [   'sender_id' => $request->sender_id, 
							'chat_id' => $request->chat_id, 
							'message' => $request->message, 
							'seen' => 0, 
							'status' => 1, 
							'created_by' => Auth::user()->id, 
							'updated_by' => Auth::user()->id
						];
		$chatmsg=$this->create($message_data);
		//update last message
		$chat = Chat::find($request->chat_id);
		$chat->last_message = date('Y-m-d H:i:s');
		$chat->message      = $request->message;
		$chat->save();
		
		event(new CreateNotification('send_message', $chatmsg));
		return true;
	}

	public function saveNewFeedback($request, $chatData)
	{
		$message_data = [   'sender_id' => $request->sender_id, 
							'chat_id' => $chatData->id, 
							'message' => $request->message, 
							'seen' => 0, 
							'status' => 1, 
							'created_by' => Auth::user()->id, 
							'updated_by' => Auth::user()->id
						];
		$chatmsg=$this->create($message_data);
		//update last message
		$chat = Chat::find($chatData->id);
		$chat->last_message = date('Y-m-d H:i:s');
		$chat->message      = $request->message;
		$chat->save();
		
		//event(new CreateNotification('send_message', $chatmsg));
		return true;
	}
}
