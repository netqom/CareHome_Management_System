<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CareHome;
use Str, Arr, Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\UserAppInfo;

class CheckSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-subscription-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'App User Log Out, if the care home subscription status not active.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Retrieve active care homes
        $careHomes = CareHome::join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name,email FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY email) AS latest_care_homes'), function ($join) {
            $join->on('care_homes.id', '=', 'latest_care_homes.id');
            })
            ->where('status', '1')
            ->whereNull('deleted_at')
            ->get();
        \Log::info(['careHomesDD' => $careHomes->toArray()]);

        foreach($careHomes as $key => $careHome){
            $careHomeSubscription = $careHome->getCareHomeActiveSubscription($careHome->id);
            \Log::info(['careHomeSubscriptionsDD' => $careHomeSubscription]);
            if($careHomeSubscription->stripe_status != 'active'){
                $staffs_id = User::where('home_id', $careHome->id)
                            ->whereIn('role_id', [4,5])
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->pluck('id');
                // echo "<pre>"; print_r($staffs_id);die;
                \Log::info(['staffsDD' => $staffs_id]);

                UserAppInfo::whereIn('user_id', $staffs_id)->delete();
                // Delete tokens for all staff members when care home status is 0
                \Laravel\Sanctum\PersonalAccessToken::whereIn('tokenable_id', $staffs_id)->delete();

            }
        }
    }
}
