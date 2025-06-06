<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use App\Models\SubscriptionPayment;
use App\Models\CareHome;
use Carbon\Carbon;
use Laravel\Cashier\Cashier;
use App\Models\User;
use Stripe\Stripe;
use Laravel\Cashier\Subscription as StripeSubscription;

class CheckSubscriptionEnds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if any subscriptions are ending today and deactivate associated care home users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $careHomes = CareHome::all();
        $stripe =Stripe::setApiKey(config('app.stripe_secret'));
        $homeids=[];
        $result=[];
       if(!$careHomes->isEmpty())
       {
        foreach ($careHomes as $home) {
           
               
            $latestSubscription = StripeSubscription::where('care_home_id', $home->id)
            ->where('stripe_id', 'like', 'sub_%')
            ->latest('updated_at')
            ->first();
            if(!empty($latestSubscription) && $latestSubscription->ends_at!=null && $latestSubscription->stripe_status=='canceled')
            {
               $homeids[]=$home->id;
            }
             }
        dd($homeids);
       }
    }
}
