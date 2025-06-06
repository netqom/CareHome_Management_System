<?php
/*
 *
 *    This file contains the common functions helpfull in working with common functionality
 *    Note : This file needs to be included in composer.json (autoload->files)
 *
 *
 */

//have this file loaded in composer autoload->files
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

if (!function_exists('checkactivesection')) {
    function checkactivesection($string, $position = false, $status = 'mm-active')
    {
        if ($position !== false) {
            return Request::segment($position) == $string ? $status : '';
        } else {
            return in_array($string, Request::segments()) ? $status : '';
        }
    }
}

if (!function_exists('lastsegment')) {
    function lastsegment($url)
    {
        $segments = explode('/', $url);
        return end($segments);
    }
}

/**
 * check the url if is the current path or at a specified position in array
 * @param string $string
 */
if (!function_exists('checkactivepage')) {
    function checkactivepage($string, $position = false, $status = 'mm-active')
    {
        if ($position !== false) {
            return Request::segment($position) == $string ? $status : '';
        } else {
            return in_array($string, Request::segments()) ? $status : '';
        }
    }
}

if (!function_exists('getSubscriptionPlanData')) {
    function getSubscriptionPlanData($stripe_id)
    {
        return \App\Models\SubscriptionPlan::where('stripe_price_id', $stripe_id)->first();
    }
}

if (!function_exists('getAdminCareHome')) {
    function getAdminCareHome($id)
    {
        $homes = \App\Models\CareHome::where('user_id', $id)->get();
        if (count($homes) > 0) {
            if (count($homes) > 1) {
                return $homes[0]->name . " +" . (count($homes) - 1);
            } else {
                return $homes[0]->name;
            }
        } else {
            return '-';
        }
    }
}

/**
 * format price upto 2 decimal place and
 * @param string $string
 */
if (!function_exists('createCancelUrl')) {
    function amountFormat($number)
    {
        $currency_sign = '$';
        return $currency_sign . number_format((float) $number, 2);
    }
}

/**
 * check previous url and create redirect url
 * @param string $string
 */
if (!function_exists('createCancelUrl')) {
    function createCancelUrl($default)
    {
        $previous_url = url()->previous();
        $current_url = url()->current();
        if ($previous_url == $current_url) {
            return $default;
        } else {
            return $previous_url;
        }
    }
}

/**
 * Get all activity shift available
 * @param empty
 */
if (!function_exists('getActivityShifts')) {
    function getActivityShifts()
    {
        return config('const.activity_shifts');
    }
}

/**
 * Get activity shift name by ID
 * @param $id
 */
if (!function_exists('getActivityShiftName')) {
    function getActivityShiftName($id)
    {
        $shifts = config('const.activity_shifts');
        return isset($shifts[$id]) ? $shifts[$id] : '';
    }
}

/**
 * Get all work shift available
 * @param null
 */
if (!function_exists('getGeofencingStatuses')) {
    function getGeofencingStatuses()
    {
        return config('const.geofencing_status');
    }
}

/**
 * Get all work shift available
 * @param null
 */
if (!function_exists('getWorkShifts')) {
    function getWorkShifts()
    {
        return config('const.work_shifts');
    }
}

/**
 * Get work shift name by ID
 * @param $id
 */
if (!function_exists('getWorkShiftName')) {
    function getWorkShiftName($id)
    {
        $shifts = config('const.work_shifts');
        return isset($shifts[$id]) ? $shifts[$id] : '';
    }
}

/**
 * Get care home Auth Info
 * @param $id
 */
if (!function_exists('getCareHomeAuthInfo')) {
    function getCareHomeAuthInfo($info)
    {
        if (Auth::user()->role_id == 2) {
            return Auth::user()->{$info};
        }
        return '';
    }
}

/**
 * Get patient image
 * @param $id
 */
if (!function_exists('getPatientImage')) {
    function getPatientImage($id)
    {
        $patient = App\Models\Patient::find($id);
        $image = asset('assets/img/patient_dummy.png');
        if ($patient && !is_null($patient->profile_image)) {
            if (file_exists(public_path($patient->profile_image))) {
                $image = url($patient->profile_image);
            }
        }
        return $image;
    }
}

/**
 * Get patient image
 * @param $id
 */
if (!function_exists('getUserImage')) {
    function getUserImage($id)
    {
        $user = App\Models\User::find($id);
        $image = asset('assets/img/profile-pic.jpg');
        if (!empty($user->profile_image) && !is_null($user->profile_image)) {
            if (file_exists(public_path($user->profile_image))) {
                $image = url($user->profile_image);
            }
        }
        return $image;
    }
}

if (!function_exists('getUserName')) {
    function getUserName($id)
    {
        $user = App\Models\User::find($id);
        $name = '';
        if (!is_null($user->name)) {
            $name = $user->name;
        }
        return $name;
    }
}

if (!function_exists('getActivityCategoryName')) {
    function getActivityCategoryName($id)
    {
        $user = App\Models\PatientActivityField::find($id);
        $name = '';
        if (!is_null($user->name)) {
            $name = $user->name;
        }
        return $name;
    }
}

if (!function_exists('getPatientName')) {
    function getPatientName($id)
    {
        $user = App\Models\Patient::find($id);
        $name = '';
        if (!is_null($user->name)) {
            $name = $user->name;
        }
        return $name;
    }
}

/**
 * Get shift name from id
 * @param $id
 */
if (!function_exists('getShiftName')) {
    function getShiftName($id)
    {
        if ($id == 1) {
            return 'Morning';
        } else if ($id == 2) {
            return 'Afternoon';
        } else if ($id == 3) {
            return 'Evening';
        } else if ($id == 4) {
            return 'Night';
        } else if ($id == 5) {
            return 'Ad-Hoc';
        }
    }
}

/**
 * Get count of total users allwoed with addons staff capacity
 * @param $id
 */

if (!function_exists('checkUserAllowedCapacity')) {
    function checkUserAllowedCapacity($id)
    {
        $home = \App\Models\CareHome::find($id);

        $subscription = \App\Models\Subscription::where(['care_home_id' => $id])->first();
        $user_allowed = '';
        $total_user_allowed = '';
        if ($subscription) {
            $user_allowed = \App\Models\SubscriptionPlan::select('user_allowed')->where('stripe_price_id', $subscription->stripe_price)
                ->orWhere('addons_stripe_price_id', $subscription->stripe_price)->first();

            if ($user_allowed) {
                $total_user_allowed = $user_allowed->user_allowed + $home->staff_capacity;
            }

            return $total_user_allowed;
        }
    }
}

if (!function_exists('checkPatientAllowedCapacity')) {
    function checkPatientAllowedCapacity($id)
    {
        $home = \App\Models\CareHome::find($id);

        $subscription = \App\Models\Subscription::where(['care_home_id' => $id])->first();
        $patientCount = \App\Models\Patient::where(['home_id' => $id, 'status' => 1, 'discharged' => 0])->whereNull('deleted_at')->count();
        $user_allowed = '';
        $total_user_allowed = 0;
        if ($subscription) {
            $user_allowed = \App\Models\SubscriptionPlan::select('patient_allowed')->where('stripe_price_id', $subscription->stripe_price)
                ->orWhere('addons_stripe_price_id', $subscription->stripe_price)->first();

            if ($user_allowed) {
                $total_user_allowed = $home->patient_capacity + $user_allowed->patient_allowed;
                if ($total_user_allowed > $patientCount) {
                    return true;
                } else {
                    return false;
                }
                //$total_user_allowed = $user_allowed->user_allowed + $home->staff_capacity;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('countAddedStaff')) {
    function countAddedStaff($id)
    {
        $staffs = \App\Models\User::where('home_id', $id)->where('role_id', '!=', 2)->where('deleted_at', null)->count();
        // echo $staffs;die;
        return $staffs;
    }
}

if (!function_exists('medicineName')) {
    function medicineName($id)
    {
        $name = '';
        $medicine = \App\Models\PatientMedicine::where('id', $id)->select('name')->first();
        if ($medicine) {
            $name = $medicine->name;
        }
        // echo $staffs;die;
        return $name;
    }
}

if (!function_exists('previousPlanUserAllowed')) {
    function previousPlanUserAllowed($id)
    {
        $home = \App\Models\CareHome::find($id);

        $subscription = \App\Models\Subscription::where(['care_home_id' => $id])->first();
        $user_allowed = '';
        if ($subscription) {
            $user_allowed = \App\Models\SubscriptionPlan::select('user_allowed')->where('stripe_price_id', $subscription->stripe_price)
                ->orWhere('addons_stripe_price_id', $subscription->stripe_price)->first();

            if ($user_allowed) {
                $total_user_allowed = $user_allowed->user_allowed;
            }

            return $total_user_allowed;
        }
    }
}

if (!function_exists('countAddedActiveStaff')) {
    function countAddedActiveStaff($id)
    {
        $staffs = \App\Models\User::where(['home_id' => $id, 'status' => 1])->where('role_id', '!=', 2)->where('deleted_at', null)->count();
        // echo $staffs;die;
        return $staffs;
    }
}

/** Get CareHome Active Subscription */
if (!function_exists('getCareHomeSubscription')) {
    function getCareHomeSubscription($plan_name)
    {
        $care_home_subscription = \App\Models\Subscription::where('type', $plan_name)->where('stripe_status', 'active')->get();
        return $care_home_subscription;
    }
}

/**
 * Check if patient belong to Admin nursing home
 * @param $id
 */

if (!function_exists('checkPatientBelongToAdminHome')) {
    function checkPatientBelongToAdminHome($id)
    {
        if (Auth::user()->role_id == 2) {
            $patient = \App\Models\Patient::withTrashed()->find($id);
            $home = \App\Models\CareHome::withTrashed()->find($patient->home_id);
            if (Auth::user()->id != $home->user_id) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}

/** Get Discount on Plan*/
if (!function_exists('getDiscountOnPlan')) {
    function getDiscountOnPlan($plan_id)
    {
        $get_discount = \App\Models\Discount::where(['plan_id' => $plan_id, 'status' => 1])->where('end_date', '>=', Carbon::now()->toDateString())->first();
        return $get_discount;
    }
}

/** Get CareHome Active Staff */
if (!function_exists('getCareHomeStaff')) {
    function getCareHomeStaff($home_id)
    {
        $care_home_staff = \App\Models\User::where(['home_id' => $home_id, 'status' => 1, 'deleted_at' => null])->get();
        return $care_home_staff;
    }
}

/** Get CareHome Patient */
if (!function_exists('getCareHomePatientCount')) {
    function getCareHomePatientCount($home_id)
    {
        $patient_count = 0;
        $patient_count = \App\Models\Patient::where(['home_id' => $home_id, 'discharged' => 0, 'status' => 1])->whereNull('deleted_at')->count();
        return $patient_count;
    }
}

/** Get CareHome Patient */
if (!function_exists('getCareHomePatient')) {
    function getCareHomePatient($home_id)
    {
        $care_home_patient = \App\Models\Patient::select('patients.id', 'patients.home_id', 'patients.name', 'patients.email', 'patients.created_by')->where(['patients.home_id' => $home_id, 'patients.deleted_at' => null, 'patients.discharged' => 0])
            ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, email FROM patients GROUP BY email) AS latest_patients'), function ($join) {
                $join->on('patients.id', '=', 'latest_patients.id');
            })
            ->get();
        return $care_home_patient;
    }
}

/** Get Staff count */
if (!function_exists('getStaffCount')) {
    function getStaffCount($home_id)
    {
        $count = 0;
        $count = \App\Models\User::where(['home_id' => $home_id, 'deleted_at' => null])->count();
        return $count;
    }
}

/*******************************************************************API Helper**********************************************************************************/

/**
 * Get care home Auth Info
 * @param $id
 */
if (!function_exists('checkManager')) {
    function checkManager($type, $id)
    {
        if ($type == 'staff') {
            $user = App\Models\User::find($id);
            if (Auth::user()->home_id != $user->home_id) {
                return false;
            }
        } else if ($type == 'patient') {
            $patient = App\Models\Patient::find($id);
            if (Auth::user()->home_id != $user->home_id) {
                return false;
            }
        }
        return true;

    }
}

if (!function_exists('changeDateFormat')) {
    function changeDateFormat($date, $newFormat = 'm-d-Y')
    {
        $currentFormat = 'Y-m-d';
        return Carbon::createFromFormat($currentFormat, $date)->format($newFormat);
    }
}
if (!function_exists('getUserDetail')) {
    function getUserDetail($id)
    {

        return $user = App\Models\User::find($id);
    }
}
if (!function_exists('isSubscriptionEnd')) {
    function isSubscriptionEnd()
    {
        $current_date = Carbon::now();
        $current_date = $current_date->format('Y-m-d H:i:s');
        return $homes = \App\Models\CareHome::join('subscriptions as sp', 'sp.care_home_id', '=', 'care_homes.id')->where('care_homes.user_id', Auth::user()->id)->latest('sp.updated_at')->select('sp.*', 'care_homes.*', 'care_homes.id as home_id')->get();

    }
}
if (!function_exists('checkTimezone')) {
    function checkTimezone($logTimes)
    {

        $currentTime = Carbon::now();

        // Convert the current time to PDT timezone
        $currentTimePDT = $currentTime->setTimezone('America/Los_Angeles');

        // Get the current time as hours and minutes
        $currentHourMinute = $currentTimePDT->format('H:i');

        // Iterate through the log times array
        foreach ($logTimes as $key => $timeRange) {
            // Extract start and end times from the range
            list($start, $end) = explode(' - ', $timeRange);

            // Convert start and end times to Carbon instances
            $startTime = Carbon::createFromFormat('Y-m-d H:i', $currentTimePDT->format('Y-m-d') . ' ' . $start, 'America/Los_Angeles');
            $endTime = Carbon::createFromFormat('Y-m-d H:i', $currentTimePDT->format('Y-m-d') . ' ' . $end, 'America/Los_Angeles');
            //  dd($currentTimePDT,$logTimes,$startTime, $endTime,$key);
            // Check if the current time falls within this range
            if ($currentTimePDT->between($startTime, $endTime)) {

                return $key; // Return the key if current time falls within this range
            }
        }

        // Return null if no matching range found
        return null;

    }
}
if (!function_exists('renderFormFields')) {
    function renderFormFields($items, $activities)
    {

        $typeMapping = [
            0 => 'heading',
            1 => 'text',
            2 => 'radio',
            3 => 'checkbox',
            4 => 'select',
            5 => 'multiselect',
            6 => 'time',
            7 => 'textarea',
        ];
        foreach ($items as $item) {
            $type = $typeMapping[$item['type']] ?? 'text';

            // Render heading if type is 0
            if ($type === 'heading') {
                echo "<h3>{$item['name']}</h3>";
            } elseif ($item['name'] == 'Activity' || $item['name'] == 'activity') {
                echo "<input type='hidden' name='form_data[{$item['id']}][field_value]' value=''>";
                echo "<div class='form-group form-group-activity row'>";
                echo "<label for='{$item['slug']}'>{$item['name']}</label>";
                echo '<div class="activity-container">
            <ul style="display:block;">';
                foreach ($activities as $act) {

                    echo "<li> <input type='checkbox' name='activity_data[$act->id][activity_id]' value='{$act->id}' class='activity_check_box' data-id='{$act->id}' data-name='{$act->name}'>{$act->name}</input><input type='hidden' name='activity_data[$act->id][status]' id='status_activity_{$act->id}'><input type='hidden' name='activity_data[$act->id][remark]' id='remark_activity_{$act->id}'></li>";
                }

                echo '</ul>
            </div>';

            } else {

                echo "<div class='form-group'>";
                echo "<label for='{$item['slug']}'>{$item['name']}</label>";

                switch ($type) {
                    case 'text':
                        echo "<input type='text' name='form_data[{$item['id']}][field_value]' id='{$item['slug']}' class='form-control'>";
                        break;
                    case 'radio':
                        // Implement radio button rendering here
                        break;
                    case 'checkbox':
                        // Implement checkbox rendering here
                        break;
                    case 'select':
                        echo "<select name='form_data[{$item['id']}][field_value]' id='{$item['slug']}' class='form-control' onchange='handleRemarkChange(this)'>";
                        foreach ($item['options'] as $option) {
                            echo "<option value='{$option['id']}' data-remark='{$option['remark']}'>{$option['name']}</option>";
                        }
                        echo "</select>";
                        echo "<div class='remark-container' id='{$item['slug']}-remark' style='display:none; margin-top:10px;'>";
                        echo "<label for='{$item['slug']}-remark-details'>Add Comment </label>";
                        echo "<textarea name='form_data[{$item['id']}][remark]' id='{$item['slug']}-remark-details' class='form-control'></textarea>";
                        echo "</div>";
                        break;
                    case 'multiselect':
                        echo "<select name='form_data[{$item['id']}][field_value][]' id='{$item['slug']}' class='form-control multi-select' multiple>";
                        foreach ($item['options'] as $option) {
                            echo "<option value='{$option['id']}' data-remark='{$option['remark']}'>{$option['name']}</option>";
                        }
                        echo "</select>";
                        break;
                    case 'time':
                        echo "<input type='time' name='form_data[{$item['id']}][field_value]' id='{$item['slug']}' class='form-control'>";
                        break;
                    case 'textarea':
                        echo "<textarea name='form_data[{$item['id']}][field_value]' id='{$item['slug']}' class='form-control'></textarea>";
                        break;
                    default:
                        echo "<input type='text' name='form_data[{$item['id']}][field_value]' id='{$item['slug']}' class='form-control'>";
                        break;
                }

                echo "</div>";
            }

            // If there are subchilds, recursively render them
            if (!empty($item['subchilds'])) {
                renderFormFields($item['subchilds'], $typeMapping);
            }
        }
    }
}
if (!function_exists('getPlanName')) {
    function getPlanName($id)
    {
        $name = '';
        $plan = \App\Models\SubscriptionPlan::where('id', $id)->select('name', 'duration')->first();
        if ($plan) {
            $name = $plan->duration == 1 ? $plan->name . ' (Monthly)' : $plan->name . ' (Yearly)';
        }
        return $name;
    }
}
if (!function_exists('chatMessageUserCount')) {
    function chatMessageUserCount()
    {   

        $chat_count = \App\Models\Chat::join('chat_messages', 'chats.id', '=', 'chat_messages.chat_id')
        // ->where('chats.user_id', Auth::user()->id)
        ->where(function($query) {
            $query->where('chats.user_id', Auth::user()->id)
                ->orWhere('chats.created_by', Auth::user()->id);
        })
        ->where('chat_messages.sender_id', '!=', Auth::user()->id)
        ->where('chat_messages.seen', 0)
        ->count('chat_messages.id');

        $feedback_chat_count = \App\Models\Chat::join('chat_messages', 'chats.id', '=', 'chat_messages.chat_id')
        ->where('chats.is_feedback', 1)
        ->where(function($query) {
            $query->where('chats.user_id', '!=', Auth::user()->id)
                ->orWhere('chats.created_by', '!=', Auth::user()->id);
        })
        ->where('chat_messages.sender_id', '!=', Auth::user()->id)
        ->where('chat_messages.seen', 0)
        ->count('chat_messages.id');

        // Combine both counts
        $total_chat_count = $chat_count + $feedback_chat_count;

        return $total_chat_count;  // Make sure to return the count
    }
}
if (!function_exists('getFileIcon')) {

    function getFileIcon($file)
    {
        $res['fileIcon'] = '';
        $res['iconColorClass'] = '';
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'pdf':
                $fileIcon = '<i class="fas fa-file-pdf text-navy"></i>';
                break;
            case 'jpg':
                
                $fileIcon = '<i class="fas fa-file-image text-navy"></i>';
                break;
            case 'png':
                $fileIcon = '<i class="fas fa-file-image text-navy"></i>';
                break;
            case 'gif':
                $fileIcon = '<i class="fas fa-file-image text-navy"></i>';
                break;
            default:
                $fileIcon = '<i class="fas fa-file-word text-navy"></i>';
                break;
        }
        $res['fileIcon'] = $fileIcon;
        return $res;
    }
}
