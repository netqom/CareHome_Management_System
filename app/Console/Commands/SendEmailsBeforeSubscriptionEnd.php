<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;
use App\Mail\SubscriptionExpireSoonMail;
use App\Models\SubscriptionPayment;
use Mail;

class SendEmailsBeforeSubscriptionEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-subscription-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for end subscription before one week';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiryDate = Carbon::now()->addWeek()->format('Y-m-d');
        $subscriptions = Subscription::join('care_homes as ch', 'ch.subscription_id', '=', 'subscriptions.id')->leftJoin('subscription_payments as sp', 'sp.stripe_subscription_id', '=', 'subscriptions.stripe_id')->where('ch.status',1)
        ->whereDate(\DB::raw('DATE(sp.current_period_end)'), $expiryDate)->select('ch.user_id', 'ch.name', 'ch.id','subscriptions.ends_at','sp.current_period_end','sp.stripe_subscription_id','subscriptions.stripe_id','ch.subscription_id','subscriptions.id')
        ->get();
    //  dd($subscriptions,$expiryDate);
        foreach($subscriptions as $subscription){
            $email = getUserDetail($subscription->user_id)->email;
           // dd($email);
            $user_name = getUserDetail($subscription->user_id)->name;
            $data = [
                'home_name'  => $subscription->name,
                'user_name'  => $user_name,
                'email' => $email,
                'end_date' => $subscription->ends_at,
                'content'  => "Your Subscription will expire soon for ".$subscription->name,
                'home_id' => $subscription->id,
                'subject' => "Subscription expire soon",
            ];
            Mail::to($email)->send(new SubscriptionExpireSoonMail($data));
        }
        dd('success');
    }
}
