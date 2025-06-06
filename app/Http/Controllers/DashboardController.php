<?php

namespace App\Http\Controllers;

use App\Models\CareHome;
use App\Models\Notification;
use App\Models\PatientLog;
use App\Models\PatientPayment;
use App\Models\Subscription;
use App\Models\SubscriptionNotification;
use App\Models\SubscriptionPayment;
use App\Models\Tasks;
use App\Models\User;
use App\Models\UserShiftHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private $users;
    private $homes;
    private $subscription;
    private $patientlogs;
    private $patientpayment;
    private $notifications;
    private $subscription_notifications;
    private $subscription_payment;
    private $user_shift_history;
    private $tasks;

    /**
     * Constructor Instance
     */
    public function __construct(User $users, CareHome $homes, Subscription $subscription, PatientLog $patientlogs, PatientPayment $patientpayment, Notification $notifications, SubscriptionNotification $subscription_notifications, UserShiftHistory $user_shift_history, Tasks $tasks, SubscriptionPayment $subscription_payment)
    {
        $this->users = $users;
        $this->homes = $homes;
        $this->subscription = $subscription;
        $this->patientlogs = $patientlogs;
        $this->patientpayment = $patientpayment;
        $this->notifications = $notifications;
        $this->subscription_notifications = $subscription_notifications;
        $this->user_shift_history = $user_shift_history;
        $this->tasks = $tasks;
        $this->subscription_payment = $subscription_payment;

    }

    public function index(Request $request)
    {
        $subhomeEnd = isSubscriptionEnd();
        $total_regisetered_users = $this->users->countTotalRegisteredUser();
        $total_nursing_homes = $this->homes->countTotalRegisteredHomes();
        $total_revenue = $last_month_revenue = $last_week_revenue = 0;

        if (Auth::user()->role_id == 1) {
            $total_revenue = $this->subscription->getSubscriptionList($request)->get()->sum('plan_price');
            $total_addOns = $this->subscription->getSubscriptionList($request)->get()->sum('addon_plan_price');
            $total_revenue = $total_revenue + $total_addOns;

            $last_month_revenue = $this->subscription->getLastMonthData()->get()->sum('plan_price');
            $last_month_addOns = $this->subscription->getLastMonthData()->get()->sum('addon_plan_price');
            $last_month_revenue = $last_month_revenue + $last_month_addOns;

            $last_week_revenue = $this->subscription->getLastWeekData()->get()->sum('plan_price');
            $last_week_addOns = $this->subscription->getLastWeekData()->get()->sum('addon_plan_price');
            $last_week_revenue = $last_week_revenue + $last_week_addOns;
        } else {
            $total_revenue = $this->patientlogs->getAdminRevenue();
            $last_month_revenue = $this->patientlogs->getLastMonthData();
            $last_week_revenue = $this->patientlogs->getLastWeekData();
        }
        $notifications = $this->notifications->getLatestNotification(15);
        $subscription_notifications = $this->subscription_notifications->getSubscriptionNotification();
        $currently_working_users = $this->users->getActiveWorkingUsers();

        $care_homes_data = $this->homes->getList($request)->select('care_homes.id', 'care_homes.name', 'care_homes.image', 'care_homes.location_long', 'care_homes.location_lat', 'care_homes.created_by')->get();

        return view('dashboard', compact('total_regisetered_users', 'total_nursing_homes', 'total_revenue', 'last_month_revenue', 'last_week_revenue', 'notifications', 'subscription_notifications', 'currently_working_users', 'care_homes_data', 'subhomeEnd'));
    }

}
