<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\CareHome;

class SubscriptionNotification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'home_id',
		'item_id',
		'item_type',
        'message',
        'app_message',
		'seen',
		'status',
		'created_by',
        'updated_by'
    ];

    protected $appends = ['creator_name'];
	
	public function getCreatorNameAttribute(){
		return $this->added_by ? $this->added_by->name : '';
    }

    public function getSubscriptionNotification(){
       $getNotification =  CareHome::where('care_homes.user_id', Auth::user()->id)
                    ->leftJoin('subscription_notifications', 'care_homes.id', '=', 'subscription_notifications.home_id')
                    // ->leftJoin('subscription_notifications', 'subscription_notifications.item_id', '=', 'subscriptions.id')
                    ->where('subscription_notifications.status', 1)
                    ->where('subscription_notifications.deleted_at', null)
                    ->orderBy('subscription_notifications.id', 'desc')
                    ->select('subscription_notifications.*')
                    ->get();
                    // ->limit($limit)->get();
       return $getNotification;
    }
}
