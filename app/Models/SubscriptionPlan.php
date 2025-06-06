<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SubscriptionPlanPermission;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'benefit_type',
        'stripe_product_id',
        'name',
        'description',
        'duration',
        'price',
        'user_allowed',
        'patient_allowed',
        'allow_add_patients',
        'allow_patient_logs',
        'allow_add_images',
        'allow_geofencing',
        'allow_cashflow',
		'stripe_price_id',
		'addon_status',
		'addons_price',
        'addons_stripe_price_id',
        'patient_addons_price',
        'patient_addons_stripe_price_id',
    ];

    public function addUpdateSubscription($request)
    {
        $subscription_data = [
            'name'         => $request->name,
            'description'  => $request->description,
            'duration'     => $request->duration, //1=Monthly, 2=Yearly
            'price'        => $request->price,
            'user_allowed' => $request->user_allowed,
            'patient_allowed' => $request->patient_allowed,
            // 'allow_add_patients' => isset($request->allow_add_patients) ? $request->allow_add_patients : 0,
            // 'allow_patient_logs' => isset($request->allow_patient_logs) ? $request->allow_patient_logs : 0 ,
            // 'allow_add_images' => isset($request->allow_add_images) ? $request->allow_add_images : 0,
            // 'allow_geofencing' => isset($request->allow_geofencing) ? $request->allow_geofencing : 0,
            // 'allow_cashflow' => isset($request->allow_cashflow) ? $request->allow_cashflow : 0,
			'stripe_price_id' => $request->stripe_price_id,
			'addon_status' => isset($request->addon_status) ? $request->addon_status : 0,
            'stripe_product_id' => $request->stripe_product_id,
			//'addons_price' => $request->addons_price,
        ];

        $subscription_permission = [
            'allow_geofencing' => isset($request->allow_geofencing) ? $request->allow_geofencing : 0,
            'allow_add_images' => isset($request->allow_add_images) ? $request->allow_add_images : 0,
            'allow_cashflow' => isset($request->allow_cashflow) ? $request->allow_cashflow : 0,
            'allow_chat' => isset($request->allow_chat) ? $request->allow_chat : 0,
            'allow_manage_task' => isset($request->allow_manage_task) ? $request->allow_manage_task : 0 ,
            'allow_manage_med_report' => isset($request->allow_manage_med_report) ? $request->allow_manage_med_report : 0,
        ];

        if ($request->id != 0) {
            $subscription = $this->findOrFail($request->id);
            $subscriptionData = $subscription->update($subscription_data);
            $subscriptionPlanPermission = SubscriptionPlanPermission::updateOrCreate(['subscription_plan_id' => $request->id], $subscription_permission);

        } else {
            $subscription_data['addons_price'] = $request->addons_price;
            $subscription_data['addons_stripe_price_id'] = $request->addons_stripe_price_id;
            $subscription = $this->create($subscription_data);
            $subscriptionPlanPermission = SubscriptionPlanPermission::create(['subscription_plan_id' => $subscription->id] + $subscription_permission);
        }

        if (isset($subscription->id)) {
            return $subscription->id;
        } else {
            return 0;
        }
    }

    public function getPlanList($request){
        $stripe_mode=config('app.stripe_mode');
        $stripe_type=0;
        if($stripe_mode=='Live')
        {
            $stripe_type=1;
        }
        $sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        return $this->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("subscription_plans.name", "LIKE", "%{$search}%")
                        ->orWhere("subscription_plans.description", "LIKE", "%{$search}%")
                        ->orWhere("subscription_plans.stripe_price_id", "LIKE", "%{$search}%");
                });
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                 return $q->orderBy('subscription_plans.' . $sort_field, $sort_order);
            })->where(['stripe_mode'=>$stripe_type]);
    }


    /** Get Subscription Plan Permission **/
    public function permission()
    {
        return $this->hasOne(SubscriptionPlanPermission::class, 'subscription_plan_id', 'id');
    }
}
