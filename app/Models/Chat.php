<?php

namespace App\Models;

use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'unique_id',
        'home_id',
        'user_id',
        'is_super_admin_allow',
        'is_feedback',
        'last_message',
        'message',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['user_detail', 'chat_admin','feedback_chat'];

    public function getUserDetailAttribute()
    {
        return User::where('id', $this->user_id)->select('id', 'name', 'role_id', 'email', 'shift_id')->first();
    }

    public function getChatAdminAttribute()
    {
        return User::where('id', $this->created_by)->select('id', 'name', 'role_id', 'email', 'shift_id')->first();
    }
    public function getFeedbackChatAttribute()
    {
        $staff =User::where('id', $this->created_by)->select('parent_id')->first();
        return User::where('id', $staff->parent_id)->select('id', 'name', 'role_id', 'email', 'shift_id')->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function chatMessages()
	{
		return $this->hasMany(ChatMessage::class);
	}
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id', 'id');
    }

    public function checkAndInitChat()
    {
        if (Auth::user()->role_id == 3) {
            $users = User::where(['home_id' => Auth::user()->home_id, 'role_id' => 4])->get();
            foreach ($users as $user) {
                $check = $this->where('user_id', $user->id)->first();
                // $check = $this->where('user_id', Auth::user()->id)->where('type', 1)->first();
                if (!$check) {
                    $this->createChat(1, $user->home_id, $user->id, Auth::user()->id);
                }
            }
        } else {
            $manger = User::where(['home_id' => Auth::user()->home_id, 'role_id' => 3])->first();
            // $check = $this->where('user_id', Auth::user()->id)->first();
            $check = $this->where('user_id', Auth::user()->id)->where('type', 1)->first();
            if (!$check && !empty($manger)) {
                $this->createChat(1, Auth::user()->home_id, Auth::user()->id, $manger->id);
            }
        }
        return true;
    }

    public function checkAndInitAdminChatAPP($home)
    {
        $users = User::where(['home_id' => $home->id])->whereIn('role_id', [3, 4])->get();
        foreach ($users as $user) {
            $check = $this->where(['type' => 2, 'home_id' => $home->id, 'user_id' => $user->id])->first();
            if (!$check) {
                $this->createChat(2, $home->id, $user->id, $home->user_id);
            }
        }
        return true;
    }

    public function checkAndInitAdminChat($request)
    {
        $user = User::find($request->user_id);
        $check = $this->where(['type' => 2, 'user_id' => $user->id])->first();
        if (!$check) {
            return $this->createChat(2, $user->home_id, $user->id, Auth::user()->id);
        }
        return $check;
    }

    public function checkAndInitAdminChatFeedback($request)
    {
        $user = User::find($request->user_id);
        $check = $this->where(['type' => 2, 'created_by' => $user->id, 'is_feedback' => 1])->first();
        if (!$check) {
            return $this->createChat(2, $user->home_id, $user->id, Auth::user()->id);
        }
        return $check;
    }

    public function createChat($type, $home_id, $user_id, $created_by)
    {
        $chat_data = ['type' => $type, 'unique_id' => $this->rand_string(8), 'home_id' => $home_id, 'user_id' => $user_id, 'status' => 1, 'created_by' => $created_by, 'updated_by' => $created_by];
        return $this->create($chat_data);
    }

    public function rand_string($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }
}
