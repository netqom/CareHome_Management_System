<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PatientLog;
use App\Models\CareHome;
use App\Models\Tasks;
use App\Models\PatientActivityFieldValue;
use App\Models\PatientMedicine;
use App\Models\StaffAssignedTraining;
use App\Models\User;
use App\Models\Activity;
use App\Models\Patient;

class StaffActivityHistoryController extends Controller
{
    private $patient_logs;
    private $users;
    private $patients;
    private $care_home;
    private $tasks;
    private $patient_medicine;
    private $patient_activityies;
    private $activityies;

    /**
     * Constructor Instance
     */
    public function __construct(PatientLog $patient_logs, User $users, Patient $patients, CareHome $care_home, Tasks $tasks, PatientMedicine $patient_medicine, PatientActivityFieldValue $patient_activityies, Activity $activityies)
    {
        $this->patient_logs = $patient_logs;
        $this->users = $users;
        $this->patients = $patients;
        $this->care_home = $care_home;
        $this->tasks = $tasks;
        $this->patient_medicine = $patient_medicine;
        $this->patient_activityies = $patient_activityies;
        $this->activityies = $activityies;
    }

    public function viewStaffActivityHistoryList()
    {
        $home_ids = $this->care_home->where('user_id', Auth::user()->id)
                    ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name,email FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY email) AS latest_care_homes'), function ($join) {
                        $join->on('care_homes.id', '=', 'latest_care_homes.id');
                    })
                    ->get()
                    ->pluck('id')->toArray();
        $homes = $this->care_home->where(['status' => 1])->whereIn('id', $home_ids)->withTrashed()->pluck('name', 'id');
        $managers = $this->users->where(['role_id' => 3, 'status' => 1])->whereIn('home_id', $home_ids)->withTrashed()->pluck('name', 'id');
        $staffs = $this->users->where(['role_id' => 4, 'status' => 1])->whereIn('home_id', $home_ids)->withTrashed()->pluck('name', 'id');
        // $patients = $this->patients->where(['status' => 1])->whereIn('home_id', $home_ids)->pluck('name', 'id');
        $patients = $this->patients->select('patients.name','patients.id')->where(['status' => 1])->whereIn('home_id', $home_ids)
        ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, email FROM patients GROUP BY email) AS latest_patients'), function ($join) {
            $join->on('patients.id', '=', 'latest_patients.id');
        })->get()->pluck('name', 'id');
        // $tasks = $this->tasks->where(['user_type' => 'client'])->pluck('title', 'id');
        $tasks = $this->tasks->getTaskList()->pluck('title','id');
        // $patientMedicines = $this->patient_medicine->where(['status' => 1])->pluck('name', 'id');
        $patientMedicines = $this->patients->getPatientMedicines($home_ids)->pluck('medicine_name','medicine_id');
        $patientActivities = $this->patient_activityies->where(['status' => 1])->select('name', 'id', 'field_id')->get();
        $patientActivities = $this->patient_activityies->select('patient_activity_field_values.*')
        ->join('patient_activity_fields', 'patient_activity_field_values.field_id', '=', 'patient_activity_fields.id')
        ->where('patient_activity_field_values.status', 1)
        ->whereNotIn('patient_activity_fields.slug', ['time', 'medication','medicines','Medication','Medicines'])
        ->get();;
         $reportData = $this->patient_logs->join('patients','patients.id','=','patient_logs.patient_id')->whereIn('patients.home_id', $home_ids)->where(['patient_logs.log_type' => 0])->select('patients.home_id','patient_logs.report_date', 'patient_logs.id as log_id', 'patient_logs.report_time')->get();
        $super_admin_ids = $this->users->where('role_id',1)->pluck('id')->toArray();
        $assignedActivities = $this->activityies->where('activities.created_by', Auth::user()->id)
        ->orWhereIn('activities.created_by', $super_admin_ids)->where(['status' => 1])->pluck('name', 'id');
        return view('staff-activity-history.index', compact('managers', 'staffs', 'patients','tasks', 'patientMedicines', 'patientActivities', 'assignedActivities','homes','reportData'));
    }

    public function getStaffActivityHistoryList(Request $request)
    {
        $data = $this->patient_logs->getAllLogList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'staff-activity-history.partials.list-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);

        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }

    public function getStaffTrainingList($id)
    {
        $staffTriningData = StaffAssignedTraining::join('staff_trainings as st', 'st.id', '=', 'staff_assigned_trainings.course_id')->select('st.*')->where('staff_assigned_trainings.user_id', $id)->pluck('st.title', 'st.id');
        $html = '<option></option>';
        if (!empty($staffTriningData)) {
            foreach ($staffTriningData as $k => $val) {
                $html .= '<option value="' . $k . '">' . $val . '</option>';
            }
        }
        return response()->json(['type' => 'success', 'html' => $html]);
        //dd($id);
    }
}
