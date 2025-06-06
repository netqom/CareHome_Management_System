<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\CareHome;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionItem;
use App\Models\PatientPayment;
use App\Models\PatientLog;
use App\Models\Patient;
use App\Models\SubscriptionPayment;

class RevenuesController extends Controller
{
    private $users;
    private $homes;
    private $subscription;
    private $plan;
    private $patients;
    private $patient_payments;
    private $patient_logs;
    private $subscription_payment;

    /**
     * Constructor Instance
     */
	public function __construct(User $users, CareHome $homes, Subscription $subscription, SubscriptionPlan $plan, PatientLog $patient_logs, PatientPayment $patient_payments, SubscriptionPayment $subscription_payment, Patient $patients)
	{
		$this->users               = $users;
        $this->homes                = $homes;
		$this->plan                 = $plan;
        $this->patients         = $patients;
        $this->subscription         = $subscription;
        $this->patient_payments     = $patient_payments;
        $this->patient_logs         = $patient_logs;
        $this->subscription_payment = $subscription_payment;
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
		if(Auth::user()->role_id == 1){
            $records = $this->subscription_payment->getSubscriptionRevenueList($request)->get();
            $total_revenue = 0.00;
            if($records->isNotEmpty()){
                foreach($records as $record){
                    $total_revenue += $record->plan_price;
                }
            }
            
			return view('revenues.superadmin-index', compact('records','total_revenue'));
		}else{
			return view('revenues.admin-index');
		}
    }

    public function getLastWeekRevenueData(Request $request)
    {
		if(Auth::user()->role_id == 1){
            
            $records = $this->subscription_payment->getLastWeekRecords($request)->get();
           
			return view('revenues.superadmin-index', compact('records'));
		}else{
			return view('revenues.admin-index');
		}
    }
	
	/**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response JSON
     */
	public function getSubscriptionData(Request $request)
    {
        // $data = $this->subscription->getSubscriptionList($request);
        $data = $this->subscription_payment->getSubscriptionRevenueList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        //$view = 'revenues.partials.subscription-list-table-body';
        $view = 'revenues.partials.super-admin-revenue-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);
        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }


    public function getRevenueData($id)
    {
        
        $data = $this->subscription_payment->getSubscriptionRevenueData($id)->first();
        $home = $this->homes->find($data->care_home_id);
        $patients = $this->patients->where('home_id', $data->care_home_id)->get();
		$staffs = $this->users->where('home_id', $data->care_home_id)->where('role_id', '!=', 2)->get();
        $subscription = $this->subscription->where(['care_home_id' => $id,'stripe_status' => 'active'])->latest()->first();
        $home['subscription'] = $subscription;
		$plan = $subscription ? $this->plan->where('stripe_price_id', $subscription->stripe_price)->first() : '';
        $user_allowed = '';
        if($home['subscription']){
            $user_allowed = $this->plan->where('stripe_price_id', $home['subscription']->stripe_price)
                            ->orWhere('addons_stripe_price_id', $home['subscription']->stripe_price)
                            ->first();
        }

        // $subscription_invoices = $this->subscription_payment->where('care_home_id', $id)->get();
		$subscription_invoices = $home->subscriptionPayments;
        return view("revenues.show", compact('data','home','patients', 'staffs', 'user_allowed', 'plan', 'subscription_invoices'));
        //dd($data,$data->care_home);
    }
	
	/**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response JSON
     */
	public function getPatientData(Request $request)
    {
        $data = $this->patient_logs->getPaymentList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		//echo"<pre>";print_r($records);die;
        $view = 'revenues.partials.patient-list-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);
		$total_revenue = $this->patient_logs->getTotalRevenue($request);
        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination, 'total_revenue' => amountFormat($total_revenue)]);
    }
	
}
