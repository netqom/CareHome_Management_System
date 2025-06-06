<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanPermission;
use Stripe\Stripe;
use Stripe\Product;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Artisan;

class SubscriptionPlanController extends Controller
{
    public $subscriptionPlan;
    public $subscriptionPlanPermission;

    /**
     * Constructor Instance
     */

    public function __construct(SubscriptionPlan $subscriptionPlan,SubscriptionPlanPermission $subscriptionPlanPermission)
    {
        $this->subscriptionPlan = $subscriptionPlan;
        $this->subscriptionPlanPermission = $subscriptionPlanPermission;
        // Set your Stripe API key
        $this->stripe = new \Stripe\StripeClient(config('app.stripe_secret'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('subscription_plan.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response JSON
     */
    public function getData(Request $request)
    {
        try {
            // Retrieve all plans(subscription plans) from Stripe
            // $plans = $this->stripe->plans->all();

            // foreach($plans->data as $key => $plan){
            //     //get the product details
            //     $plans->data[$key]['products'] = $stripe->products->retrieve($plan->product, []);
            // }
            // $total_count = !empty($plans) ? count($plans) : 0;
            $plans = $this->subscriptionPlan->getPlanList($request);
            $total_count = $plans->get()->isNotEmpty() ? $plans->get()->count() : 0;
            [$offset, $limit] = $this->dataOffsetLimit($request);
            $records = $plans->skip($offset)->take($limit)->get();
            $view = 'subscription_plan.partials.subscription-list-table-body';

            $html = view($view, compact('records'))->render();
            $pagination = $this->preparePaginationData($request, $records, $total_count);
            return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
	
	 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view("subscription_plan.create");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($plan_id)
    {
        $plan = $this->subscriptionPlan->where('id', $plan_id)->first();
        //Get the specific plan
        // $subscriptionPlan = $this->stripe->plans->retrieve($plan->stripe_price_id, []);
        // $plan['product'] = $this->stripe->products->retrieve($subscriptionPlan->product, []);
        // $plan['plan_detail'] = $this->subscriptionPlan->where('stripe_price_id', $plan_id)->select('id', 'price', 'user_allowed', 'addon_status', 'addons_price')->first();
        return view("subscription_plan.edit", compact('plan','plan_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		// echo"<pre>";print_r($request->all());die;

		request()->validate([
            'name' => 'required',
            'user_allowed' => 'required',
            'patient_allowed' => 'required',
            'addons_price' => 'required',
        ]);

        $this->stripe->products->update(
            $request->stripe_product_id,
            ['name' => $request->name, 'description' => $request->description]
        );
        
        $update =  $this->subscriptionPlan->addUpdateSubscription($request);
        return redirect()->route('subscription_plans.index')->with(['type' => 'success', 'message' => 'Subscription updated successfully.']);
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            $stripeProducts = Cashier::stripe()->products->all(); 
            foreach ($stripeProducts->data as $key => $product) {                   
                $request->name = $product->name;
                $request->stripe_product_id = $product->id;
                $request->description  = $product->description;
                $request->stripe_price_id = $product->default_price;
                $request->user_allowed = 0;
                
                // Fetch prices for each product
                $prices = Cashier::stripe()->prices->all([
                    'product' => $product->id,
                ]);
                
                foreach($prices->data as $key => $price){
                    if($price->nickname != '' && $price->nickname != null){
                        $request->addons_price           = $price->unit_amount/100;
                        $request->addons_stripe_price_id  = $price->id;
                    
                    }else{
                        $duration = $price->recurring->interval == 'month' ? 1 : ($price->recurring->interval == 'day' ? 0 : 2 );
                       
                        $request->price     = $price->unit_amount/100;
                        $request->duration  = $duration; //1=Monthly, 2=Yearly
                        
                    }
                }
                
                // $existingPlan  = $this->subscriptionPlan->where('stripe_price_id', $product->default_price)->first();
                $existingPlan  = $this->subscriptionPlan->where('stripe_product_id', $product->id)->first();

                if(!$existingPlan){
                    $result =  $this->subscriptionPlan->addUpdateSubscription($request);
                }else{
                    $request->id = $existingPlan->id;
                    $result =  $this->subscriptionPlan->addUpdateSubscription($request);
                }
            }
            return response()->json(['type' => 'success', 'msg' => 'Plans sync with database table successfully']);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function subscriptionPrice($plan_id)
     {
        $plan = $this->subscriptionPlan->where('id', $plan_id)->first();
        // echo "<pre>"; print_r($plan);die;
        return view("subscription_plan.create-subscription-price", compact('plan','plan_id'));
     }
    
    public function updateSubscriptionPrice(Request $request){
        try {
            // Get the plan detail
            $plan_id = $request->id;
            $realAmount = $request->price;// Get real_amount from the form
            $staffAddonAmount = $request->addons_price;// Get real_amount from the form
            $patientAddonAmount = $request->patient_addons_price;// Get real_amount from the form
            
            dispatch(function () use ($realAmount, $plan_id, $staffAddonAmount, $patientAddonAmount) {
                Artisan::call('command:changeSubscription', ['--real_amount' => $realAmount, '--plan_id' => $plan_id, '--staff_addon_price' => $staffAddonAmount, '--patient_addon_price' => $patientAddonAmount]);
            }); 

            return redirect()->route('subscription_plans.index')->with(['type' => 'success', 'message' => 'Subscription with new price updated successfully.']);
            
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateSubscriptionPermission(Request $request)
    { 
        $this->subscriptionPlanPermission->addUpdatePermissions($request);
        return redirect()->route('subscription_plans.index')->with(['type' => 'success', 'message' => 'Permission updated successfully.']);
    }

    // public function updateSubscriptionPrice(Request $request){
    //     try {
    //         // Get the product
    //         $product = Cashier::stripe()->products->retrieve($request->stripe_product_id); 
           
    //         if($product){
    //             // Get the default price for the product
    //             $price = Cashier::stripe()->prices->retrieve($product->default_price);
    //             if($price){
    //                 $amount     = $price->unit_amount/100;
    //                 $stripe_price_id = $product->default_price;

    //                 $update = $this->subscriptionPlan->where('id',$request->id)->update(['price' => $amount, 'stripe_price_id' => $stripe_price_id]);

    //                 return response()->json(['type' => 'success', 'msg' => 'Subscription plan price updated successfully']);
    //             }
    //         }else{
    //             return response()->json(['type' => 'error', 'msg' => 'Product not found']);
    //         }
    //     }catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
}
