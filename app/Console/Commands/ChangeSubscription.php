<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use App\Events\CreateNotification;

class ChangeSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:changeSubscription {--real_amount=} {--plan_id=} {--staff_addon_price=} {--patient_addon_price=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change subscription price logic goes here';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $realAmount = $this->option('real_amount');
        $staffAddonAmount = $this->option('staff_addon_price');
        $patientAddonAmount = $this->option('patient_addon_price');
        $planId = $this->option('plan_id');
        $this->createNewStripePlan($realAmount, $planId, $staffAddonAmount, $patientAddonAmount);
    }

    public function createNewStripePlan($realAmount, $planId, $staffAddonAmount, $patientAddonAmount)
    {
        $plan = SubscriptionPlan::find($planId);
        $care_home_subscription = getCareHomeSubscription($plan->name);
        // echo "<pre>"; print_r($care_home_subscription->toArray());die;
        $interval = $plan->duration == 1 ? 'month' : ($plan->duration == 2 ? 'year' : 'day');
        
        $stripe = new \Stripe\StripeClient(config('app.stripe_secret'));
        $new_amount = $realAmount * 100;
        $new_price = $stripe->prices->create([
            'currency' => 'usd',
            'unit_amount' => $new_amount,
            'recurring' => ['interval' => $interval],
            'product' => $plan->stripe_product_id,
        ]);

        $staff_new_addon_price = $stripe->prices->create([
            'currency' => 'usd',
            'unit_amount' => $staffAddonAmount*100,
            'recurring' => ['interval' => $interval],
            'product' => $plan->stripe_product_id,
            'nickname' => 'Staff Addon Price',
        ]);

        $patient_new_addon_price = $stripe->prices->create([
            'currency' => 'usd',
            'unit_amount' => $patientAddonAmount*100,
            'recurring' => ['interval' => $interval],
            'product' => $plan->stripe_product_id,
            'nickname' => 'Patient Addon Price',
        ]);
        Log::channel('subscription_price_update')->info(json_encode($new_price));
        if($new_price && $staff_new_addon_price && $patient_new_addon_price){
            $plan->stripe_price_id = $new_price->id;
            $plan->price       = $realAmount;

            $plan->addons_stripe_price_id = $staff_new_addon_price->id;
            $plan->addons_price       = $staffAddonAmount;

            $plan->patient_addons_stripe_price_id = $patient_new_addon_price->id;
            $plan->patient_addons_price       = $patientAddonAmount;
            if($plan->save()){
                foreach($care_home_subscription as $key => $home_subscription){
                    $home_subscription['new_price'] = $realAmount;
                    // echo "<pre>"; print_r($home_subscription->toArray());die;
                    $updateSubscription = $stripe->subscriptions->update($home_subscription->stripe_id, ['cancel_at_period_end' => true]); 

                    event(new CreateNotification('subscription_price_update', $home_subscription));
                }
                // $update = Subscription::where('type', $plan->name)->where('stripe_status','active')->update(['stripe_price' => $new_price->id]);
            }
		    return true;
        }
    }

}
