<?php

namespace App\Http\Controllers;

use App\Mail\AddOnStaffSubscriptionMail;
use App\Mail\BuySubscriptionPlanMail;
use App\Mail\SubscriptionCancelResumeMail;
use App\Mail\SubscriptionPriceUpdateMail;
use App\Models\ActivityTime;
use App\Models\CareHome;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\Expense;
use App\Models\ManageHomeDocument;
use App\Models\Patient;
use App\Models\PatientLog;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\SubscriptionNotification;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Mail;
use Stripe\PaymentIntent;

class CareHomesController extends Controller
{

    private $users;
    private $roles;
    private $homes;
    private $patients;
    private $subscription;
    private $subscriptionItem;
    public $subscriptionPlan;
    private $subscription_payment;
    private $patient_logs;
    private $activity_time;
    private $subscription_notifications;
    private $coupon;
    private $discount;
    private $expense;
    private $home_documents;

    /**
     * Constructor Instance
     */
    public function __construct(User $users, Role $roles, CareHome $homes, Patient $patients, Subscription $subscription,
        SubscriptionPlan $subscriptionPlan, SubscriptionItem $subscriptionItem, SubscriptionPayment $subscription_payment, PatientLog $patient_logs, ActivityTime $activity_time, SubscriptionNotification $subscription_notifications, Coupon $coupon, Discount $discount, Expense $expense, ManageHomeDocument $home_documents) {
        $this->users = $users;
        $this->roles = $roles;
        $this->homes = $homes;
        $this->patients = $patients;
        $this->subscription = $subscription;
        $this->subscriptionPlan = $subscriptionPlan;
        $this->subscriptionItem = $subscriptionItem;
        $this->stripe = new \Stripe\StripeClient(config('app.stripe_secret'));
        $this->subscription_payment = $subscription_payment;
        $this->patient_logs = $patient_logs;
        $this->activity_time = $activity_time;
        $this->subscription_notifications = $subscription_notifications;
        $this->coupon = $coupon;
        $this->discount = $discount;
        $this->expense = $expense;
        $this->home_documents = $home_documents;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('care-homes.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response JSON
     */
    public function getData(Request $request)
    {
        $data = $this->homes->getList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'care-homes.partials.list-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);
        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Retrieve all plans(subscription plans) from Stripe
        $plans = $this->subscriptionPlan->get();
        $stripe_mode = config('app.stripe_mode');
        $stripe_type = 0;
        if ($stripe_mode == 'Live') {
            $stripe_type = 1;
        }
        $monthlyPlans = $this->subscriptionPlan->where(["duration" => 1, 'status' => 1, 'stripe_mode' => $stripe_type])->get();
        $yearlyPlans = $this->subscriptionPlan->where(["duration" => 2, 'status' => 1, 'stripe_mode' => $stripe_type])->get();
        //$view = Auth::user()->role_id == 1 ? "care-homes.create" : "care-homes.admin-create";
        $roles = $this->roles->where('id', '=', 2)->get();
        return view("care-homes.admin-create", compact('roles', 'plans', 'monthlyPlans', 'yearlyPlans'));
    }

    public function searchUser(Request $request)
    {
        //echo"<pre>";print_r($request->all());die;
        //$req = $request->all();
        //echo"<pre>";print_r(json_decode($req));die;
        //$users = $this->users->where('role_id', 2)->get();
        $users = $this->users->searchUser($request)->get();
        return response()->json(['type' => 'success', 'users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(Request $request)
    {
    //echo"<pre>";print_r($request->all());die;
    request()->validate([
    'admin_name' => $request->selected_user_id == 0 ? 'required' : '',
    'admin_email'   => $request->selected_user_id == 0 ? 'required|email|unique:users,email' : '',
    'role_id' => $request->selected_user_id == 0 ? 'required' : '',
    'admin_phone_number' => $request->selected_user_id == 0 ? 'required' : '',
    'name' => 'required',
    'email' => 'required',
    'contact_no' => 'required',
    'about' => 'required',
    //'address' => 'required',
    'street' => 'required',
    'city' => 'required',
    'state' => 'required',
    'zip_code' => 'required',
    //'capacity' => 'required',
    'location_lat' => 'required',
    'location_long' => 'required',
    'status' => 'required',
    ]);
    $pass = '';
    if($request->selected_user_id == 0){
    [$user , $pass] = $this->users->createCareHomeAdmin($request, 0);
    }else{
    //update user data if any
    if($request->has('role_id') && $request->has('admin_name') && $request->has('admin_email') && $request->has('admin_phone_number')){
    $this->users->createCareHomeAdmin($request, $request->selected_user_id);
    }
    $user = $this->users->find($request->selected_user_id);
    }

    $this->homes->addUpdateHome($request, $user, $pass, 0);
    return redirect()->route('homes.index')->with(['type' => 'success', 'message' => 'Care home created successfully']);
    }*/
	
	public function store(Request $request)
    {   
        
        request()->validate([
			'name' => 'required',
			'email' => 'required',
			'contact_no' => 'required',
			//'about' => 'required',
			'street' => 'required',
			'city' => 'required',
			'state' => 'required',
			'zip_code' => 'required',
			'location_lat' => 'required',
			'location_long' => 'required',
			'status' => 'required',
		]);
		$result = $this->homes->addUpdateHome($request, 0);
		if($result){
			$response = $this->buySubscription($request, $result);
			return response()->json($response);
		}
        //return redirect()->route('homes.index')->with(['type' => 'success', 'message' => 'Care home created successfully']);
    }

    public function buySubscription($request, $care_home)
    {
        //
        try {
            $plan = $this->subscriptionPlan->findOrFail($request->subscription_plan);
            if ($request->discount == '') {
                //get coupon
                if ($request->coupon_code != "") {
                    $coupon_detail = $this->coupon->where('coupon_code', $request->coupon_code)->first();
                    $count_coupon_used = $coupon_detail->times_redeemed + 1;
                }
            } else {
                //get discount
                $coupon_detail = $this->discount->where('stripe_id', $request->discount)->first();
                $count_coupon_used = $coupon_detail->times_redeemed + 1;
            }
            $paymentMethod = $request->stripeToken;

            $checkSubscription = $this->subscription->where(['care_home_id' => $care_home->id, 'stripe_price' => $plan->stripe_price_id, 'stripe_status' => 'active'])->first();
            // dd($request, $care_home, $checkSubscription, $paymentMethod,$plan->stripe_price_id);
            if (empty($checkSubscription)) {
                if ($request->discount == '' && $request->coupon_code == '') {
                    $subscription = $care_home->newSubscription($plan->name, $plan->stripe_price_id)->create($paymentMethod, ['email' => $request->email]);

                    if ($subscription) {
                        //  $add_subscription_permission = $this->homes->where('id',$care_home->id)->update(['subscription_plan_permission' => isset($request->subscription_plan_permission) && $request->subscription_plan_permission != '' ? json_encode($request->subscription_plan_permission) : null]);
                    }
                } else {
                    $subscription = $care_home->newSubscription($plan->name, $plan->stripe_price_id)->withCoupon($coupon_detail->stripe_id)->create($paymentMethod, ['email' => $request->email]);

                    if ($subscription) {
                        //  $add_subscription_permission = $this->homes->where('id',$care_home->id)->update(['subscription_plan_permission' => isset($request->subscription_plan_permission) && $request->subscription_plan_permission != '' ? json_encode($request->subscription_plan_permission) : null]);
                    }
                }

                if ($request->discount == '') {
                    if ($request->coupon_code != "") {
                        $coupon_update = $this->coupon->where('coupon_code', $request->coupon_code)->update(['times_redeemed' => $count_coupon_used]);
                    }
                } else {
                    $coupon_update = $this->discount->where('stripe_id', $request->discount)->update(['times_redeemed' => $count_coupon_used]);
                }
                //dd($subscription, $plan);
                $care_home->subscription_id = $subscription->id;
                $care_home->subscription_plan_id = $plan->id;
                $care_home->save();
                $this->sendSubscriptionMail($plan, $subscription, $care_home);
                $message = "Care home added and Subscription purchased successfully!";

                return ['status' => 'success', 'subscription_status' => 'active', 'url' => route('users.create', ['home_id' => $care_home->id]), 'message' => $message];
            } else {
                if ($request->staff_capacity) {
                    //addon staff subscription
                    $subscription = $care_home->newSubscription($plan->name, $plan->addons_stripe_price_id)
                        ->quantity($request->staff_capacity)
                        ->create($paymentMethod, ['email' => $request->email]);

                    if ($subscription) {
                        $this->sendAddonStaffSubscriptionMail($request->staff_capacity, $plan, $subscription, $care_home);
                        $staff_capacity = $care_home->staff_capacity + $request->staff_capacity;
                        $update_staff_capacity = $this->homes->where('id', $request->home_id)->update(['staff_capacity' => $staff_capacity]);
                        $message = "Per Staff Addon Subscription purchased successfully!";
                    }
                }
                if ($request->patient_capacity) {
                    //dd($request->patient_capacity);
                    //addon staff subscription
                    $subscription = $care_home->newSubscription($plan->name, $plan->addons_stripe_price_id)
                        ->quantity($request->patient_capacity)
                        ->create($paymentMethod, ['email' => $request->email]);
                    //dd($subscription,$request->email);
                    if ($subscription) {

                        $this->sendAddonPatientSubscriptionMail($request->patient_capacity, $plan, $subscription, $care_home);

                        $patient_capacity = $care_home->patient_capacity + $request->patient_capacity;
                        $update_staff_capacity = $this->homes->where('id', $request->home_id)->update(['patient_capacity' => $patient_capacity]);
                        $message = "Per Patient Addon Subscription purchased successfully!";
                    }
                }
                return ['status' => 'success', 'message' => $message];
            }
        } catch (PaymentActionRequired $e) {
            return ['status' => 'payment_action_required', 'url' => $e->paymentIntent->confirmation_method_url, 'message' => 'Payment confiramtion requried please follow the url!'];
        } catch (\Throwable $e) {
            //echo"<pre>";print_r($e->getMessage());die;
            /*$subscription = $care_home->subscription($plan->name);
            if($subscription->status == 'incomplete'){
            return ['status' => 'success', 'subscription_status' => 'incomplete', 'url' => route('homes.show', $care_home->id), 'message' => 'Your care home and subscription created successfully.', 'info' => 'It will take some time to activate your subscription. You can visit your care home by clicking the below button'];
            }*/
            // $subscription = $care_home->subscription($plan->name);

            // $upcoming_subscription = $this->stripe->subscriptions->retrieve($subscription->stripe_id, []);
            // Retrieve the latest invoice for the subscription
            // if ($upcoming_subscription) {
            //    $latestInvoice =  $this->stripe->invoices->pay($upcoming_subscription->latest_invoice, []);

            //     if($latestInvoice->paid == 1 && $latestInvoice->status == 'paid'){
            //         $subscription_status = $this->subscription->where('stripe_id',$subscription->stripe_id)->update(['stripe_status' => 'active']);

            //         // if($request->staff_capacity){
            //         //     $this->sendAddonStaffSubscriptionMail($request->staff_capacity,$plan,$subscription,$care_home);
            //         //     $staff_capacity = $care_home->staff_capacity + $request->staff_capacity;
            //         //     $update_staff_capacity = $this->homes->where('id',$request->home_id)->update(['staff_capacity' => $staff_capacity]);
            //         //     $message = "Per Staff Addon Subscription purchased successfully!";
            //         // }else{
            //         //     $this->sendSubscriptionMail($plan,$subscription,$care_home);
            //         //     $message = "Care home added and Subscription purchased successfully!";
            //         // }
            //         return ['status'=> 'success', 'subscription_status'=>  'active', 'url'=> route('users.create', ['home_id' => $care_home->id]), 'message'=> $message];
            //     }
            // }
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function sendSubscriptionMail($plan, $subscription, $care_home)
    {
        $dateString = '';
        $dateString = date_format($subscription->created_at, 'Y-m-d');

        $dateTime = new DateTime($dateString);

        // Add months, years and days
        if ($plan->duration == 1) {
            $dateTime->modify('+1 month');
        } elseif ($plan->duration == 2) {
            $dateTime->modify('+1 years');
        } else {
            $dateTime->modify('+1 day');
        }

        // Get the result as a string
        $renewal_date = $dateTime->format('m-d-Y');
        $data = [
            'name' => $care_home->name,
            'email' => $care_home->email,
            'content' => "You have purchased " . $plan->name . " successfully for " . $care_home->name,
            'price' => $plan->price,
            'renewal_date' => $renewal_date,
            'user_allowed' => $plan->user_allowed,
            'home_id' => $care_home->id,
            'subject' => $plan->name . " purchased successfully",
        ];
        Mail::to($care_home->email)->send(new BuySubscriptionPlanMail($data));
        return true;
    }

    public function sendAddonStaffSubscriptionMail($staff_capacity, $plan, $subscription, $care_home)
    {
        $data = [
            'name' => $care_home->name,
            'email' => $care_home->email,
            'content' => "You have purchased Per Staff Addon successfully on " . $plan->name . " for " . $care_home->name,
            'addon_price' => $plan->addons_price,
            'price' => $staff_capacity * $plan->addons_price,
            'staff_capacity' => $staff_capacity,
            'home_id' => $care_home->id,
            'type' => 'staff',
            /*'subject' => "Per Staff Addon Subscription on ".$plan->name." purchased successfully",*/
            'subject' => "Per Staff Addon purchased successfully",
        ];
        Mail::to($care_home->email)->send(new AddOnStaffSubscriptionMail($data));
        return true;
    }

    public function sendAddonPatientSubscriptionMail($patient_capacity, $plan, $subscription, $care_home)
    {
        $data = [
            'name' => $care_home->name,
            'email' => $care_home->email,
            'content' => "You have purchased Per Patient Addon successfully on " . $plan->name . " for " . $care_home->name,
            'addon_price' => $plan->addons_price,
            'price' => $patient_capacity * $plan->addons_price,
            'staff_capacity' => $patient_capacity,
            'home_id' => $care_home->id,
            'type' => 'patient',
            /*'subject' => "Per Staff Addon Subscription on ".$plan->name." purchased successfully",*/
            'subject' => "Per Patient Addon purchased successfully",
        ];

        //try {
        Mail::to($care_home->email)->send(new AddOnStaffSubscriptionMail($data));
        return true;
        // } catch (\Exception $e) {

        //     Log::error('Error sending email: ' . $e->getMessage());
        //     dd($e); // Dump and die to see the error details
        // }

    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
		$home = $this->homes->withTrashed()->find($id);
         
		//check if home belong to admin
		if(Auth::user()->role_id == 2){
			if($home->user_id != Auth::user()->id){
				return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this operation']);
			}
		}
		// $patients = $this->patients->where('home_id', $id)->orderBy('id','desc')->get();
        $patients = $this->patients->select('patients.*')
            ->where('home_id', $id)
            ->when($home != '', function ($query) use ($home) {
                if ($home->deleted_at != null) {
                    return $query->withTrashed();
                } else {
                    return $query->where('discharged', 0)
                        ->whereNull('deleted_at');
                }
            })
        /* ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, email FROM patients GROUP BY email) AS latest_patients'), function ($join) {
        $join->on('patients.id', '=', 'latest_patients.id');
        }) */
            ->orderBy('patients.id', 'desc')->get();
        $staffs = $this->users->where('home_id', $id)->where('role_id', '!=', 2)
            ->when($home != '', function ($query) use ($home) {
                if ($home->deleted_at != null) {
                    return $query->withTrashed();
                } else {
                    return $query->whereNull('deleted_at');
                }
            })
            ->orderBy('id', 'desc')
            ->get();
        // $subscription = $this->subscription->where(['care_home_id' => $id])->first();
        /*$subscription = $this->subscription->where(['care_home_id' => $id])
        ->when($home != '', function ($query) use($home) {
        if($home->deleted_at != null){
        // If home is trashed, include trashed patients
        return $query->withTrashed();
        }else{
        return  $query->whereNull('deleted_at');
        }
        })->latest('updated_at')->first();*/
        $subscription = $this->subscription->where('id', $home->subscription_id)->latest('updated_at')->first();
        $home['subscription'] = $subscription;
        $plan = $subscription ? $this->subscriptionPlan->where('stripe_price_id', $subscription->stripe_price)->orWhere('addons_stripe_price_id', $subscription->stripe_price)->first() : '';
        $user_allowed = '';
        if ($home['subscription']) {
            $user_allowed = $this->subscriptionPlan->where('stripe_price_id', $home['subscription']->stripe_price)
                ->orWhere('addons_stripe_price_id', $home['subscription']->stripe_price)
                ->orderBy('id', 'desc')
                ->first();
        }

        // $subscription_invoices = $this->subscription_payment->where('care_home_id', $id)->get();

        $subscription_invoices = $home->subscriptionPayments;

        $revenue = $this->patient_logs->getCareHomePaymentList($id);
        $revenue = $revenue->when($home != '', function ($query) use ($home) {
            if ($home->deleted_at != null) {
                // If home is trashed, include trashed patients
                return $query->withTrashed();
            } else {
                return $query->whereNull('patient_logs.deleted_at');
            }
        })->orderBy('id', 'desc')->get();
        if ($home != '' && $home->deleted_at != null) {
            $patient_expenses = $this->expense->where('home_id', $id)->onlyTrashed()->orderBy('id', 'desc')->get();
        } else {
            $patient_expenses = $home->patientExpenses;
        }
        $currently_working_users = $this->users->getActiveWorkingUsers($id);
        // echo "<pre>"; print_r($currently_working_users->toArray());die;
        return view("care-homes.show", compact('home', 'staffs', 'user_allowed', 'patients', 'plan', 'subscription_invoices', 'revenue', 'patient_expenses', 'currently_working_users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $home = $this->homes->find($id);
        //check if home belong to admin
        if (Auth::user()->role_id == 2) {
            if ($home->user_id != Auth::user()->id) {
                return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this operation']);
            }
        }
        $user = $this->users->find($home->user_id);
        $roles = $this->roles->where('id', '!=', 1)->get();
        return view("care-homes.edit", compact('home', 'user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //echo"<pre>";print_r($request->all());die;
        request()->validate([
            'name' => 'required',
            'email' => 'required',
            'contact_no' => 'required',
            //'about' => 'required',
            //'address' => 'required',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            //'capacity' => 'required',
            'location_lat' => 'required',
            'location_long' => 'required',
            'status' => 'required',
        ]);

        $this->homes->addUpdateHome($request, $id);
        return redirect()->route('homes.show', $id)->with(['type' => 'success', 'message' => 'Care home updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CareHome $home
     * @return \Illuminate\Http\Response
     */
    public function destroy(CareHome $home)
    {
        //check if home belong to admin
        if (Auth::user()->role_id == 2) {
            if ($home->user_id != Auth::user()->id) {
                return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this operation']);
            }
        }
        //delete care home and its related data
        $this->homes->deleteCareHome($home);
        return redirect()->back()->with(['type' => 'success', 'message' => 'Care home deleted successfully']);
    }

    public function subscriptionManage(Request $request, $id)
    {
        //check if id is not zero
        if ($id == 0) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'This operation is not authorized please provide a valid care home ID']);
        }
        //check if care home belong to admin
        // $home = $this->homes->with('getCareHomeSubscription')->find($id);
        $home = $this->homes->find($id);
        $home['getCareHomeSubscription'] = $this->homes->getCareHomeActiveSubscription($id);
       
        if ($id != 0 && Auth::user()->role_id && Auth::user()->id != $home->user_id) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this operation']);
        }

        $upgrade_price = $request->upgrade_price;
        $upgrade_downgrade_plan = $request->upgrade_downgrade_plan;
        $new_plan = $request->new_plan;
        $add_ons = $request->add_ons;
        // Retrieve all plans(subscription plans) from Stripe
        $stripe_mode = config('app.stripe_mode');
        $stripe_type = 0;
        if ($stripe_mode == 'Live') {
            $stripe_type = 1;
        }
        $plans = $this->subscriptionPlan->where(['status' => 1, 'stripe_mode' => $stripe_type])->get();
        return view('care-homes.upgrade-downgrade-swap-subscription', compact('plans', 'home', 'upgrade_price', 'upgrade_downgrade_plan', 'add_ons', 'new_plan'));
    }

    public function handleSubscriptionManage(Request $request)
    {

        if (isset($request->staff_capacity)) {
            request()->validate([
                'card_holder_name' => 'required',
                'staff_capacity' => 'required|numeric|min:1',
            ]);
        } else if (isset($request->patient_capacity)) {
            request()->validate([
                'card_holder_name' => 'required',
                'patient_capacity' => 'required|numeric|min:1',
            ]);
        } else {
            request()->validate([
                'card_holder_name' => 'required',
            ]);
        }

        $care_home = $this->homes->findOrFail($request->home_id);
        $plan = $this->subscriptionPlan->findOrFail($request->subscription_plan);
        $get_old_price = $this->subscription->select('id', 'stripe_price', 'type')->where('care_home_id', $request->home_id)->first();
        //dd($request->new_subscription);
        if ($request->new_subscription == 'no') {
            //just swap plan
            if (isset($request->agreed_price_update)) {
                if (!is_null($care_home->stripe_id)) {
                    // if ($care_home->subscribed($plan->name)) {
                    if ($care_home->subscribed($get_old_price->type)) {

                        try {
                            $payment_method = $care_home->defaultPaymentMethod()->id;
                            // $subscription = $care_home->subscription($plan->name);
                            $subscription = $care_home->subscription($get_old_price->type);

                            // Swap Plan
                            if ($subscription->exists) {

                                $subscription->swap($plan->stripe_price_id);

                                $this->stripe->subscriptions->update($subscription->stripe_id, ['cancel_at_period_end' => false]);

                                $care_home->subscription_id = $subscription->id;
                                $care_home->subscription_plan_id = $plan->id;
                                $care_home->save();

                                if ($request->update_notification == 'yes') {
                                    $update_notification = $this->subscription_notifications->where('home_id', $request->home_id)->update(['status' => 0, 'stripe_price_from' => $get_old_price->stripe_price, 'stripe_price_to' => $plan->stripe_price_id, 'flag' => 1, 'deleted_at' => Carbon::now()]);
                                }

                                $update = $this->subscription->where('care_home_id', $request->home_id)->where('stripe_status', 'active')->update(['stripe_price' => $plan->stripe_price_id, 'type' => $plan->name]);

                                $this->sendUpdateSubscriptionPriceMail($plan, $subscription, $care_home, $request, $get_old_price);

                            }
                            return response()->json(['status' => 'success', 'message' => 'Your Care home price has been updated with new price successfully.']);
                        } catch (\Exception $e) {
                            return response()->json(['status' => 'error', 'message' => 'Something went wrong.' . $e->getMessage()]);
                        }
                    }
                }
            }
        } else {
            //create new subscription
            // echo "<pre>"; print_r($request->all());die;
            //dd($care_home, 'Hello');
            $response = $this->buySubscription($request, $care_home);
            return response()->json($response);
        }
    }

    public function sendUpdateSubscriptionPriceMail($plan, $subscription, $care_home, $request, $get_old_price)
    {
        if ($request->update_notification == 'yes') {
            $msg = "You subscription " . $plan->name . " price has been updated successfully for " . $care_home->name;
            $subject = $plan->name . " price updated successfully";
        } else {
            $msg = "You subscription " . $get_old_price->type . " has been updated to " . $plan->name . " successfully for " . $care_home->name;
            $subject = $get_old_price->type . " plan updated to " . $plan->name . " successfully";
        }

        $data = [
            'name' => $care_home->name,
            'email' => $care_home->email,
            'content' => $msg,
            'price' => $plan->price,
            'user_allowed' => $plan->user_allowed,
            'home_id' => $care_home->id,
            'subject' => $subject,
        ];

        Mail::to($care_home->email)->send(new SubscriptionPriceUpdateMail($data));
        return true;
    }

    public function pauseSubscription(Request $request)
    {
        $care_home = $this->homes->findOrFail($request->home_id);
        $subscription = $this->stripe->subscriptions->update(
            $request->subscription_id,
            ['pause_collection' => ['behavior' => 'void']],
        );

        if ($subscription->pause_collection != null) {
            $update_subscription_status = $this->homes->where('id', $request->home_id)->update(['subscription_status' => 'pause', 'pause_at' => date('Y-m-d h:i:s'), 'resume_at' => null]);

            $data = [
                'name' => $care_home->name,
                'email' => $care_home->email,
                'content' => "Your " . $request->subscription_type . " Subscription paused successfully on " . Carbon::now() . ".",
                'home_id' => $care_home->id,
                'subject' => $request->subscription_type . ' Package Paused',
            ];

            Mail::to($care_home->email)->send(new SubscriptionCancelResumeMail($data));
            return response()->json(['status' => 'success', 'message' => 'Subscription Paused Successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Subscription not paused!']);
        }

    }

    public function resumeSubscription(Request $request)
    {
        $care_home = $this->homes->findOrFail($request->home_id);

        $subscription = $this->stripe->subscriptions->update(
            $request->subscription_id,
            [
                'pause_collection' => '',
                'cancel_at_period_end' => false,
            ],
        );

        if ($subscription->pause_collection == null || $subscription->cancel_at_period_end == false) {
            $update_subscription_status = $this->homes->where('id', $request->home_id)->update(['subscription_status' => 'resume', 'resume_at' => date('Y-m-d h:i:s'), 'pause_at' => null]);

            $resume_cancel_subscription = $this->subscription->where('stripe_id', $subscription->id)->update(['ends_at' => null]);

            $data = [
                'name' => $care_home->name,
                'email' => $care_home->email,
                'content' => "Your " . $request->subscription_type . " Subscription resumed successfully on " . Carbon::now() . ".",
                'home_id' => $care_home->id,
                'subject' => $request->subscription_type . ' Package Resumed',
            ];

            Mail::to($care_home->email)->send(new SubscriptionCancelResumeMail($data));
            return response()->json(['status' => 'success', 'message' => 'Subscription Resumed Successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Subscription not resumed!']);
        }

    }

    public function cancelSubscription(Request $request)
    {
        $care_home = $this->homes->findOrFail($request->home_id);

        if ($care_home) {
            $get_subscription = $care_home->subscription($request->subscription_type);
            if ($get_subscription) {
                // $subscription = $this->stripe->subscriptions->cancel($get_subscription->stripe_id, ['cancel_at_period_end' => true]);
                $subscription = $this->stripe->subscriptions->update($get_subscription->stripe_id, ['cancel_at_period_end' => true]);
                if ($subscription && $subscription->cancel_at_period_end == true) {
                    $update_care_home = $this->homes->where('id', $request->home_id)->update(['subscription_status' => '', 'resume_at' => null, 'pause_at' => null]);
                    $cancelSubscription = $this->subscription->where('id', $get_subscription->id)->update(['ends_at' => Carbon::createFromTimestamp($subscription['cancel_at'])->toDateTimeString()]);
                    // $deleteSubscriptionItem = $this->subscriptionItem->where('subscription_id', $get_subscription->id)->delete();

                    $data = [
                        'name' => $care_home->name,
                        'email' => $care_home->email,
                        'content' => "Your " . $request->subscription_type . " Subscription has been canceled on " . Carbon::now() . ". Your Subscription will be ended on " . Carbon::createFromTimestamp($subscription['cancel_at'])->toDateTimeString(),
                        'home_id' => $care_home->id,
                        'subject' => $request->subscription_type . ' Subscription canceled on ' . Carbon::createFromTimestamp($subscription['cancel_at'])->toDateTimeString(),
                    ];
                    Mail::to($care_home->email)->send(new SubscriptionCancelResumeMail($data));
                    return response()->json(['status' => 'success', 'message' => 'Subscription Cancelled Successfully.']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Subscription not cancelled!']);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Subscription not found!']);
            }
        }
    }

    public function getActivityTimeForm($home_id)
    {
        $home = $this->homes->find($home_id);
        $activity_time = $this->activity_time->where('home_id', $home_id)->first();
        return view("care-homes.activity-time-form", compact('activity_time', 'home'));
    }
    public function addUpdateHomeDocument($home_id, $id)
    {
        $home = $this->homes->find($home_id);
        $document = $this->home_documents->find($id);
        return view("care-homes.partials.add-update-document", compact('document', 'home', 'id', 'home_id'));
    }
    public function savehomeDocument(Request $request)
    {
        //echo"<pre>";print_r($request->all());die;
        $this->home_documents->addDocument($request);
        return redirect()->route('homes.show', $request->home_id)->with(['type' => 'success', 'message' => 'home document added successfully']);
    }

    public function saveActivityTimeForm(Request $request)
    {
        //echo"<pre>";print_r($request->all());die;
        $activity_time = $this->activity_time->addUpdateTime($request);
        return redirect()->back()->with(['type' => 'success', 'message' => 'Care home activity time added/updated successfully']);
    }
    public function deleteHomeDocument($id)
    {
        $this->home_documents->find($id)->delete();
        return redirect()->back()->with(['type' => 'success', 'message' => 'Document deleted successfully']);
    }
    public function getCareHomeStaff(Request $request)
    {

        $care_home_staff = $this->users->where(['home_id' => $request->home_id, 'status' => 1, 'deleted_at' => null])->get();
        $manager_html = '<option value="">Select</option>';
        $staff_html = '<option value="">Select</option>';
        if (!$care_home_staff->isEmpty()) {
            foreach ($care_home_staff as $user) {
                if ($user->role_id == 3) {
                    $manager_html .= '<option value="' . $user->id . '">' . $user->name . '</option>';
                } elseif ($user->role_id == 4) {
                    $staff_html .= '<option value="' . $user->id . '">' . $user->name . '</option>';
                }
            }
            return response()->json(['status' => 'success', 'message' => 'User Successfully get.', 'staff_html' => $staff_html, 'manager_html' => $manager_html]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'user not found', 'staff_html' => $staff_html, 'manager_html' => $manager_html]);
        }
    }
    public function ishomeEmailExsist(Request $request)
    {
        $email = $request->email;

        if (isset($request->id) && !empty($request->id)) {
            $res = $this->homes->where('email', $email)->first();

            if (!empty($res)) {
                if ($res->id == $request->id) {

                    $user = [];
                } else {

                    $user = $res;
                }

            } else {
                $user = [];
            }
        } else {
            $user = $this->homes->where('email', $email)->first();

        }
        if (!empty($user)) {
            return response()->json(['status' => 'success', 'user' => $user, 'message' => 'Care Home already exist', 'exsist' => true]);
        } else {
            return response()->json(['status' => 'success', 'user' => [], 'message' => 'Care Home not exist', 'exsist' => false]);
        }

    }
}
