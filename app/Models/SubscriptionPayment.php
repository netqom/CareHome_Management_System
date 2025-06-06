<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class SubscriptionPayment extends Model
{
    use HasFactory, SoftDeletes;
	
	 /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'care_home_id',
        'stripe_subscription_id',
        'current_period_start',
        'current_period_end',
		'unit_amount',
		'customer',
		'default_payment_method',
		'stripe_status',
		'hosted_invoice_url',
		'invoice_pdf',
    ];


    public function getSubscriptionRevenueList($request)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
		// $start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        // $end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

        $start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
        $end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';
    
        return $this->where('subscription_payments.stripe_status', 'paid')
            ->leftJoin('subscriptions', function ($join) {
                $join->on('subscription_payments.stripe_subscription_id', '=', 'subscriptions.stripe_id');
            })
            ->leftJoin('care_homes', function ($join) {
                $join->on('subscriptions.care_home_id', '=', 'care_homes.id');
            })
            ->leftJoin('subscription_plans', function ($join) {
                $join->on('subscriptions.stripe_price', '=', 'subscription_plans.stripe_price_id');
            })
            ->when($start_date != '' && $end_date != '', function ($q) use ($start_date, $end_date) {
                return $q->whereBetween('subscription_payments.current_period_start', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
            })
            ->withTrashed()
            ->select('subscription_payments.id', 'subscription_payments.unit_amount as plan_price', 'subscription_plans.user_allowed', 'subscription_payments.created_at','subscriptions.type as plan_name','subscriptions.quantity as quantity','care_homes.id as care_home_id', 'care_homes.name as care_home_name', 'care_homes.street as care_home_street', 'care_homes.state as care_home_state', 'care_homes.zip_code as care_home_zip_code', 'care_homes.city as care_home_city', 'subscriptions.stripe_status as status', 'subscription_payments.current_period_start', 'subscription_payments.current_period_end')->orderBy('subscription_payments.created_at', 'DESC');

	}
    public function getLastWeekRecords($request)
    {
        // Set default sort field and order
        $sort_field = $request->input('sort_field', 'id');
        $sort_order = $request->input('sort_order', 'desc');

        // Set start date and end date if provided, otherwise default to last week
        $start_date = $request->has('start_date') ? date('Y-m-d', strtotime($request->start_date)) : Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
        $end_date = $request->has('end_date') ? date('Y-m-d', strtotime($request->end_date)) : Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');

        // Build the query
        return $this->where('subscription_payments.stripe_status', 'paid')
            ->leftJoin('subscriptions', 'subscription_payments.stripe_subscription_id', '=', 'subscriptions.stripe_id')
            ->leftJoin('care_homes', 'subscriptions.care_home_id', '=', 'care_homes.id')
            ->leftJoin('subscription_plans', 'subscriptions.stripe_price', '=', 'subscription_plans.stripe_price_id')
            ->when($start_date != '' && $end_date != '', function ($q) use ($start_date, $end_date) {
                return $q->whereBetween('subscription_payments.current_period_start', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
            })
            ->withTrashed()
            ->select(
                'subscription_payments.id', 
                'subscription_payments.unit_amount as plan_price', 
                'subscription_plans.user_allowed', 
                'subscription_payments.created_at',
                'subscriptions.type as plan_name',
                'subscriptions.quantity as quantity',
                'care_homes.id as care_home_id', 
                'care_homes.name as care_home_name', 
                'care_homes.city as care_home_city', 
                'subscriptions.stripe_status as status', 
                'subscription_payments.current_period_start', 
                'subscription_payments.current_period_end'
            )
            ->orderBy($sort_field, $sort_order);
    }

    public function getSubscriptionRevenueData($id)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
		$start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
        $end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';
    
        return $this->where('subscription_payments.stripe_status', 'paid')
            ->leftJoin('subscriptions', function ($join) {
                $join->on('subscription_payments.stripe_subscription_id', '=', 'subscriptions.stripe_id');
            })
            ->leftJoin('care_homes', function ($join) {
                $join->on('subscriptions.care_home_id', '=', 'care_homes.id');
            })
            ->leftJoin('subscription_plans', function ($join) {
                $join->on('subscriptions.stripe_price', '=', 'subscription_plans.stripe_price_id');
            })
            // ->when($start_date != '' && $end_date != '', function ($q) use ($start_date, $end_date) {
            //     return $q->whereBetween('subscription_payments.current_period_start', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
            // })
            ->select('subscription_payments.unit_amount as plan_price', 'subscription_plans.user_allowed', 'subscription_payments.created_at','subscriptions.type as plan_name','care_homes.id as care_home_id', 'care_homes.name as care_home_name', 'care_homes.city as care_home_city', 'subscriptions.stripe_status as status', 'subscription_payments.current_period_start', 'subscription_payments.current_period_end', 'subscription_payments.stripe_status','subscription_payments.invoice_pdf','subscription_payments.stripe_subscription_id')->where('subscription_payments.id',$id)->orderBy('subscription_payments.id', 'DESC');

	}
}
