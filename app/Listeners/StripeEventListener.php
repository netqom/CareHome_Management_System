<?php

namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CareHome;
use App\Models\SubscriptionPayment;
use App\Models\Subscription;
use App\Models\SubscriptionNotification;
use Carbon\Carbon;
use Log, Mail;
use App\Models\User;
use App\Mail\SubscriptionCancelResumeMail;

class StripeEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        if ($event->payload['type'] === 'invoice.payment_succeeded') {
			Log::channel('stripelog')->info(json_encode($event));
			$payload_data = $event->payload['data']['object'];
			Log::channel('stripelog')->info(['payload_data' => $payload_data]);
			$care_home = CareHome::where('stripe_id', $payload_data['customer'])->first();
			Log::channel('stripelog')->info(json_encode($care_home));
			$line_data    = $payload_data['billing_reason'] == 'subscription_create' || $payload_data['billing_reason'] == 'subscription_cycle' ? $payload_data['lines']['data'][0] : $payload_data['lines']['data'][1];
			$start_period = Carbon::createFromTimestamp($line_data['period']['start'])->toDateTimeString();
			$end_period   = Carbon::createFromTimestamp($line_data['period']['end'])->toDateTimeString();
			$amount       = $payload_data['amount_paid']/100;
			
			$payment_data = ['stripe_subscription_id' => $payload_data['subscription'], 
							'care_home_id'			  => $care_home->id,
							'current_period_end'      => $end_period, 
							'current_period_start'    => $start_period, 
							'unit_amount'             => number_format($amount, 2), 
							'customer'                => $payload_data['customer'], 
							'default_payment_method'  => $payload_data['default_payment_method'], 
							'stripe_status'           => $payload_data['status'],
							'hosted_invoice_url'      => $payload_data['hosted_invoice_url'], 
							'invoice_pdf'             => $payload_data['invoice_pdf'],
							'created_by'              => 0, 
							'updated_by'              => 0
						];
		    SubscriptionPayment::create($payment_data);
			User::where('home_id',$care_home->id)->whereIn('role_id',[3,4])->update(['status'=>1]);
            // Handle the incoming event...
        }else if($event->payload['type'] === 'invoice.payment_action_required'){
			Log::channel('stripelog')->info(json_encode($event));
        }else if($event->payload['type'] === 'invoice.invoice.created'){
			Log::channel('stripelog')->info(json_encode($event));
		}else if ($event->payload['type'] === 'customer.subscription.deleted') {
			Log::channel('stripelog')->info(json_encode($event));
			$payload_data = $event->payload['data']['object'];
			Log::channel('stripelog')->info(json_encode($payload_data));
			$subscription = Subscription::where('stripe_id', $payload_data['id'])->first();
			$care_home = CareHome::where('id', $subscription->care_home_id)->first();
			Log::channel('stripelog')->info(json_encode($subscription));
			$ends_at = Carbon::createFromTimestamp($payload_data['canceled_at'])->toDateTimeString();
			
			$payment_data = ['stripe_status' => $payload_data['status'], 'ends_at' => $ends_at];

		    Subscription::where('stripe_id', $payload_data['id'])->update($payment_data);
			Log::channel('stripelog')->info("care_home=====================".$care_home->name);
			User::where('home_id',$care_home->id)->whereIn('role_id',[3,4])->update(['status'=>0]);
			SubscriptionNotification::where('home_id', $subscription->care_home_id)->where('status', 1)->update(['status' => 0,'deleted_at' => Carbon::now()]);

			$data = [
				'name'  => $care_home->name,
				'email' => $care_home->email,
				'content' => "Your ".$subscription->type." package has been canceled today at (".$ends_at.").",
				'home_id' => $care_home->id,
				'subject' => $subscription->type.' Subscription canceled',
			];
			
			Mail::to($care_home->email)->send(new SubscriptionCancelResumeMail($data));
            // Handle the incoming event...
        }
		
    }
}
