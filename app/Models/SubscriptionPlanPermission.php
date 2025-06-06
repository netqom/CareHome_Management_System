<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanPermission extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subscription_plan_id',
        'allow_geofencing',
        'allow_cashflow',
        'allow_chat',
        'allow_manage_task',
        'allow_manage_history',
        'allow_manage_med_report',
        'allow_add_images',
        'manage_scheduling',
        'assign_training',
    ];


    public function addUpdatePermissions($request)
    {
       
        $subscription_permission = [
            'allow_geofencing' => isset($request->allow_geofencing) ? $request->allow_geofencing : 0,
            'allow_add_images' => isset($request->allow_add_images) ? $request->allow_add_images : 0,
            'allow_cashflow' => isset($request->allow_cashflow) ? $request->allow_cashflow : 0,
            'allow_chat' => isset($request->allow_chat) ? $request->allow_chat : 0,
            'allow_manage_task' => isset($request->allow_manage_task) ? $request->allow_manage_task : 0 ,
            'allow_manage_med_report' => isset($request->allow_manage_med_report) ? $request->allow_manage_med_report : 0,
            'manage_scheduling' => isset($request->manage_scheduling) ? $request->manage_scheduling : 0,
            'assign_training' => isset($request->assign_training) ? $request->assign_training : 0,
        ];

        if ($request->plan_id != 0) {
        
            $subscriptionPlanPermission =  $this->updateOrCreate(['subscription_plan_id' => $request->plan_id], $subscription_permission);
        
            return true;
        }else{
            return false;
        }
       
    }
}
