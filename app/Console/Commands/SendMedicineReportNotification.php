<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PatientLog;
use App\Models\Patient;
use App\Models\User;
use App\Models\CareHome;
use App\Models\PatientMedicine;
use App\Models\ActivityTime;
use Str, Arr, Auth;
use Carbon\Carbon;
use App\Events\CreateNotification;
use App\Models\Notification;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\DB;

class SendMedicineReportNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:send-medicine-report-notification';
    protected $signature = 'notifications:send-medicine-report-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to staff about patient medicine report before 1 hour of shift end if the report has not been added.';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $title ='Add Patient Medicine Report';
        $today = Carbon::today('PST8PDT');
        \Log::info(['todayDate' => $today]);
        $currentTime = Carbon::now('PST8PDT');   
        \Log::info(['currentTime' => $currentTime,'timezone' =>  $currentTime->format('T')]);
        // $currentTime = Carbon::parse('2024-08-28 00:01:00'); // Your test time
        // Retrieve active care homes
        $careHomes = CareHome::join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name,email FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY email) AS latest_care_homes'), function ($join) {
        $join->on('care_homes.id', '=', 'latest_care_homes.id');
        })
        ->where('status', '1')
        ->whereNull('deleted_at')
        ->get();

        \Log::info(['careHomes' => $careHomes->toArray()]);
        // $careHome = CareHome::where('id','43')->get();
       
        foreach($careHomes as $key => $careHome){
            \Log::info(['single_carehome' => $careHome]);
            if ($careHome) {
                $activity_time = $careHome->activity_time;
                \Log::info(['carehome_activity' => $activity_time]);
                // $shiftTimes = [
                //     'morning' => $this->parseShiftTime($activity_time->morning_time),
                //     'afternoon' => $this->parseShiftTime($activity_time->afternoon_time),
                //     'evening' => $this->parseShiftTime($activity_time->evening_time),
                //     'night' => $this->parseShiftTime($activity_time->night_time),
                // ];
                $shiftTimes = [
                    'morning' => isset($activity_time) && $activity_time->morning_time ? $this->parseShiftTime($activity_time->morning_time) : $this->parseShiftTime('5:00 - 11:59'),
                    'afternoon' => isset($activity_time) && $activity_time->afternoon_time ? $this->parseShiftTime($activity_time->afternoon_time): $this->parseShiftTime('12:00 - 16:59'),
                    'evening' => isset($activity_time) && $activity_time->evening_time ? $this->parseShiftTime($activity_time->evening_time): $this->parseShiftTime('17:00 - 23:59'),
                    'night' => isset($activity_time) && $activity_time->night_time ? $this->parseShiftTime($activity_time->night_time): $this->parseShiftTime('00:00 - 04:59'),
                ];

                $shiftMapping = [
                    'morning' => '1',
                    'afternoon' => '2',
                    'evening' => '3',
                    'night' => '4',
                ];
                
                // Determine the current shift
                $currentShift = null;
                $previousShift = null;
                $previousShiftName = null;

                foreach ($shiftTimes as $shiftName => $times) {

                    if ($this->isCurrentShift($currentTime, $times)) {
                        $currentShift = $shiftName;
                        break;
                    }

                    $shiftEndTime = Carbon::parse($times['end']);
                    $shiftStartTime = Carbon::parse($times['start']);
                    if ($currentTime->greaterThan($shiftEndTime)) {
                        $previousShift = $shiftEndTime;
                        $previousShiftName = $shiftName;
                    }
                    
                    // Notify admin if the report is still missing after shift end
                    // $shiftEndTime = Carbon::parse($times['end']);
                    // if ($currentTime->greaterThan($shiftEndTime)) {
                    //     $this->notifyAdmin($careHome, $shiftName, $today);
                    // }

                    if ($previousShift && $currentTime->greaterThan($previousShift)) {
                        \Log::info('Current Time: ' . $currentTime);
                        \Log::info('Previous Shift End Time: ' . $previousShift);
                        \Log::info('Previous Shift Name: ' . $previousShiftName);
                        
                        \Log::info('admin_test notification');
                        $this->notifyAdmin($careHome, $previousShiftName, $today);
                    }
                }
            
                if ($currentShift) {
                    $care_home_id = $careHome->id;
                    // $patients = $careHome->patients;
                    $patients = Patient::where('home_id', $care_home_id)
                        ->where('status', 1)
                        ->where('discharged', 0)
                        ->whereNull('deleted_at')
                        ->get();
                  
                    $this->sendNotificationsForShift($currentShift, $shiftTimes[$currentShift], $currentTime, $today, $shiftMapping[$currentShift], $care_home_id, $patients);
                  
                    // Notify admin if the report is still missing after shift end 
                    // if($previousShift && $currentTime->greaterThan($previousShift)){
                    //     \Log::info('admin_test notification');
                    //     $this->notifyAdmin($careHome, $previousShiftName, $today);
                    // }
                }
            }
        }
    }

    /**
     * Parse the shift time string into start and end times.
     *
     * @param string $shiftTime
     * @return array
     */
    private function parseShiftTime($shiftTime)
    {
        if (!$shiftTime || strpos($shiftTime, ' - ') === false) {
            return ['start' => null, 'end' => null];
        }

        [$start, $end] = explode(' - ', $shiftTime);
        return ['start' => $start, 'end' => $end];
    }

    /**
     * Determine if the current time is within the shift times.
     *
     * @param \Carbon\Carbon $currentTime
     * @param array $shiftTimes
     * @return bool
     */
    private function isCurrentShift($currentTime, $shiftTimes)
    {
        $shiftStartTime = Carbon::parse($shiftTimes['start']);
        $shiftEndTime = Carbon::parse($shiftTimes['end']);
        return $currentTime->between($shiftStartTime, $shiftEndTime);
    }

    /**
     * Send notifications for the given shift.
     *
     * @param \App\Models\CareHome $careHome
     * @param string $shiftName
     * @param array $shiftTimes
     * @param \Carbon\Carbon $currentTime
     * @param \Carbon\Carbon $today
     * @param string $shiftId
     */
    private function sendNotificationsForShift($shiftName, $shiftTimes, $currentTime, $today, $shiftId, $care_home_id, $patients)
    {
        $shiftEndTime = Carbon::parse($shiftTimes['end']);
        $oneHourBeforeEnd = $shiftEndTime->copy()->subHour();
        $thirtyMinutesBeforeEnd = $shiftEndTime->copy()->subMinutes(30);
        $fifteenMinutesBeforeEnd = $shiftEndTime->copy()->subMinutes(15);

        
        if ($this->shouldSendNotification($currentTime, $oneHourBeforeEnd, $shiftEndTime, $care_home_id, $shiftName, 60)
            || $this->shouldSendNotification($currentTime, $thirtyMinutesBeforeEnd, $shiftEndTime, $care_home_id, $shiftName, 30)
            || $this->shouldSendNotification($currentTime, $fifteenMinutesBeforeEnd, $shiftEndTime, $care_home_id, $shiftName, 15)) {

            $staffs = User::where('home_id', $care_home_id)
            ->where('role_id', 4)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->where(function ($query) use ($shiftId) {
                $query->where('shift_id', $shiftId)
                      ->orWhere('shift_id', 5);
            })
            ->get();
           
            foreach ($staffs as $staff) {
                \Log::info(['patients hii']);
                foreach ($patients as $patient) {
                    $patient_medicines = PatientMedicine::where('patient_id', $patient->id)
                        ->where('status', 1)
                        ->where('is_discontinue',0)
                        ->whereNull('deleted_at')
                        ->whereJsonContains('time_id', $shiftId)  // Check if shift_id exists in time_id array
                        ->get();

                    // If patient has active medicines, proceed
                    if ($patient_medicines->isNotEmpty()) {

                        \Log::info(['patient_medicines' => $patient_medicines->toArray()]);

                        $report = PatientLog::where('patient_id', $patient->id)
                            ->where('log_type', '1')
                            ->where('is_in_house', '1')
                            ->whereDate('report_date', $today)
                            ->first();
                        if ($report) {
                            \Log::info(['report yes']);
                            $reportTime = $report->report_time;
                            \Log::info(['shiftName' => $shiftName, 'reportTime' => $reportTime, 'patient_id' => $patient->id]);
                            if ($this->isShiftInReportTime($shiftName, $reportTime)) {
                                \Log::info(['report continue']);
                                continue;
                            }
                        }

                        $app_message = "Add the " . $shiftName . " shift medicine report of " . $patient->name . ".";
                        $notificationData = [
                            'home_id' => $care_home_id,
                            'patient_id' => $patient->id,
                            'staff_id' => $staff->id,
                            'item_id' => $report ? $report->id : 0,
                            'item_type' => 'patient_medicine_report_missing',
                            'app_message' => $app_message,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        Notification::insert($notificationData);

                        try {
                            $firebaseService = new FirebaseService();
                            $response = $firebaseService->sendNotification($staff->id, 'Patient Medicine Report Missing', $app_message);
                        } catch (\Exception $e) {
                            Log::error('Error sending notification to user ' . $staff->id . ': ' . $e->getMessage());
                            continue;
                        }   
                    }
                }
            }
        }
    }

    /**
     * Check if a notification should be sent for the given time window.
     *
     * @param \Carbon\Carbon $currentTime
     * @param \Carbon\Carbon $windowStart
     * @param \Carbon\Carbon $windowEnd
     * @param int $careHomeId
     * @param string $shiftName
     * @param int $windowMinutes
     * @return bool
     */
    private function shouldSendNotification($currentTime, $windowStart, $windowEnd, $careHomeId, $shiftName, $windowMinutes)
    {
        \Log::info(['windowStart' => $windowStart, 'windowEnd' => $windowEnd]);
        if ($currentTime->between($windowStart, $windowEnd)) {
            
            // Check for existing notification for today with the same care home, shift, and within the time window

            $notificationExists = Notification::where('home_id', $careHomeId)
                ->where('item_type', 'patient_medicine_report_missing')
                ->whereDate('created_at', Carbon::today('PST8PDT')) // Check if created today
                ->where('created_at', '>=', $windowStart)
                ->where('created_at', '<=', $windowEnd)
                ->where('app_message', 'LIKE', "%$shiftName%")
                ->first();

            // $notificationExists = Notification::where('home_id', $careHomeId)
            //     ->where('item_type', 'patient_medicine_report_missing')
            //     ->where('app_message', 'LIKE', "%$shiftName%")
            //     ->whereDate('created_at', Carbon::today()) // Check if created today
            //     ->where(function ($query) use ($windowStart, $windowEnd) {
            //         $query->whereBetween('created_at', [$windowStart, $windowEnd]);
            //     })
            //     ->first();

            \Log::info(['notificationExists' => $notificationExists]);

            // $earlierNotificationsExist = Notification::where('home_id', $careHomeId)
            //     ->where('item_type', 'patient_medicine_report_missing')
            //     ->where('created_at', '<', $windowStart)
            //     ->whereDate('created_at', Carbon::today()) // Ensure only today's notifications are checked
            //     ->where('app_message', 'LIKE', "%$shiftName%")
            //     ->where(function ($query) use ($windowMinutes) {
            //         $query->whereRaw('TIMESTAMPDIFF(MINUTE, created_at, ?) >= ?', [now(), $windowMinutes]);
            //     })
            //     ->first();

            // \Log::info(['earlierNotificationsExist' => $earlierNotificationsExist]);
            // if (!$notificationExists && !$earlierNotificationsExist) {
            //     return true;
            // }
            // return !$notificationExists && !$earlierNotificationsExist;
            return !$notificationExists;
        }

        return false;
    }


    /**
     * Check if the shift name exists in the report time.
     *
     * @param string $shiftName
     * @param string $reportTime
     * @return bool
     */
    private function isShiftInReportTime($shiftName, $reportTime)
    {
        $shiftMapping = [
            'morning' => '1',
            'afternoon' => '2',
            'evening' => '3',
            'night' => '4',
        ];

        // return isset($shiftMapping[$shiftName]) && strpos($reportTime, $shiftMapping[$shiftName]) !== false;

        // Check if the shift name exists in the mapping and if it's in the report time
        if (isset($shiftMapping[$shiftName])) {
            $shiftValue = $shiftMapping[$shiftName];
            // return strpos($reportTime, $shiftValue) !== false; // Return true if shift is found in report time

            // Decode the reportTime JSON string
            $reportTimeArray = json_decode($reportTime, true); // Decode to an associative array

            // Check if the shift value exists in the report time array
            return isset($reportTimeArray[$shiftValue]);
        }

        return false; // Shift name not found in mapping
    }

    // private function notifyAdmin($careHome, $shiftName, $today)
    // {
    //     \Log::info(['notify_admin' => $careHome, 'shiftName' => $shiftName, 'today' => $today]);
    //     $admin = $careHome->admin;

    //     // Mapping shift names to shift numbers
    //     $shiftMapping = [
    //         'morning' => 1,
    //         'afternoon' => 2,
    //         'evening' => 3,
    //         'night' => 4
    //     ];

    //     // Get shift number based on $shiftName
    //     $shiftNumber = $shiftMapping[$shiftName] ?? null; // Defaults to null if the shiftName doesn't exist

    //     if (!$shiftNumber) {
    //         \Log::warning('Invalid shift name: ' . $shiftName);
    //         return; // Exit if invalid shift name is provided
    //     }

    //     // First Check: Patients without any logs for the day
    //     $patientsWithoutLogs = Patient::where('home_id', $careHome->id)
    //         ->where('status', 1)
    //         ->whereNull('deleted_at')
    //         ->where('discharged', 0)
    //         ->whereNotExists(function ($query) use ($today, $careHome) {
    //             $query->select(DB::raw(1))
    //                 ->from('patient_logs')
    //                 ->whereColumn('patient_logs.patient_id', 'patients.id')
    //                 ->where('patient_logs.log_type', '1')
    //                 ->where('patient_logs.is_in_house', '1')
    //                 ->whereDate('patient_logs.report_date', $today);
    //         })
    //         ->get();

    //     // Second Check: Patients with logs but missing specific shifts
    //     $patientsWithLogsButMissingShifts = Patient::where('home_id', $careHome->id)
    //     ->where('status', 1)
    //     ->whereNull('deleted_at')
    //     ->where('discharged', 0)
    //     ->whereExists(function ($query) use ($today) {
    //         $query->select(DB::raw(1))
    //             ->from('patient_logs')
    //             ->whereColumn('patient_logs.patient_id', 'patients.id')
    //             ->where('patient_logs.log_type', '1')
    //             ->where('patient_logs.is_in_house', '1')
    //             ->whereDate('patient_logs.report_date', $today);
    //     })
    //     ->where(function ($query) use ($today, $shiftNumber) {
    //         // Check if the patient logs are missing for the given shift number
    //         $query->where(function ($subQuery) use ($today, $shiftNumber) {
    //             $subQuery->whereNotExists(function ($missingShiftQuery) use ($shiftNumber, $today) {
    //                 $missingShiftQuery->select(DB::raw(1))
    //                     ->from('patient_logs')
    //                     ->whereColumn('patient_logs.patient_id', 'patients.id')
    //                     ->where('patient_logs.log_type', '1')
    //                     ->where('patient_logs.is_in_house', '1')
    //                     ->whereDate('patient_logs.report_date', $today)
    //                     ->whereRaw("json_contains_path(patient_logs.report_time, 'one', '$.\"$shiftNumber\"')");
    //             });
    //         });
    //     })
    //     ->get();

    //     // Merge the results of both queries
    //     $patientsWithoutReports = $patientsWithoutLogs->merge($patientsWithLogsButMissingShifts);

    //     if ($admin && $patientsWithoutReports->isNotEmpty()) {

    //         // Check if a notification has already been sent for this shift
    //         $existingNotification = Notification::where('home_id', $careHome->id)
    //         ->where('staff_id', $admin->id)
    //         ->where('item_type', 'patient_medicine_report_admin')
    //         ->whereDate('created_at', $today)
    //         ->where('message', 'LIKE', "%$shiftName%")
    //         ->first();

    //         if (!$existingNotification) {
    //             foreach ($patientsWithoutReports as $patient) {
    //                 $app_message = 'The ' . $shiftName . ' shift medicine report is still missing for patient ' . $patient->name . '.';
    //                 $notificationData = [
    //                     'home_id' => $careHome->id,
    //                     'patient_id' => $patient->id,
    //                     'staff_id' => $admin->id,
    //                     'item_id' => 0,
    //                     'item_type' => 'patient_medicine_report_admin',
    //                     'message' => $app_message,
    //                     // 'app_message' => $app_message,
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ];

    //                 Notification::insert($notificationData);
    //             }
    //         }
    //     }
    // }

    private function notifyAdmin($careHome, $shiftName, $today)
    {
        \Log::info(['notify_admin' => $careHome, 'shift_name' => $shiftName, 'today' => $today]);
        $admin = $careHome->admin;

        // Mapping shift names to shift numbers
        $shiftMapping = [
            'morning' => 1,
            'afternoon' => 2,
            'evening' => 3,
            'night' => 4
        ];

        // Get shift number based on $shiftName
        $shiftNumber = $shiftMapping[$shiftName] ?? null; // Defaults to null if the shiftName doesn't exist

        if (!$shiftNumber) {
            \Log::warning('Invalid shift name: ' . $shiftName);
            return; // Exit if invalid shift name is provided
        }

        // Get patients who are active and in-house
        $patients = Patient::where('home_id', $careHome->id)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->where('discharged', 0)
            ->get();

        $patientsWithoutReports = collect();

        // Iterate through each patient and check if they have missing medicine reports for the current shift
        foreach ($patients as $patient) {
            // Fetch active medicines for the patient where the shift_id exists in time_id array
            $patient_medicines = PatientMedicine::where('patient_id', $patient->id)
                ->where('status', 1)  // Active medicines
                ->where('is_discontinue', 0)  // Not discontinued
                ->whereNull('deleted_at')
                ->whereJsonContains('time_id', (string)$shiftNumber)  // Check if time_id contains the shift
                ->get();

            // Log patient medicine data for debugging
            \Log::info(['patient_medicines' => $patient_medicines->toArray()]);

            // Only proceed if the patient has active medicines for this shift
            if ($patient_medicines->isNotEmpty()) {
                // Check if the report is already available for the patient for the given shift
                $report = PatientLog::where('patient_id', $patient->id)
                    ->where('log_type', '1')
                    ->where('is_in_house', '1')
                    ->whereDate('report_date', $today)
                    ->first();

                if ($report) {
                    $reportTime = $report->report_time;

                    // Check if the shift is missing in the report
                    if (!$this->isShiftInReportTime($shiftName, $reportTime)) {
                        // If the shift report is missing, add the patient to the collection
                        $patientsWithoutReports->push($patient);
                    }
                } else {
                    // If no report exists at all, add the patient to the collection
                    $patientsWithoutReports->push($patient);
                }
            }
        }

        if ($admin && $patientsWithoutReports->isNotEmpty()) {
            \Log::info("patient without report not empty");
            // Check if a notification has already been sent for this shift
            $existingNotification = Notification::where('home_id', $careHome->id)
                ->where('staff_id', $admin->id)
                ->where('item_type', 'patient_medicine_report_admin')
                ->whereDate('created_at', $today)
                ->where('message', 'LIKE', "%$shiftName%")
                ->first();

            if (!$existingNotification) {
                \Log::info("hi admin");
                foreach ($patientsWithoutReports as $patient) {
                    $app_message = 'The ' . $shiftName . ' shift medicine report is still missing for patient ' . $patient->name . '.';
                    $notificationData = [
                        'home_id' => $careHome->id,
                        'patient_id' => $patient->id,
                        'staff_id' => $admin->id,
                        'item_id' => 0,
                        'item_type' => 'patient_medicine_report_admin',
                        'message' => $app_message,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    Notification::insert($notificationData);
                }
            }
        }
    }

}
